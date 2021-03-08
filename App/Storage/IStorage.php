<?php

namespace App\Storage;

use App\Models\Post;

interface IStorage
{
    public function getAllPosts(): array;
    public function addPost(Post $post): bool;
    public function editPost(Post $post): bool;
}
