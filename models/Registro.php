<?php
namespace Models;

use Config\Database;
use PDO;

class Registro {
    public static function alterarStatusRegistro($status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE configuracoes SET registro_liberado = ? WHERE id = 1");
        return $stmt->execute([$status]);
    }
    
    public static function obterStatusRegistro() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT registro_liberado FROM configuracoes WHERE id = 1");
        return $stmt->fetch(PDO::FETCH_ASSOC)['registro_liberado'];
    }

    public static function listarTodos($data = null)
    {
        $conn = \Config\Database::getConnection();
        $stmt = $conn->prepare("
            SELECT r.*, a.nome AS atleta_nome
            FROM registro r
            INNER JOIN atletas a ON r.atleta_id = a.id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function listarPorTreinador($treinadorId)
    {
        $conn = \Config\Database::getConnection();
        $stmt = $conn->prepare("
            SELECT r.*, a.nome AS atleta_nome
            FROM registro r
            INNER JOIN atletas a ON r.atleta_id = a.id
            WHERE a.treinador_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$treinadorId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
?>
