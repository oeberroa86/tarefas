<?php
namespace App\Utils;

class Config {
    private static $configs = [];

    //obtiene un valor de configuración usando notación "archivo.clave.subclave"
    public static function get($key, $default = null) {
        $keys = explode('.', $key); //separa por puntos para obtener archivo y subclaves
        $file = $keys[0];
        
        //si no ha cargado el archivo lo incluimos
        if (!isset(self::$configs[$file])) {
            $configPath = __DIR__ . "/../config/{$file}.php";
            if (file_exists($configPath)) {
                self::$configs[$file] = require $configPath;
            } else {
                return $default;
            }
        }
        
        $config = self::$configs[$file];
        for ($i = 1; $i < count($keys); $i++) {
            if (isset($config[$keys[$i]])) {
                $config = $config[$keys[$i]];
            } else {
                return $default;
            }
        }
        
        return $config;
    }
    
    public static function baseUrl($path = '') {
        $baseUrl = self::get('system.base_url', ''); //obtenemos la base_url desde config
        $path = ltrim($path, '/'); //eliminamos la barra inicial si existe
        return $baseUrl . ($path ? "/{$path}" : '');
    }
}
