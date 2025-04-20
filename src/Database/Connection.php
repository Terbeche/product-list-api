<?php

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;
use App\Config\Config;

class Connection
{
    private static ?PDO $connection = null;

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            Config::init();

            // Determine environment (Heroku or local)
            $isHeroku = getenv('DYNO') !== false; // Heroku sets 'DYNO' in env

            $host = $isHeroku ? getenv('DB_HOST') : $_ENV['DB_HOST'];
            $dbname = $isHeroku ? getenv('DB_NAME') : $_ENV['DB_NAME'];
            $username = $isHeroku ? getenv('DB_USER') : $_ENV['DB_USER'];
            $password = $isHeroku ? getenv('DB_PASSWORD') : $_ENV['DB_PASSWORD'];

            try {
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