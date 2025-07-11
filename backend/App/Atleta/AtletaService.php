<?php
namespace App\Atleta;

class AtletaService {
    private AtletaRepositoryInterface $repo;
    public function __construct(AtletaRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function cadastrar(array $data): void {
        // Exemplo mínimo
        $atleta = new Atleta(
            $data['nome'],
            $data['data_nascimento'],
            $data['posicao'],
            $data['email'],
            $data['telefone'] ?? null,
            $data['categoria'] ?? null,
            $data['acesso'] ?? null,
            $data['senha'] ?? null,
            $data['treinador_id'],
            $data['usuario_id'] ?? null
        );
        $this->repo->save($atleta);
    }
} 