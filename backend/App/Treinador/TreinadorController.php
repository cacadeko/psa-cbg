<?php
namespace App\Treinador;

class TreinadorController
{
    private TreinadorService $service;

    public function __construct(TreinadorService $service)
    {
        $this->service = $service;
    }

    public function criar($data)
    {
        return $this->service->criarTreinador(
            $data['id'],
            $data['nome'],
            $data['email'],
            $data['telefone'] ?? null
        );
    }

    public function listar()
    {
        return $this->service->listarTreinadores();
    }

    public function obter($id)
    {
        return $this->service->obterTreinador($id);
    }

    public function atualizar($data)
    {
        $treinador = $this->service->obterTreinador($data['id']);
        if ($treinador) {
            $treinador->setNome($data['nome']);
            $treinador->setEmail($data['email']);
            $treinador->setTelefone($data['telefone'] ?? null);
            $this->service->atualizarTreinador($treinador);
            return $treinador;
        }
        return null;
    }

    public function remover($id)
    {
        $this->service->removerTreinador($id);
        return true;
    }
} 