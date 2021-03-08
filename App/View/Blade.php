<?php

namespace App\View;

use Jenssegers\Blade\Blade as BladeView;

class Blade implements IView
{
    private ?BladeView $view = NULL;

    public function __construct()
    {
        $this->view = new BladeView(ROOT_DIR . '/views', ROOT_DIR . '/cache');
    }

    public function render(string $view, array $data = [], array $mergeData = []): string
    {
        return $this->view->render($view, $data, $mergeData);
    }
}
