<?php
// Carga automática de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;

// Instancia del controlador de autenticación
$authController = new AuthController();

// Llama al método login, que maneja:
// - Mostrar el formulario de login
// - Procesar POST con email y contraseña
$authController->login();
