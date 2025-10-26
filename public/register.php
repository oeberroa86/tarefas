<?php
// Carga automática de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;

// Instancia del controlador de autenticación
$authController = new AuthController();

// Llama al método register, que maneja:
// - Mostrar el formulario de registro
// - Procesar POST con nombre, email, contraseña y confirmación
$authController->register();
