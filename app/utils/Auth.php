<?php
namespace App\Utils;

class Auth {

    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user) {
        self::startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['logged_in'] = true;
    }

    public static function logout() {
        self::startSession(); 
        session_destroy(); 
    }

    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function getUserId() {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUser() {
        self::startSession();
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'nome' => $_SESSION['user_name'] ?? null,
            'email' => $_SESSION['user_email'] ?? null
        ];
    }
}
