<?php
namespace App\Minutagem;

class MinutagemService
{
    private MinutagemRepository $repository;

    public function __construct(MinutagemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, int $jogoId, bool $titular, ?int $minutoEntrou, ?int $minutoSaiu, int $minutosPrimeiroTempo, int $minutosSegundoTempo, ?string $observacoes = null): Minutagem
    {
        $registro = new Minutagem($id, $atletaId, $jogoId, $titular, $minutoEntrou, $minutoSaiu, $minutosPrimeiroTempo, $minutosSegundoTempo, $observacoes);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?Minutagem
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(Minutagem $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 