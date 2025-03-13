<?php

namespace App\Config;

define('DEBUG_MODE', false);

class Config
{
    public static function init()
    {
        // Load environment variables if not already loaded
        if (!isset($_ENV['DB_HOST'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }
    }
}
