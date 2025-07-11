<?php
namespace App\PsaTreino;

class PsaTreinoController
{
    private PsaTreinoService $service;

    public function __construct(PsaTreinoService $service)
    {
        $this->service = $service;
    }

    public function criar($data)
    {
        return $this->service->criarRegistro(
            $data['id'],
            $data['atletaId'],
            $data['data'],
            $data['valor'],
            $data['observacoes'] ?? null
        );
    }

    public function listar()
    {
        return $this->service->listarRegistros();
    }

    public function obter($id)
    {
        return $this->service->obterRegistro($id);
    }

    public function atualizar($data)
    {
        $registro = $this->service->obterRegistro($data['id']);
        if ($registro) {
            $registro->setAtletaId($data['atletaId']);
            $registro->setData($data['data']);
            $registro->setValor($data['valor']);
            $registro->setObservacoes($data['observacoes'] ?? null);
            $this->service->atualizarRegistro($registro);
            return $registro;
        }
        return null;
    }

    public function remover($id)
    {
        $this->service->removerRegistro($id);
        return true;
    }
} 