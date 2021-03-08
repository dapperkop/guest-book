<?php

namespace App\View;

interface IView
{
    public function __construct();
    public function render(string $view, array $data, array $mergeData): string;
}
