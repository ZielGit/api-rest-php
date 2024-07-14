<?php

namespace Config;

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database
{
    public static function getEntityManager()
    {
        $paths = [__DIR__ . '/../app/Entity']; // Ajusta la ruta a tu carpeta de entidades
        $isDevMode = true;

        // Configuración de conexión a la base de datos
        $dbParams = [
            'driver'   => 'pdo_mysql',
            'host'     => $_ENV['DB_HOST'],
            'user'     => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname'   => $_ENV['DB_DATABASE'],
        ];

        // Crear configuración de metadatos para anotaciones
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

        // Configurar la conexión de la base de datos
        $connection = DriverManager::getConnection($dbParams, $config);

        // Obtener el EntityManager
        return new EntityManager($connection, $config);
    }
}