<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return list<Category> */
    public function findWithPosts(): array
    {
        $statement = $this->pdo->prepare(
            '
            SELECT c.* FROM categories c
            WHERE 
                EXISTS (SELECT 1 FROM category_post cp WHERE cp.category_id = c.id)
            ORDER BY c.name, c.id
            ',
        );
        $statement->execute();
        $rows = $statement->fetchAll();

        return array_map($this->hydrate(...), $rows);
    }

    public function findBySlug(string $slug): ?Category
    {
        $statement = $this->pdo->prepare('SELECT * FROM categories WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    private function hydrate(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            $row['name'],
            $row['slug'],
            $row['description'],
        );
    }
}
