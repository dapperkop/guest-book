<?php

namespace App\Models;

use DateTime;
use App\Storage\GuestBook as Storage;

class Post
{
    public ?string $id = NULL;
    public ?string $replyAt = NULL;
    public ?DateTime $createdAt = NULL;
    public ?DateTime $updatedAt = NULL;
    public string $content = '';

    public static function all(): array
    {
        $posts = Storage::getAllPosts();

        usort($posts, function ($a, $b) {
            return $b->createdAt->getTimestamp() - $a->createdAt->getTimestamp();
        });

        return $posts;
    }

    public static function getLast(): ?Post
    {
        $posts = static::all();

        if (empty($posts)) {
            return NULL;
        }

        return $posts[0];
    }

    public static function getById(string $id): ?Post
    {
        $posts = Storage::getAllPosts();

        foreach ($posts as $post) {
            if ($post->id === $id) {
                return $post;
            }
        }

        return NULL;
    }

    public static function create(Post $post): bool
    {
        return Storage::addPost($post);
    }

    public static function update(Post $post): bool
    {
        return Storage::editPost($post);
    }
}
