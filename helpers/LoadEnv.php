<?php

namespace Helpers;

// LoadEnv is helper para cargar el archivo .env
// y cargar las variables de entorno en el sistema
// más información de como se realizó en https://www.sourcecodester.com/tutorial/php/16035/load-environment-variables-env-file-using-php-tutorial
class LoadEnv
{
    const ENV_FILE = __DIR__ . '/../.env';

    /**
     * LoadEnv constructor.
     *
     * @param string $envFile
     *
     * @return void
     *
     * @throws \Exception
     */
    public function __construct(string $envFile = "")
    {
        self::loadEnv($envFile);
    }

    /**
     * loadEnv carga las variables de entorno
     * Se verifica si el archivo .env existe
     * Si existe, entonces se carga el contenido
     * si no, entonces se lanza una excepción
     *
     * @param string $envFile
     *
     * @return void
     *
     * @throws \Exception
     */
    public static function loadEnv(string $envFile = self::ENV_FILE): void
    {
        $path = !empty($envFile) ? $envFile : self::ENV_FILE;
        // Verificar si el archivo .env existe
        if (!file_exists($path)) {
            throw new \Exception('El archivo .env no existe');
        }
        self::loadContent($path);
    }

    /**
     * loadContent carga el contenido del archivo .env
     * y lo separa en líneas, para después poder usarlos con
     * la función getenv() o $_ENV
     *
     * @example getenv('DB_HOST') or $_ENV['DB_HOST']
     *
     * @see https://www.php.net/manual/en/function.getenv.php
     *
     * @param string $envFile
     *
     * @return void
     */
    private static function loadContent(string $envFile): void
    {
//        ServerLogger::log("Cargando variables de entorno" . PHP_EOL . "Archivo: $envFile");
        // Leer el contenido del archivo .env
        $env = file_get_contents($envFile);
        // Separar las líneas del archivo
        $env = explode("\n", $env);
        foreach ($env as $line) {
            // Si la línea es un comentario, entonces saltar
            $line_is_comment = str_starts_with(trim($line), '#');
            if($line_is_comment || empty(trim($line)))
                continue;
            // Separar la línea en dos partes: nombre de la variable y valor
            if (str_contains($line, '=')) {
                [$name, $value] = explode('=', $line, 2);
                // Eliminar espacios en blanco al inicio y final de la línea
                $name = trim($name);
                $value = trim($value);
                // Si el valor está entre comillas, entonces eliminarlas
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    // Eliminar las comillas
                    $value = substr($value, 1, -1);
                }
                // Definir la variable de entorno
                putenv("$name=$value");
            }
        }
    }
}