<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;

final class PostService
{
    private const RELATED_POSTS_LIMIT = 3;

    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    /** @return array{post: Post, related: list<Post>}|null */
    public function getPost(string $slug): ?array
    {
        $post = $this->postRepository->findBySlug($slug);
        if ($post === null) {
            return null;
        }

        $this->postRepository->incrementViews($post->id);
        $post->incrementViews();
        $related = $this->postRepository->findRelated(
            $post->id,
            $post->getCategoryIds(),
            self::RELATED_POSTS_LIMIT,
        );

        return [
            'post' => $post,
            'related' => $related,
        ];
    }
}
