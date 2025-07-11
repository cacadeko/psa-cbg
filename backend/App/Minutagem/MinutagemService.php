<?php
namespace App\Minutagem;

class MinutagemService
{
    private MinutagemRepository $repository;

    public function __construct(MinutagemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, int $minutos, string $atividade): Minutagem
    {
        $registro = new Minutagem($id, $atletaId, $data, $minutos, $atividade);
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