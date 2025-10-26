<?php
namespace App\Controllers;

use App\Models\User;
use App\Utils\Auth;
use App\Utils\Middleware;
use App\Utils\Config;
use App\Utils\Validator;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        Middleware::guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST, RegisterRequest::rules(), RegisterRequest::messages());
            
            if ($validator->fails()) {
                $errors = $validator->getErrors();
                return $this->showRegisterForm($errors, $_POST);
            }

            // Verificar si el email ya existe
            $existingUser = $this->userModel->findByEmail($_POST['email']);
            if ($existingUser) {
                $errors = ['email' => 'O e-mail já está registrado'];
                return $this->showRegisterForm($errors, $_POST);
            }

            // Crear usuario
            if ($this->userModel->create($_POST['name'], $_POST['email'], $_POST['password'])) {
                // Login automático después de registrarse
                $user = $this->userModel->findByEmail($_POST['email']);
                Auth::login($user);

                // Redirigimos al listado de tareas
                header('Location: ' . Config::baseUrl('tasks.php'));
                exit;
            } else {
                $errors = ['general' => 'Erro ao criar a conta'];
                return $this->showRegisterForm($errors, $_POST);
            }
        } else {
            $this->showRegisterForm();
        }
    }

    public function login() {
        //para que no se pueda loguear si ya esta autenticado
        Middleware::guest();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST, LoginRequest::rules(), LoginRequest::messages());
            
            if ($validator->fails()) {
                $errors = $validator->getErrors();
                return $this->showLoginForm($errors, $_POST);
            }

            //buscar usuario
            $user = $this->userModel->findByEmail($_POST['email']);
            if (!$user) {
                $errors = ['email' => 'Credenciales incorrectas'];
                return $this->showLoginForm($errors, $_POST);
            }

            //verificar contrasenna
            if (!$this->userModel->verifyPassword($_POST['password'], $user['senha'])) {
                $errors = ['password' => 'Credenciales incorrectas'];
                return $this->showLoginForm($errors, $_POST);
            }

            //login correcto
            Auth::login($user);
            header('Location: ' . Config::baseUrl('tasks.php'));
            exit;
        } else {
            $this->showLoginForm();
        }
    }
    
    public function logout() {
        Auth::logout();
        header('Location: ' . Config::baseUrl('login.php'));
        exit;
    }

    //renderiza el login
    private function showLoginForm($errors = [], $data = []) {
        $pageTitle = "Login - Sistema de Tarefas";
        
        // Pasar variables a la vista
        $viewData = [
            'errors' => $errors,
            'data' => $data,
            'pageTitle' => $pageTitle
        ];
        
        extract($viewData);
        require_once __DIR__ . '/../views/auth/login.php';
    }

    //renderiza el registro
    private function showRegisterForm($errors = [], $data = []) {
        $pageTitle = "Cadastro - Sistema de Tarefas";
        
        $viewData = [
            'errors' => $errors,
            'formData' => $data,
            'pageTitle' => $pageTitle
        ];
        
        extract($viewData);
        require_once __DIR__ . '/../views/auth/register.php';
    }
}
