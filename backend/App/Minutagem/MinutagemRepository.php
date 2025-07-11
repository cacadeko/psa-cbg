<?php
namespace App\Minutagem;

class MinutagemRepository
{
    private array $registros = [];

    public function add(Minutagem $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function getById(int $id): ?Minutagem
    {
        return $this->registros[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->registros);
    }

    public function update(Minutagem $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function delete(int $id): void
    {
        unset($this->registros[$id]);
    }
} 