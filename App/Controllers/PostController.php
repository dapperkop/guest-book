<?php

namespace App\Controllers;

use App\Models\Post;
use DateTime;

class PostController extends MainController
{
    public function list()
    {
        $posts = Post::all();

        $this->render('posts.list', ['posts' => $posts]);
    }

    public function post()
    {
        $lastPost = Post::getLast();
        $currentDateTime = new DateTime;

        if ($lastPost) {
            $diff = $currentDateTime->getTimestamp() - $lastPost->createdAt->getTimestamp();

            if ($diff < 10) {
                $this->redirect('/posts/add?error=1&message=' . urlencode('Once every 10 sec'));
            }
        }

        $data = $_POST;

        $replyAt = empty($data['replyAt']) ? NULL : $data['replyAt'];

        if ($replyAt) {
            $reply = Post::getById($replyAt);

            if (empty($reply)) {
                $this->redirect('/posts/add?error=1&message=' . urlencode('Reply not found'));
            }
        }

        $content = $data['content'];

        if (empty($content)) {
            $this->redirect('/posts/add?error=1&message=' . urlencode('Empty content'));
        }

        $post = new Post;
        $post->replyAt = $replyAt;
        $post->createdAt = new DateTime;
        $post->content = $content;

        if (Post::create($post)) {
            $this->redirect('/posts?success=1');
        } else {
            $this->redirect('/posts/add?error=1&message=' . urlencode('Not saved'));
        }
    }

    public function add()
    {
        $this->render('posts.add');
    }

    public function update($postId)
    {
        $post = Post::getById($postId);

        if (empty($post)) {
            $this->redirect('/posts?error=1&message=' . urlencode('Post not found'));
        }

        $data = $_POST;
        $content = $data['content'];

        if (empty($content)) {
            $this->redirect('/posts/edit/' . $postId . '?error=1&message=' . urlencode('Empty content'));
        }

        $post->updatedAt = new DateTime;
        $post->content = $content;

        if (Post::update($post)) {
            $this->redirect('/posts?success=1#post-' . $post->id);
        } else {
            $this->redirect('/posts/edit/' . $postId . '?error=1&message=' . urlencode('Not saved'));
        }
    }

    public function edit($postId)
    {
        $post = Post::getById($postId);

        if (empty($post)) {
            $this->redirect('/posts?error=1&message=' . urlencode('Post not found'));
        }

        $this->render('posts.edit', ['post' => $post]);
    }
}
