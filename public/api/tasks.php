<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\TaskController;

$taskController = new TaskController();

$action = $_GET['action'] ?? '';

$method = match($action) {
    'create' => 'create',
    'update-status' => 'updateStatus',
    'update' => 'update',
    'delete' => 'delete',
    default => null
};

if ($method && method_exists($taskController, $method)) {
    $taskController->$method();
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode([
        'success' => false, 
        'message' => 'Ação inválida. Ações permitidas: create, update-status, update, delete'
    ]);
}
