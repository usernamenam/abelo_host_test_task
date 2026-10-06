<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\PaginationMeta;
use App\Entity\Category;
use App\Entity\Post;
use DateTimeImmutable;
use PDO;

final class PostRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findBySlug(string $slug): ?Post
    {
        $statement = $this->pdo->prepare('SELECT * FROM posts WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();
        if (!$row) {
            return null;
        }

        $categories = $this->findCategories((int) $row['id']);
        return $this->hydrate($row, $categories);
    }

    public function incrementViews(int $postId): void
    {
        $statement = $this->pdo->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $statement->execute(['id' => $postId]);
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM category_post WHERE category_id = :id');
        $statement->execute(['id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    /** @return list<Post> */
    public function findByCategory(int $categoryId, string $sort, PaginationMeta $pagination): array
    {
        $orderBy = $sort === 'views'
            ? 'p.views DESC, p.published_at DESC, p.id DESC'
            : 'p.published_at DESC, p.id DESC';
        $statement = $this->pdo->prepare(
            "SELECT p.* FROM posts p
             INNER JOIN category_post cp ON cp.post_id = p.id
             WHERE cp.category_id = :categoryId
             ORDER BY {$orderBy} LIMIT :limit OFFSET :offset",
        );
        $statement->bindValue('categoryId', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $pagination->perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $pagination->getOffset(), PDO::PARAM_INT);
        $statement->execute();

        return array_map($this->hydrate(...), $statement->fetchAll());
    }

    /** @return array<int, list<Post>> */
    public function findRecentByCategory(int $limit): array
    {
        $statement = $this->pdo->prepare(
            'SELECT ranked.* FROM (
                SELECT p.*, cp.category_id,
                       ROW_NUMBER() OVER (
                           PARTITION BY cp.category_id ORDER BY p.published_at DESC, p.id DESC
                       ) AS position
                FROM posts p
                INNER JOIN category_post cp ON cp.post_id = p.id
             ) ranked
             WHERE ranked.position <= ?
             ORDER BY ranked.category_id, ranked.published_at DESC, ranked.id DESC',
        );
        $statement->execute([$limit]);
        $rows = $statement->fetchAll();
        $posts = [];
        foreach ($rows as $row) {
            $catId = (int) $row['category_id'];
            $posts[$catId][] = $this->hydrate($row);
        }

        return $posts;
    }

    /**
     * @param int[] $categoryIds
     * @return Post[]
     */
    public function findRelated(int $postId, array $categoryIds, int $limit): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $implodedCategoryIds = implode(',', $categoryIds);
        $sql = "
            SELECT DISTINCT p.*
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
             WHERE cp.category_id IN ($implodedCategoryIds)
                  AND p.id <> ?
             ORDER BY p.published_at DESC, p.id DESC
             LIMIT ?
        ";

        $statement = $this->pdo->prepare($sql);
        $statement->execute([$postId, $limit]);

        return array_map($this->hydrate(...), $statement->fetchAll());
    }

    /** @return list<Category> */
    private function findCategories(int $postId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.* FROM categories c
             INNER JOIN category_post cp ON cp.category_id = c.id
             WHERE cp.post_id = :postId ORDER BY c.id',
        );
        $statement->execute(['postId' => $postId]);

        $categories = [];
        foreach ($statement->fetchAll() as $row) {
            $categories[] = new Category(
                (int) $row['id'],
                $row['name'],
                $row['slug'],
                $row['description'],
            );
        }

        return $categories;
    }

    /**
     * @param $categories Category[]
     */
    private function hydrate(array $row, array $categories = []): Post
    {
        return new Post(
            (int) $row['id'],
            $row['title'],
            $row['slug'],
            $row['description'],
            $row['content'],
            $row['image'],
            (int) $row['views'],
            new DateTimeImmutable($row['published_at']),
            $categories,
        );
    }
}
