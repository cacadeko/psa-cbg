<?php
namespace App\PSE;

class PSERepository
{
    private array $registros = [];

    public function add(PSE $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function getById(int $id): ?PSE
    {
        return $this->registros[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->registros);
    }

    public function update(PSE $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function delete(int $id): void
    {
        unset($this->registros[$id]);
    }
} 