<?php

namespace App\Atleta;

use App\Atleta\AtletaService;

class AtletaController {
    private AtletaService $service;
    public function __construct(AtletaService $service) {
        $this->service = $service;
    }

    public function store(array $data, $treinadorId = null): int {
        // Log para debug
        error_log('Dados recebidos no store: ' . json_encode($data));
        
        // Garantir que todos os campos necessários estejam presentes
        $atletaData = [
            'nome' => $data['nome'] ?? '',
            'data_nascimento' => $data['data_nascimento'] ?? '',
            'posicao' => $data['posicao'] ?? '',
            'email' => $data['email'] ?? '',
            'telefone' => $data['telefone'] ?? '',
            'categoria' => $data['categoria'] ?? '',
            'senha' => $data['senha'] ?? '',
            'acesso' => $data['acesso'] ?? '',
            'treinador_id' => $data['treinador_id'] ?? ($treinadorId ? (int)$treinadorId : null),
            'usuario_id' => $data['usuario_id'] ?? 0
        ];
        
        return $this->service->cadastrar($atletaData);
    }

    public function cadastrar(array $request): void {
        $this->service->cadastrar($request);
        echo json_encode(['status' => 'ok']);
    }

    public function listar(): array {
        return $this->service->listar();
    }

    public function editar(array $data): bool {
        return $this->service->editar($data);
    }

    public function excluir(int $id): bool {
        return $this->service->excluir($id);
    }
} 