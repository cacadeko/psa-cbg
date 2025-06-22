<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static $host = 'localhost';
    private static $dbname = 'psa_cbg';
    private static $username = 'root'; // Alterar se necessário
    private static $password = ''; // Alterar se necessário
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
