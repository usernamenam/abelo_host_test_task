<?php

declare(strict_types=1);

namespace App\DTO;

final class PaginationMeta
{
    public readonly int $currentPage;
    public readonly int $totalPages;
    public readonly ?int $previousPage;
    public readonly ?int $nextPage;

    public function __construct(
        int $page,
        public readonly int $totalItems,
        public readonly int $perPage,
    ) {
        $this->totalPages = (int) ceil($totalItems / $perPage);
        $this->currentPage = $page;
        $this->previousPage = $this->currentPage > 1
            ? $this->currentPage - 1
            : null;
        $this->nextPage = $this->currentPage < $this->totalPages
            ? $this->currentPage + 1
            : null;
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }
}
