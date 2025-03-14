<?php

namespace App\Config;

use Dotenv\Dotenv;
use RuntimeException;

class Config
{
    public static function init()
    {
        $rootDir = dirname(__DIR__, 2);

        if (!file_exists($rootDir . '/.env')) {
            return;
        }

        $dotenv = Dotenv::createImmutable($rootDir);
        $dotenv->load();

        // Ensure required env variables are set
        $requiredVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
        foreach ($requiredVars as $var) {
            if (!getenv($var) && empty($_ENV[$var])) {
                throw new RuntimeException("Missing environment variable: $var");
            }
        }
    }
}
