<?php

namespace App\Config;

class Database
{
    public static function loadEnv(): void
    {
        Config::init();
    }
}