<?php
namespace App\PsaTreino;

class PsaTreinoRepository
{
    private array $registros = [];

    public function add(PsaTreino $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function getById(int $id): ?PsaTreino
    {
        return $this->registros[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->registros);
    }

    public function update(PsaTreino $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function delete(int $id): void
    {
        unset($this->registros[$id]);
    }
} 