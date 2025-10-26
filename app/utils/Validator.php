<?php
namespace App\Utils;

class Validator {
    private $data;
    private $rules;
    private $messages;
    private $errors = [];
    private $customMessages = [];

    public function __construct($data, $rules, $messages = []) {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $messages;
    }

    public function validate() {
        $this->errors = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
                
                //si ya hay error en este campo pasar al siguiente
                if (isset($this->errors[$field])) {
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        $params = [];

        //verificar si la regla tiene parametros (ej: min:6)
        if (strpos($rule, ':') !== false) {
            list($rule, $param) = explode(':', $rule, 2);
            $params = explode(',', $param);
        }

        $methodName = 'validate' . str_replace(' ', '', ucwords(str_replace('_', ' ', $rule)));
        
        if (method_exists($this, $methodName)) {
            if (!$this->$methodName($field, $value, $params)) {
                $this->addError($field, $rule, $params);
            }
        }
    }

    private function addError($field, $rule, $params = []) {
        $messageKey = "{$field}.{$rule}";
        
        //mensaje personalizado
        if (isset($this->customMessages[$messageKey])) {
            $this->errors[$field] = $this->customMessages[$messageKey];
            return;
        }

        //mensajes por defecto en portugues
        $defaultMessages = [
            'required' => 'O campo :field é obrigatório',
            'email' => 'O campo :field deve ser um email válido',
            'min' => 'O campo :field deve ter pelo menos :min caracteres',
            'max' => 'O campo :field não pode ter mais de :max caracteres',
            'confirmed' => 'A confirmação de :field não coincide'
        ];

        $message = $defaultMessages[$rule] ?? 'O campo :field não é válido';
        $message = str_replace(':field', $this->getFieldName($field), $message);
        
        foreach ($params as $key => $param) {
            $message = str_replace(":{$key}", $param, $message);
        }

        $this->errors[$field] = $message;
    }

    private function getFieldName($field) {
        $fieldNames = [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'title' => 'título',
            'description' => 'descrição'
        ];

        return $fieldNames[$field] ?? $field;
    }

    //reglas
    private function validateRequired($field, $value, $params) {
        return !empty(trim($value ?? ''));
    }

    private function validateEmail($field, $value, $params) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateMin($field, $value, $params) {
        $min = (int) $params[0];
        return strlen(trim($value ?? '')) >= $min;
    }

    private function validateMax($field, $value, $params) {
        $max = (int) $params[0];
        return strlen(trim($value ?? '')) <= $max;
    }

    private function validateConfirmed($field, $value, $params) {
        $confirmationField = $field . '_confirmation';
        return isset($this->data[$confirmationField]) && $value === $this->data[$confirmationField];
    }

    //para uso publico
    public function fails() {
        return !$this->validate();
    }

    public function passes() {
        return $this->validate();
    }

    public function getErrors() {
        $this->validate();
        return $this->errors;
    }

    public function getFirstError() {
        $this->validate();
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}