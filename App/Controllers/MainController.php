<?php

namespace App\Controllers;

class MainController
{
    protected ?\App\View\IView $view = NULL;

    public function __construct()
    {
        $this->view = new \App\View\Blade;
    }

    public function render(string $view, array $data = [], array $mergeData = [])
    {
        echo $this->view->render($view, $data, $mergeData);
        die;
    }

    public function redirect($location = '/posts')
    {
        header('Location: ' . $location);
        die;
    }
}
