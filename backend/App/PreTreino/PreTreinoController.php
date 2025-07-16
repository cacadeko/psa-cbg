<?php

namespace App\PreTreino;

use App\PreTreino\PreTreinoService;

class PreTreinoController {
    private PreTreinoService $service;
    
    public function __construct(PreTreinoService $service) {
        $this->service = $service;
    }

    public function store(array $data): int {
        // Log para debug
        error_log('Dados recebidos no store: ' . json_encode($data));
        
        // Garantir que todos os campos necessários estejam presentes
        $preTreinoData = [
            'atleta_id' => $data['atleta_id'] ?? 0,
            'data_avaliacao' => $data['data_avaliacao'] ?? date('Y-m-d'),
            'tqr_recuperacao' => $data['tqr_recuperacao'] ?? 10,
            'fadiga_bem_estar' => $data['fadiga_bem_estar'] ?? 3,
            'nivel_fadiga' => $data['nivel_fadiga'] ?? 5,
            'qualidade_sono' => $data['qualidade_sono'] ?? 3,
            'horas_sono' => $data['horas_sono'] ?? 'Entre 6 e 7 horas',
            'tempo_dormir' => $data['tempo_dormir'] ?? 'Menos que 30 min',
            'motivo_sono_inquieto' => $data['motivo_sono_inquieto'] ?? null,
            'vezes_acordou_noite' => $data['vezes_acordou_noite'] ?? '1 vez',
            'dor_muscular_geral' => $data['dor_muscular_geral'] ?? 3,
            'escala_dor_visual' => $data['escala_dor_visual'] ?? 5,
            'local_dor_especifica' => $data['local_dor_especifica'] ?? null,
            'tipo_dor_muscular' => $data['tipo_dor_muscular'] ?? null,
            'nivel_estresse' => $data['nivel_estresse'] ?? 3,
            'humor' => $data['humor'] ?? 3,
            'periodo_pre_menstrual' => $data['periodo_pre_menstrual'] ?? false,
            'periodo_menstrual' => $data['periodo_menstrual'] ?? false,
            'medicacao_uso' => $data['medicacao_uso'] ?? null,
            'motivo_medicacao' => $data['motivo_medicacao'] ?? null,
            'observacoes' => $data['observacoes'] ?? null
        ];
        
        return $this->service->cadastrar($preTreinoData);
    }

    public function cadastrar(array $request): void {
        $this->service->cadastrar($request);
        echo json_encode(['status' => 'ok']);
    }

    public function listar(): array {
        return $this->service->listar();
    }

    public function listarPorAtleta(int $atletaId): array {
        return $this->service->listarPorAtleta($atletaId);
    }

    public function editar(array $data): bool {
        return $this->service->editar($data);
    }

    public function excluir(int $id): bool {
        return $this->service->excluir($id);
    }
} 