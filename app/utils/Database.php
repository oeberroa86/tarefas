<?php
namespace App\Utils;

class Database {
    private $connection;
    private static $instance = null; //instancia unica para singleton

    private function __construct() {
        //datos de conexion a db
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            $this->connection = new \PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // Devuelve arrays asociativos por defecto
                    \PDO::ATTR_EMULATE_PREPARES => false                 // Usa sentencias preparadas reales (mas seguro, evitar inyeccion SQL)
                ]
            );
        } catch (\PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error de conexión");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    //para seguridad evita que se pueda clonar la instancia
    private function __clone() {
        throw new \Exception("Não é permitido desserializar uma instância de Database (singleton).");
    }

    //para seguridad evita que se pueda deserializar la instancia
    public function __wakeup() {
        throw new \Exception("Não é permitido clonar a instância de Database (singleton).");
    }
}