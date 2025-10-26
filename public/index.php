<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Auth;
use App\Utils\Config;

Auth::startSession();

if (Auth::isLoggedIn()) {
    header('Location: ' . Config::baseUrl('tasks.php'));
} else {
    header('Location: ' . Config::baseUrl('login.php'));
}
exit;