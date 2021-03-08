<?php

namespace App\Storage;

use App\Models\Post;
use App\Storage\XML\Storage;

class GuestBook
{
    private static ?IStorage $storage = NULL;

    public static function init()
    {
        if (static::$storage === NULL) {
            static::$storage = new Storage;
        }
    }

    public static function getAllPosts(): array
    {
        return static::$storage->getAllPosts();
    }

    public static function addPost(Post $post): bool
    {
        return static::$storage->addPost($post);
    }

    public static function editPost(Post $post): bool
    {
        return static::$storage->editPost($post);
    }
}
