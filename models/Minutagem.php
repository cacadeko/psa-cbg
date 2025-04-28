<?php
namespace Models;

require_once __DIR__ . '/../config/Database.php';
use Config\Database;
use PDO;

class Minutagem
{
    /** cria um registro de minutagem */
    public static function create(array $d): bool
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO minutagem
                (atleta_id, treinador_id, data_jogo, local_jogo,
                 titular, minuto_entrou, minuto_saiu,
                 minutos_primeiro_tempo, minutos_segundo_tempo, observacoes)
                VALUES
                (:atleta_id, :treinador_id, :data_jogo, :local_jogo,
                 :titular, :min_entrou, :min_saiu,
                 :min1, :min2, :obs)";
        return $pdo->prepare($sql)->execute($d);
    }

    /** lista todos (admin) */
    public static function listarTodos(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query(
            "SELECT m.*, a.nome AS atleta
             FROM minutagem m
             JOIN atletas a ON a.id = m.atleta_id
             ORDER BY m.created_at DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    /** lista apenas os registros de um treinador */
    public static function listarPorTreinador(int $treinadorId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT m.*, a.nome AS atleta
             FROM minutagem m
             JOIN atletas a ON a.id = m.atleta_id
             WHERE m.treinador_id = ?
             ORDER BY m.created_at DESC"
        );
        $stmt->execute([$treinadorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
