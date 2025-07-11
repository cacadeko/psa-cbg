<?php
namespace App\Treinador;

class TreinadorRepository
{
    // Exemplo de armazenamento em memória (substituir por integração com banco de dados)
    private array $treinadores = [];

    public function add(Treinador $treinador): void
    {
        $this->treinadores[$treinador->getId()] = $treinador;
    }

    public function getById(int $id): ?Treinador
    {
        return $this->treinadores[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->treinadores);
    }

    public function update(Treinador $treinador): void
    {
        $this->treinadores[$treinador->getId()] = $treinador;
    }

    public function delete(int $id): void
    {
        unset($this->treinadores[$id]);
    }
} 