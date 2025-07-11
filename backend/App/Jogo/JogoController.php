<?php
namespace App\Jogo;

class JogoController
{
    private JogoService $service;

    public function __construct(JogoService $service)
    {
        $this->service = $service;
    }

    public function criar($data)
    {
        return $this->service->criarJogo(
            $data['id'],
            $data['data'],
            $data['local'],
            $data['adversario'],
            $data['resultado'] ?? null,
            $data['observacoes'] ?? null
        );
    }

    public function listar()
    {
        return $this->service->listarJogos();
    }

    public function obter($id)
    {
        return $this->service->obterJogo($id);
    }

    public function atualizar($data)
    {
        $jogo = $this->service->obterJogo($data['id']);
        if ($jogo) {
            $jogo->setData($data['data']);
            $jogo->setLocal($data['local']);
            $jogo->setAdversario($data['adversario']);
            $jogo->setResultado($data['resultado'] ?? null);
            $jogo->setObservacoes($data['observacoes'] ?? null);
            $this->service->atualizarJogo($jogo);
            return $jogo;
        }
        return null;
    }

    public function remover($id)
    {
        $this->service->removerJogo($id);
        return true;
    }
} 