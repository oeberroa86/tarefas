<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TaskController;

$taskController = new TaskController();
$taskController->index();