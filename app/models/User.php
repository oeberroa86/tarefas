<?php
namespace App\Models;

use App\Utils\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $query = $this->db->prepare($sql);
        
        return $query->execute([
            ':nome' => $name,
            ':email' => $email,
            ':senha' => $hashedPassword
        ]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->execute([':email' => $email]);
        
        return $query->fetch();
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}
