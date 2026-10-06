<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

final class Post
{
    /** @param list<Category> $categories */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $content,
        public readonly ?string $image,
        private int $views,
        public readonly DateTimeImmutable $publishedAt,
        public readonly array $categories = [],
    ) {
        Assert::positiveInteger($id);
        Assert::stringNotEmpty($title);
        Assert::stringNotEmpty($slug);
        Assert::greaterThanEq($views, 0);
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): void
    {
        ++$this->views;
    }

    /** @return list<int> */
    public function getCategoryIds(): array
    {
        return array_map(
            fn (Category $category): int => $category->id,
            $this->categories
        );
    }
}
