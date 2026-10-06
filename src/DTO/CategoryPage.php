<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Post;

final class CategoryPage
{
    /** @param Post[] $posts */
    public function __construct(
        public readonly Category       $category,
        public readonly array          $posts,
        public readonly PaginationMeta $paginationMeta,
        public readonly string         $sort,
    ) {

    }
}
