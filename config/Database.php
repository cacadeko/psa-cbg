<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static $host = 'localhost';
    private static $dbname = 'u300069101_cbg';
    private static $username = 'u300069101_cbg'; // Alterar se necessário
    private static $password = ')1%XxE#7N.;N'; // Alterar se necessário
    private static $conn;

    public static function getConnection() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
