<?php

define('ROOT_DIR', realpath(__DIR__ . '/../'));
require_once ROOT_DIR . "/vendor/autoload.php";

$guestBook = \App\GuestBook::getInstance();
$guestBook->run();
