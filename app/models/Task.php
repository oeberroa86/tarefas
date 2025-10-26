<?php
namespace App\Models;

use App\Utils\Database;

class Task {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUser($userId, $filter = 'all') {
        $sql = "SELECT * FROM tarefas WHERE usuario_id = :user_id";
        
        switch($filter) {
            case 'pendentes':
                $sql .= " AND status = 'pendente'";
                break;
            case 'concluidas':
                $sql .= " AND status = 'concluida'";
                break;
        }
        
        $sql .= " ORDER BY data_criacao DESC";
        
        $query = $this->db->prepare($sql);
        $query->execute([':user_id' => $userId]);
        
        return $query->fetchAll();
    }

    public function create($userId, $title, $description) {
        $sql = "INSERT INTO tarefas (usuario_id, titulo, descricao) VALUES (:user_id, :titulo, :descricao)";
        $query = $this->db->prepare($sql);
        
        return $query->execute([
            ':user_id' => $userId,
            ':titulo' => $title,
            ':descricao' => $description
        ]);
    }

    public function updateStatus($taskId, $userId, $status) {
        $sql = "UPDATE tarefas SET status = :status, data_conclusao = NOW() WHERE id = :id AND usuario_id = :user_id";
        $query = $this->db->prepare($sql);
        
        return $query->execute([
            ':status' => $status,
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    public function update($taskId, $userId, $title, $description) {
        $sql = "UPDATE tarefas SET titulo = :titulo, descricao = :descricao WHERE id = :id AND usuario_id = :user_id";
        $query = $this->db->prepare($sql);
        
        return $query->execute([
            ':titulo' => $title,
            ':descricao' => $description,
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    public function delete($taskId, $userId) {
        $sql = "DELETE FROM tarefas WHERE id = :id AND usuario_id = :user_id";
        $query = $this->db->prepare($sql);
        
        return $query->execute([
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    public function findById($taskId, $userId) {
        $sql = "SELECT * FROM tarefas WHERE id = :id AND usuario_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
        
        return $query->fetch();
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
