<?php
namespace App\Treinador;

class TreinadorController
{
    private TreinadorService $service;

    public function __construct(TreinadorService $service)
    {
        $this->service = $service;
    }

    public function store(array $data): int
    {
        // Log para debug
        error_log('Dados recebidos no store treinador: ' . json_encode($data));
        
        // Garantir que todos os campos necessários estejam presentes
        $treinadorData = [
            'nome' => $data['nome'] ?? '',
            'email' => $data['email'] ?? '',
            'telefone' => $data['telefone'] ?? '',
            'especialidade' => $data['especialidade'] ?? '',
            'data_contratacao' => $data['data_contratacao'] ?? '',
            'observacoes' => $data['observacoes'] ?? '',
            'nivel' => $data['nivel'] ?? 'treinador',
            'senha' => $data['senha'] ?? '',
            'ativo' => $data['ativo'] ?? true
        ];
        
        return $this->service->cadastrar($treinadorData);
    }

    public function cadastrar(array $request): void
    {
        $this->service->cadastrar($request);
        echo json_encode(['status' => 'ok']);
    }

    public function listar(): array {
        $treinadores = $this->service->listar();
        return array_map([$this, 'toArray'], $treinadores);
    }

    public function editar(array $data): bool
    {
        return $this->service->editar($data);
    }

    public function excluir(int $id): bool
    {
        return $this->service->excluir($id);
    }

    public function obter(int $id): ?array
    {
        $treinador = $this->service->obterTreinador($id);
        return $treinador ? $this->toArray($treinador) : null;
    }

    private function toArray(Treinador $treinador): array
    {
        return [
            'id' => $treinador->getId(),
            'nome' => $treinador->getNome(),
            'email' => $treinador->getEmail(),
            'telefone' => $treinador->getTelefone(),
            'especialidade' => $treinador->getEspecialidade(),
            'data_contratacao' => $treinador->getDataContratacao(),
            'observacoes' => $treinador->getObservacoes(),
            'usuario_id' => $treinador->getUsuarioId(),
            'nivel' => $treinador->getNivel(),
            'ativo' => $treinador->getAtivo()
        ];
    }
} 