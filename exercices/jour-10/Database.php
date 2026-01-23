<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO('mysql:host=localhost;dbname=shop;charset=utf8mb4', 'dev', 'dev');
        }
        return self::$instance;
    }
}
