<?php
namespace App\QualidadeSono;

class QualidadeSonoService
{
    private QualidadeSonoRepository $repository;

    public function __construct(QualidadeSonoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, int $qualidade, ?string $observacoes = null): QualidadeSono
    {
        $registro = new QualidadeSono($id, $atletaId, $data, $qualidade, $observacoes);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?QualidadeSono
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(QualidadeSono $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 