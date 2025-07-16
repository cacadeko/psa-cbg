<?php

namespace App\PreTreino;

use Backend\Config\Database;
use PDO;

class PreTreinoModel {
    public static function create(
        int $atleta_id,
        string $data_avaliacao,
        int $tqr_recuperacao,
        int $fadiga_bem_estar,
        int $nivel_fadiga,
        int $qualidade_sono,
        string $horas_sono,
        string $tempo_dormir,
        ?string $motivo_sono_inquieto,
        string $vezes_acordou_noite,
        int $dor_muscular_geral,
        int $escala_dor_visual,
        ?string $local_dor_especifica,
        ?string $tipo_dor_muscular,
        int $nivel_estresse,
        int $humor,
        bool $periodo_pre_menstrual,
        bool $periodo_menstrual,
        ?string $medicacao_uso,
        ?string $motivo_medicacao,
        ?string $observacoes
    ): int|false {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO pre_treino (
                    atleta_id, data_avaliacao, tqr_recuperacao, fadiga_bem_estar, 
                    nivel_fadiga, qualidade_sono, horas_sono, tempo_dormir, 
                    motivo_sono_inquieto, vezes_acordou_noite, dor_muscular_geral, 
                    escala_dor_visual, local_dor_especifica, tipo_dor_muscular, 
                    nivel_estresse, humor, periodo_pre_menstrual, periodo_menstrual, 
                    medicacao_uso, motivo_medicacao, observacoes, created_at, updated_at
                ) VALUES (
                    :atleta_id, :data_avaliacao, :tqr_recuperacao, :fadiga_bem_estar,
                    :nivel_fadiga, :qualidade_sono, :horas_sono, :tempo_dormir,
                    :motivo_sono_inquieto, :vezes_acordou_noite, :dor_muscular_geral,
                    :escala_dor_visual, :local_dor_especifica, :tipo_dor_muscular,
                    :nivel_estresse, :humor, :periodo_pre_menstrual, :periodo_menstrual,
                    :medicacao_uso, :motivo_medicacao, :observacoes, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                )";
        
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':atleta_id' => $atleta_id,
            ':data_avaliacao' => $data_avaliacao,
            ':tqr_recuperacao' => $tqr_recuperacao,
            ':fadiga_bem_estar' => $fadiga_bem_estar,
            ':nivel_fadiga' => $nivel_fadiga,
            ':qualidade_sono' => $qualidade_sono,
            ':horas_sono' => $horas_sono,
            ':tempo_dormir' => $tempo_dormir,
            ':motivo_sono_inquieto' => $motivo_sono_inquieto,
            ':vezes_acordou_noite' => $vezes_acordou_noite,
            ':dor_muscular_geral' => $dor_muscular_geral,
            ':escala_dor_visual' => $escala_dor_visual,
            ':local_dor_especifica' => $local_dor_especifica,
            ':tipo_dor_muscular' => $tipo_dor_muscular,
            ':nivel_estresse' => $nivel_estresse,
            ':humor' => $humor,
            ':periodo_pre_menstrual' => $periodo_pre_menstrual ? 1 : 0,
            ':periodo_menstrual' => $periodo_menstrual ? 1 : 0,
            ':medicacao_uso' => $medicacao_uso,
            ':motivo_medicacao' => $motivo_medicacao,
            ':observacoes' => $observacoes
        ]);
        
        return $ok ? (int)$pdo->lastInsertId() : false;
    }

    public static function listarTodos() {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM pre_treino ORDER BY data_avaliacao DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erro ao buscar pré-treinos: " . $e->getMessage());
        }
    }

    public static function listarPorAtleta(int $atletaId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT * FROM pre_treino
             WHERE atleta_id = :atleta_id
             ORDER BY data_avaliacao DESC"
        );
        $stmt->execute([':atleta_id' => $atletaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function excluir($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM pre_treino WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public static function editar($id, $data) {
        $conn = Database::getConnection();
        $sql = "UPDATE pre_treino SET 
                atleta_id = ?, data_avaliacao = ?, tqr_recuperacao = ?, 
                fadiga_bem_estar = ?, nivel_fadiga = ?, qualidade_sono = ?, 
                horas_sono = ?, tempo_dormir = ?, motivo_sono_inquieto = ?, 
                vezes_acordou_noite = ?, dor_muscular_geral = ?, escala_dor_visual = ?, 
                local_dor_especifica = ?, tipo_dor_muscular = ?, nivel_estresse = ?, 
                humor = ?, periodo_pre_menstrual = ?, periodo_menstrual = ?, 
                medicacao_uso = ?, motivo_medicacao = ?, observacoes = ?, 
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['atleta_id'], $data['data_avaliacao'], $data['tqr_recuperacao'],
            $data['fadiga_bem_estar'], $data['nivel_fadiga'], $data['qualidade_sono'],
            $data['horas_sono'], $data['tempo_dormir'], $data['motivo_sono_inquieto'],
            $data['vezes_acordou_noite'], $data['dor_muscular_geral'], $data['escala_dor_visual'],
            $data['local_dor_especifica'], $data['tipo_dor_muscular'], $data['nivel_estresse'],
            $data['humor'], $data['periodo_pre_menstrual'], $data['periodo_menstrual'],
            $data['medicacao_uso'], $data['motivo_medicacao'], $data['observacoes'], $id
        ]);
    }
} 