<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Framework\Request;
use App\Framework\View;
use App\Service\CategoryService;

final class CategoryController
{
    private const SORT_OPTIONS = ['date' => 'По дате', 'views' => 'По просмотрам'];

    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categoryService,
    ) {
    }

    public function index(): string
    {
        $sort = Request::getQueryString('sort', 'date');
        if (!isset(self::SORT_OPTIONS[$sort])) {
            $sort = 'date';
        }

        $page = $this->categoryService->getPage(
            Request::getQueryString('slug'),
            $sort,
            Request::getQueryInt('page'),
        );
        if ($page === null) {
            return $this->view->error(404, 'Категория не найдена.');
        }

        return $this->view->render('category.tpl', [
            'category' => $page->category,
            'posts' => $page->posts,
            'pagination' => $page->paginationMeta,
            'sort' => $page->sort,
            'sortOptions' => self::SORT_OPTIONS,
        ]);
    }
}
