<?php
namespace App\Controllers;

use App\Models\Task;
use App\Utils\Auth;
use App\Utils\Middleware;
use App\Utils\Config;
use App\Utils\Validator;
use App\Requests\TaskRequest; 

class TaskController {
    private $taskModel;

    public function __construct() {
        $this->taskModel = new Task();
    }

    public function index() {
        Middleware::auth();//permite solo user autenticado
        
        $filter = $_GET['filter'] ?? 'all';
        $userId = Auth::getUserId();
        $tasks = $this->taskModel->getByUser($userId, $filter);
        
        $pageTitle = "Minhas Tarefas - " . Config::get('system.site_name');
        
        $viewData = [
            'tasks' => $tasks,
            'filter' => $filter,
            'pageTitle' => $pageTitle
        ];
        
        extract($viewData);
        require_once __DIR__ . '/../views/tasks/index.php';
    }

    public function create() {
        Middleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST, TaskRequest::rules(), TaskRequest::messages());
            
            if ($validator->fails()) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => $validator->getErrors()
                ]);
                return;
            }

            $userId = Auth::getUserId();
            $title = trim($_POST['title']);
            $description = trim($_POST['description'] ?? '');

            if ($this->taskModel->create($userId, $title, $description)) {
                $lastId = $this->taskModel->getLastInsertId();
                $task = $this->taskModel->findById($lastId, $userId);

                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Tarefa criada com sucesso',
                    'task' => $task
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Erro ao criar a tarefa'
                ]);
            }
        }
    }

    public function updateStatus() {
        Middleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;
            $userId = Auth::getUserId();

            if (!$taskId || !in_array($status, ['pendente', 'concluida'])) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Dados inválidos'
                ]);
                return;
            }

            if ($this->taskModel->updateStatus($taskId, $userId, $status)) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Estado atualizado'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Erro ao atualizar'
                ]);
            }
        }
    }

    public function update() {
        Middleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST, TaskRequest::rules(), TaskRequest::messages());
            
            if ($validator->fails()) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => $validator->getErrors()
                ]);
                return;
            }

            $taskId = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $userId = Auth::getUserId();

            if (!$taskId) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'ID da tarefa inválido'
                ]);
                return;
            }

            if ($this->taskModel->update($taskId, $userId, $title, $description)) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Tarefa atualizada'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Erro ao atualizar a tarefa'
                ]);
            }
        }
    }

    public function delete() {
        Middleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['id'] ?? null;
            $userId = Auth::getUserId();

            if (!$taskId) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'ID inválido'
                ]);
                return;
            }

            if ($this->taskModel->delete($taskId, $userId)) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Tarefa eliminada'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Erro ao eliminar'
                ]);
            }
        }
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}