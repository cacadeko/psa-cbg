<?php
namespace App\PFE;

class PFERepository
{
    private array $registros = [];

    public function add(PFE $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function getById(int $id): ?PFE
    {
        return $this->registros[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->registros);
    }

    public function update(PFE $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function delete(int $id): void
    {
        unset($this->registros[$id]);
    }
} 