<?php
namespace App\Utils;

use App\Utils\Config;

class Middleware {

    //verifica que el usuario este autenticado
    public static function auth() {
        Auth::startSession();
        //si no esta logueado, lo redirigimos al login
        if (!Auth::isLoggedIn()) {
            header('Location: ' . Config::baseUrl('login.php'));
            exit;
        }
    }

    //verifica que el usuario sea invitado
    public static function guest() {
        Auth::startSession();
        //si ya está logueado, lo redirigimos al panel de tareas
        if (Auth::isLoggedIn()) {
            header('Location: ' . Config::baseUrl('tasks.php'));
            exit;
        }
    }
}
