<?php
// Carga automática de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;

// Instancia del controlador de autenticación
$authController = new AuthController();

// Llama al método logout, que:
// - Cierra la sesión
// - Redirige al login
$authController->logout();
