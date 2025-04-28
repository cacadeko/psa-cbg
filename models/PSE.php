<?php
namespace Models;

use Config\Database;
use PDO;
use PDOException;

class PSE {
    public static function create($atleta_id, $descricao, $avaliacao_pse) {
        $conn = \Config\Database::getConnection(); // <-- aqui com barra invertida
        $stmt = $conn->prepare("INSERT INTO pse (atleta_id, descricao, nota_pse, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$atleta_id, $descricao, $avaliacao_pse]);
    }

    public static function listarTodos($data = null) {
        $conn = \Config\Database::getConnection(); // <-- aqui também
        if ($data) {
            $stmt = $conn->prepare("SELECT pse.*, a.nome AS atleta_nome FROM pse pse INNER JOIN atletas a ON pse.atleta_id = a.id WHERE DATE(pse.created_at) = ?");
            $stmt->execute([$data]);
        } else {
            $stmt = $conn->query("SELECT pse.*, a.nome AS atleta_nome FROM pse pse INNER JOIN atletas a ON pse.atleta_id = a.id");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorTreinador($treinadorId)
    {
        $conn = \Config\Database::getConnection(); // já estava certo aqui
        $stmt = $conn->prepare("
            SELECT pse.*, a.nome AS atleta_nome
            FROM pse pse
            INNER JOIN atletas a ON pse.atleta_id = a.id
            WHERE a.treinador_id = ?
            ORDER BY pse.created_at DESC
        ");
        $stmt->execute([$treinadorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function excluir($id) {
        $conn = \Config\Database::getConnection(); // <-- e aqui
        $stmt = $conn->prepare("DELETE FROM pse WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
