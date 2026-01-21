<?php

namespace App;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (!self::$instance instanceof \PDO) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=shop;charset=utf8mb4',
                    'dev',
                    'dev'
                );

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new RuntimeException('Database connection failed', 0, $e);
            }
        }

        return self::$instance;
    }
}
