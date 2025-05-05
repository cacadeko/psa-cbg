<?php
namespace Models;
require_once __DIR__ . '/../config/Database.php';
use Config\Database;
use PDO;

class PsaTreino
{
    public static function create(array $data): bool
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO psa_treino (atleta_id, grupo, nota_pse, tempo_treino, turno)
                VALUES (:atleta_id, :grupo, :nota_pse, :tempo_treino, :turno)";
        return $pdo->prepare($sql)->execute($data);
    }

    public static function listarTodos(?string $data = null): array
    {
        $pdo = Database::getConnection();
        if ($data) {
            $stmt = $pdo->prepare("SELECT p.*, a.nome AS atleta_nome
                                   FROM psa_treino p
                                   JOIN atletas a ON a.id = p.atleta_id
                                   WHERE DATE(p.created_at) = ?
                                   ORDER BY p.created_at DESC");
            $stmt->execute([$data]);
        } else {
            $stmt = $pdo->query("SELECT p.*, a.nome AS atleta_nome
                                 FROM psa_treino p
                                 JOIN atletas a ON a.id = p.atleta_id
                                 ORDER BY p.created_at DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
