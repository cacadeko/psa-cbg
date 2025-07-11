<?php
namespace App\Treinador;

class TreinadorService
{
    private TreinadorRepository $repository;

    public function __construct(TreinadorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarTreinador(int $id, string $nome, string $email, ?string $telefone = null): Treinador
    {
        $treinador = new Treinador($id, $nome, $email, $telefone);
        $this->repository->add($treinador);
        return $treinador;
    }

    public function obterTreinador(int $id): ?Treinador
    {
        return $this->repository->getById($id);
    }

    public function listarTreinadores(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarTreinador(Treinador $treinador): void
    {
        $this->repository->update($treinador);
    }

    public function removerTreinador(int $id): void
    {
        $this->repository->delete($id);
    }
} 