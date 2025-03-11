<?php

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;
use Dotenv\Dotenv;

class Connection
{
    private static ?PDO $connection = null;
    
    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            $rootDir = dirname(__DIR__, 2);
            $autoloadPath = $rootDir . '/vendor/autoload.php';
            if (!file_exists($autoloadPath)) {
                throw new RuntimeException('Autoload file not found at: ' . $autoloadPath);
            }
            require $autoloadPath;

            if (!class_exists('Dotenv\Dotenv')) {
                throw new RuntimeException('Dotenv class not found. Ensure vlucas/phpdotenv is installed via Composer.');
            }

            $dotenv = Dotenv::createImmutable($rootDir);
            $dotenv->load();

            try {
                $requiredVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
                foreach ($requiredVars as $var) {
                    if (empty($_ENV[$var])) {
                        throw new RuntimeException("Environment variable $var is not set.");
                    }
                }

                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_NAME'];
                $username = $_ENV['DB_USER'];
                $password = $_ENV['DB_PASSWORD'];
                
                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                
                self::$connection = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new RuntimeException('Database connection error: ' . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}