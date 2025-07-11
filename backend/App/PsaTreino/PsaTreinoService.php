<?php
namespace App\PsaTreino;

class PsaTreinoService
{
    private PsaTreinoRepository $repository;

    public function __construct(PsaTreinoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, int $valor, ?string $observacoes = null): PsaTreino
    {
        $registro = new PsaTreino($id, $atletaId, $data, $valor, $observacoes);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?PsaTreino
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(PsaTreino $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 