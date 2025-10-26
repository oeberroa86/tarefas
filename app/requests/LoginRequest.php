<?php
namespace App\Requests;

class LoginRequest {
    public static function rules() {
        return [
            'email' => 'required|email|max:150',
            'password' => 'required|min:6'
        ];
    }

    public static function messages() {
        return [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O formato do e-mail não é válido',
            'email.max' => 'O e-mail não pode ter mais de 150 caracteres',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
        ];
    }
}