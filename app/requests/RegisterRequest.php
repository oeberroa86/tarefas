<?php
namespace App\Requests;

class RegisterRequest {
    public static function rules() {
        return [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:150',
            'password' => 'required|min:6|confirmed'
        ];
    }

    public static function messages() {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.min' => 'O nome deve ter pelo menos 2 caracteres',
            'name.max' => 'O nome não pode ter mais de 100 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O formato do e-mail não é válido',
            'email.max' => 'O e-mail não pode ter mais de 150 caracteres',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'As senhas não coincidem'
        ];
    }
}