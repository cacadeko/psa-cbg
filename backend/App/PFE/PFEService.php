<?php
namespace App\PFE;

class PFEService
{
    private PFERepository $repository;

    public function __construct(PFERepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, int $valor, ?string $observacoes = null): PFE
    {
        $registro = new PFE($id, $atletaId, $data, $valor, $observacoes);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?PFE
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(PFE $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 