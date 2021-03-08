<?php

namespace App\Router;

use Bramus\Router\Router as BramusRouter;

class Bramus implements IRouter
{
    private ?BramusRouter $router = NULL;

    public function __construct()
    {
        $this->router = new BramusRouter;
        $this->router->setNamespace('\App\Controllers');

        $this->router->get('/', function () {
            header('Location: /posts');
        });
        $this->router->get('/posts', 'PostController@list');
        $this->router->post('/posts', 'PostController@post');
        $this->router->get('/posts/add', 'PostController@add');
        $this->router->post('/posts/{postId}', 'PostController@update');
        $this->router->get('/posts/edit/{postId}', 'PostController@edit');
    }

    public function run()
    {
        $this->router->run();
    }
}
