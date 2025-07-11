<?php
namespace App\Minutagem;

class MinutagemController
{
    private MinutagemService $service;

    public function __construct(MinutagemService $service)
    {
        $this->service = $service;
    }

    public function criar($data)
    {
        return $this->service->criarRegistro(
            $data['id'],
            $data['atletaId'],
            $data['data'],
            $data['minutos'],
            $data['atividade']
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
            $registro->setMinutos($data['minutos']);
            $registro->setAtividade($data['atividade']);
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