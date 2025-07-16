<?php

namespace Backend\Config;

use PDO;
use PDOException;

class Database {
    private static $connection = null;
    
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $database = $_ENV['DB_DATABASE'] ?? 'psa-cbg';
                $username = $_ENV['DB_USERNAME'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                
                $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";
                
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                
                error_log("Database connection established successfully");
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new PDOException("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
} 