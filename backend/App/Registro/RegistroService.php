<?php
namespace App\Registro;

class RegistroService
{
    private RegistroRepository $repository;

    public function __construct(RegistroRepository $repository)
    {
        $this->repository = $repository;
    }

    public function criarRegistro(int $id, int $atletaId, string $data, string $descricao): Registro
    {
        $registro = new Registro($id, $atletaId, $data, $descricao);
        $this->repository->add($registro);
        return $registro;
    }

    public function obterRegistro(int $id): ?Registro
    {
        return $this->repository->getById($id);
    }

    public function listarRegistros(): array
    {
        return $this->repository->getAll();
    }

    public function atualizarRegistro(Registro $registro): void
    {
        $this->repository->update($registro);
    }

    public function removerRegistro(int $id): void
    {
        $this->repository->delete($id);
    }
} 