<?php
namespace App\Jogo;

class JogoService
{
    private JogoRepository $repository;

    public function __construct(JogoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarJogo(int $id, string $data, string $local, string $adversario, ?string $resultado = null, ?string $observacoes = null): Jogo
    {
        $jogo = new Jogo($id, $data, $local, $adversario, $resultado, $observacoes);
        $this->repository->add($jogo);
        return $jogo;
    }

    public function obterJogo(int $id): ?Jogo
    {
        return $this->repository->getById($id);
    }

    public function listarJogos(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarJogo(Jogo $jogo): void
    {
        $this->repository->update($jogo);
    }

    public function removerJogo(int $id): void
    {
        $this->repository->delete($id);
    }
} 