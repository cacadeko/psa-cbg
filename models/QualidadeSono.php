<?php

namespace Models;

use Config\Database;
use PDO;
use PDOException; // <-- Adicione essa linha aqui

    class QualidadeSono {
        public static function create($atleta_id, $hora_dormir, $hora_acordar, $dor_corpo, $local_dor, $intensidade_dor, $avaliacao_psr, $avaliacao_sono, $acordou_durante_a_noite) {
            try {
                $conn = Database::getConnection();
                $stmt = $conn->prepare("INSERT INTO qualidade_sono 
                    (atleta_id, hora_dormir, hora_acordar, dor_corpo, local_dor, intensidade_dor, avaliacao_psr, avaliacao_sono, acordou_durante_a_noite, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                
                return $stmt->execute([$atleta_id, $hora_dormir, $hora_acordar, $dor_corpo, $local_dor, $intensidade_dor, $avaliacao_psr, $avaliacao_sono, $acordou_durante_a_noite]);
    
            } catch (PDOException $e) {
                die("Erro ao inserir dados: " . $e->getMessage());
            }
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