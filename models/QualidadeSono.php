<?php

namespace Models;

use Config\Database;
use PDO;
use PDOException; // <-- Adicione essa linha aqui

    class QualidadeSono {
        public static function create(
        int $atleta_id,
        string $avaliacao_recuperacao,
        string $avaliacao_fadiga,
        string $avaliacao_sono,
        string $avaliacao_cansaco,
        string $avaliacao_dor,
        string $intensidade_dor,
        string $local_dor,
        string $dor_corpo,
        string $estresse_geral,
        string $estresse_umor,
        string $periodo_menstrual,
        string $periodo_premenstrual,
        string $uso_medicacao,
        string $uso_medicacao_outro,
        string $motivo_medicacao,
        string $motivo_medicacao_outro
    ): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO qualidade_sono (
                atleta_id, avaliacao_recuperacao, avaliacao_fadiga, avaliacao_sono, avaliacao_cansaco, avaliacao_dor,
                intensidade_dor, local_dor, dor_corpo, estresse_geral, estresse_umor,
                periodo_menstrual, periodo_premenstrual, uso_medicacao, uso_medicacao_outro,
                motivo_medicacao, motivo_medicacao_outro
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        return $stmt->execute([
            $atleta_id,
            $avaliacao_recuperacao,
            $avaliacao_fadiga,
            $avaliacao_sono,
            $avaliacao_cansaco,
            $avaliacao_dor,
            $intensidade_dor,
            $local_dor,
            $dor_corpo,
            $estresse_geral,
            $estresse_umor,
            $periodo_menstrual,
            $periodo_premenstrual,
            $uso_medicacao,
            $uso_medicacao_outro,
            $motivo_medicacao,
            $motivo_medicacao_outro
        ]);
    }



        public static function listarTodos($data = null) {
            $conn = Database::getConnection();
            if ($data) {
                $stmt = $conn->prepare("SELECT qs.*, a.nome AS atleta_nome FROM qualidade_sono qs INNER JOIN atletas a ON qs.atleta_id = a.id WHERE DATE(qs.created_at) = ?");
                $stmt->execute([$data]);
            } else {
                $stmt = $conn->query("SELECT qs.*, a.nome AS atleta_nome FROM qualidade_sono qs INNER JOIN atletas a ON qs.atleta_id = a.id");
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarPorTreinador($treinadorId)
        {
            $conn = \Config\Database::getConnection();
            $stmt = $conn->prepare("
                SELECT qs.*, a.nome AS atleta_nome
                FROM qualidade_sono qs
                INNER JOIN atletas a ON qs.atleta_id = a.id
                WHERE a.treinador_id = ?
                ORDER BY qs.created_at DESC
            ");
            $stmt->execute([$treinadorId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }        
        
        public static function excluir($id) {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM qualidade_sono WHERE id = ?");
            return $stmt->execute([$id]);
        }
    
    }