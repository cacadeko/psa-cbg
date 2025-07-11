<?php
namespace App\PSE;

class PSEService
{
    private PSERepository $repository;

    public function __construct(PSERepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, int $valor, ?string $observacoes = null): PSE
    {
        $registro = new PSE($id, $atletaId, $data, $valor, $observacoes);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?PSE
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(PSE $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 