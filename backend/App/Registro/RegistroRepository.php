<?php
namespace App\Registro;

class RegistroRepository
{
    private array $registros = [];

    public function add(Registro $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function getById(int $id): ?Registro
    {
        return $this->registros[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->registros);
    }

    public function update(Registro $registro): void
    {
        $this->registros[$registro->getId()] = $registro;
    }

    public function delete(int $id): void
    {
        unset($this->registros[$id]);
    }
} 