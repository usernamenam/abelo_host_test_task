<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Framework\Request;
use App\Framework\View;
use App\Service\PostService;

final class PostController
{
    public function __construct(
        private readonly View $view,
        private readonly PostService $postService,
    ) {
    }

    public function show(): string
    {
        $page = $this->postService->getPost(Request::getQueryString('slug'));
        if ($page === null) {
            return $this->view->error(404, 'Статья не найдена.');
        }

        return $this->view->render('post.tpl', [
            'post' => $page['post'],
            'categories' => $page['post']->categories,
            'similar_posts' => $page['related'],
        ]);
    }
}
