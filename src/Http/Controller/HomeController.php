<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Framework\View;
use App\Service\CategoryService;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categoryService,
    ) {
    }

    public function index(): string
    {
        return $this->view->render('home.tpl', [
            'categories' => $this->categoryService->getHomeCategories(),
        ]);
    }
}
