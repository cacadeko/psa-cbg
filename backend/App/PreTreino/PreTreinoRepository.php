<?php
namespace App\PreTreino;

use PDO;

interface PreTreinoRepositoryInterface {
    public function findById(int $id): ?array;
    public function save(array $data): int;
    public function listarTodos(): array;
    public function editar(array $data): bool;
    public function excluir(int $id): bool;
}

class PreTreinoRepository implements PreTreinoRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM pre_treino WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function save(array $data): int {
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
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':atleta_id' => $data['atleta_id'],
            ':data_avaliacao' => $data['data_avaliacao'],
            ':tqr_recuperacao' => $data['tqr_recuperacao'],
            ':fadiga_bem_estar' => $data['fadiga_bem_estar'],
            ':nivel_fadiga' => $data['nivel_fadiga'],
            ':qualidade_sono' => $data['qualidade_sono'],
            ':horas_sono' => $data['horas_sono'],
            ':tempo_dormir' => $data['tempo_dormir'],
            ':motivo_sono_inquieto' => $data['motivo_sono_inquieto'] ?? null,
            ':vezes_acordou_noite' => $data['vezes_acordou_noite'],
            ':dor_muscular_geral' => $data['dor_muscular_geral'],
            ':escala_dor_visual' => $data['escala_dor_visual'],
            ':local_dor_especifica' => $data['local_dor_especifica'] ?? null,
            ':tipo_dor_muscular' => $data['tipo_dor_muscular'] ?? null,
            ':nivel_estresse' => $data['nivel_estresse'],
            ':humor' => $data['humor'],
            ':periodo_pre_menstrual' => $data['periodo_pre_menstrual'] ? 1 : 0,
            ':periodo_menstrual' => $data['periodo_menstrual'] ? 1 : 0,
            ':medicacao_uso' => $data['medicacao_uso'] ?? null,
            ':motivo_medicacao' => $data['motivo_medicacao'] ?? null,
            ':observacoes' => $data['observacoes'] ?? null
        ]);
        
        return (int) $this->pdo->lastInsertId();
    }

    public function listarTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM pre_treino ORDER BY data_avaliacao DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editar(array $data): bool {
        $sql = "UPDATE pre_treino SET 
                atleta_id = :atleta_id, data_avaliacao = :data_avaliacao, 
                tqr_recuperacao = :tqr_recuperacao, fadiga_bem_estar = :fadiga_bem_estar,
                nivel_fadiga = :nivel_fadiga, qualidade_sono = :qualidade_sono,
                horas_sono = :horas_sono, tempo_dormir = :tempo_dormir,
                motivo_sono_inquieto = :motivo_sono_inquieto, vezes_acordou_noite = :vezes_acordou_noite,
                dor_muscular_geral = :dor_muscular_geral, escala_dor_visual = :escala_dor_visual,
                local_dor_especifica = :local_dor_especifica, tipo_dor_muscular = :tipo_dor_muscular,
                nivel_estresse = :nivel_estresse, humor = :humor,
                periodo_pre_menstrual = :periodo_pre_menstrual, periodo_menstrual = :periodo_menstrual,
                medicacao_uso = :medicacao_uso, motivo_medicacao = :motivo_medicacao,
                observacoes = :observacoes, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':atleta_id' => $data['atleta_id'],
            ':data_avaliacao' => $data['data_avaliacao'],
            ':tqr_recuperacao' => $data['tqr_recuperacao'],
            ':fadiga_bem_estar' => $data['fadiga_bem_estar'],
            ':nivel_fadiga' => $data['nivel_fadiga'],
            ':qualidade_sono' => $data['qualidade_sono'],
            ':horas_sono' => $data['horas_sono'],
            ':tempo_dormir' => $data['tempo_dormir'],
            ':motivo_sono_inquieto' => $data['motivo_sono_inquieto'] ?? null,
            ':vezes_acordou_noite' => $data['vezes_acordou_noite'],
            ':dor_muscular_geral' => $data['dor_muscular_geral'],
            ':escala_dor_visual' => $data['escala_dor_visual'],
            ':local_dor_especifica' => $data['local_dor_especifica'] ?? null,
            ':tipo_dor_muscular' => $data['tipo_dor_muscular'] ?? null,
            ':nivel_estresse' => $data['nivel_estresse'],
            ':humor' => $data['humor'],
            ':periodo_pre_menstrual' => $data['periodo_pre_menstrual'] ? 1 : 0,
            ':periodo_menstrual' => $data['periodo_menstrual'] ? 1 : 0,
            ':medicacao_uso' => $data['medicacao_uso'] ?? null,
            ':motivo_medicacao' => $data['motivo_medicacao'] ?? null,
            ':observacoes' => $data['observacoes'] ?? null,
            ':id' => $data['id']
        ]);
    }

    public function excluir(int $id): bool {
        $sql = "DELETE FROM pre_treino WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
} 