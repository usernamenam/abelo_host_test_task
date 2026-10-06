<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CategoryPage;
use App\DTO\PaginationMeta;
use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

final class CategoryService
{
    private const HOME_POSTS_LIMIT = 3;
    private const POSTS_PER_PAGE = 6;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly PostRepository $postRepository,
    ) {
    }

    /** @return list<array{category: Category, posts: list<Post>}> */
    public function getHomeCategories(): array
    {
        $categories = $this->categoryRepository->findWithPosts();
        $postsByCategory = $this->postRepository->findRecentByCategory(self::HOME_POSTS_LIMIT);
        $groups = [];

        foreach ($categories as $category) {
            $groups[] = [
                'category' => $category,
                'posts' => $postsByCategory[$category->id] ?? [],
            ];
        }

        return $groups;
    }

    public function getPage(string $slug, string $sort, int $page): ?CategoryPage
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if ($category === null) {
            return null;
        }

        $totalPosts = $this->postRepository->countByCategory($category->id);
        $pagination = new PaginationMeta($page, $totalPosts, self::POSTS_PER_PAGE);
        $posts = $this->postRepository->findByCategory($category->id, $sort, $pagination);

        return new CategoryPage(
            $category,
            $posts,
            $pagination,
            $sort,
        );
    }
}
