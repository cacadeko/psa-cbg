<?php
namespace App\Jogo;

class JogoRepository
{
    private array $jogos = [];

    public function add(Jogo $jogo): void
    {
        $this->jogos[$jogo->getId()] = $jogo;
    }

    public function getById(int $id): ?Jogo
    {
        return $this->jogos[$id] ?? null;
    }

    public function getAll(): array
    {
        return array_values($this->jogos);
    }

    public function update(Jogo $jogo): void
    {
        $this->jogos[$jogo->getId()] = $jogo;
    }

    public function delete(int $id): void
    {
        unset($this->jogos[$id]);
    }
} 