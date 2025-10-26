<?php
namespace App\Requests;

class TaskRequest {
    public static function rules() {
        return [
            'title' => 'required|min:5|max:200',
            'description' => 'max:1000'
        ];
    }

    public static function messages() {
        return [
            'title.required' => 'O título é obrigatório',
            'title.min' => 'O título deve ter pelo menos 5 caracteres',
            'title.max' => 'O título não pode ter mais de 200 caracteres',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres'
        ];
    }
}