<?php

namespace App;

use \App\Router\Bramus as Router;
use \App\Router\IRouter;
use \App\Storage\GuestBook as Storage;

final class GuestBook
{
    private static ?GuestBook $instance = NULL;
    private ?IRouter $router = NULL;

    public static function getInstance(): GuestBook
    {
        if (static::$instance === NULL) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->router = new Router;
        Storage::init();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function run()
    {
        $this->router->run();
    }
}
