<?php
namespace Controllers;

/* helper de usuário */
require_once dirname(__DIR__) . '/config/helpers/usuariosHelper.php';

/* model */
require_once dirname(__DIR__) . '/models/Treinador.php';
use Models\Treinador;

class TreinadoresController
{
    /** grava novo treinador + cria usuário automático */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método inválido');
        }

        /* captura dados */
        $nome             = $_POST['nome']             ?? null;
        $email            = $_POST['email']            ?? null;
        $telefone         = $_POST['telefone']         ?? null;
        $especialidade    = $_POST['especialidade']    ?? null;
        $data_contratacao = $_POST['data_contratacao'] ?? null;
        $observacoes      = $_POST['observacoes']      ?? null;

        if (!$nome || !$email) {
            die('Nome e e-mail são obrigatórios.');
        }

        /* cria usuário automático (global function) */
        $usuarioId = \criar_usuario_automatico($nome, $email, 'treinador');
        if (!$usuarioId) {
            die('E-mail já existe ou erro ao criar usuário.');
        }

        /* monta array e grava no model */
        $dados = [
            'nome'             => $nome,
            'email'            => $email,
            'telefone'         => $telefone,
            'especialidade'    => $especialidade,
            'data_contratacao' => $data_contratacao,
            'observacoes'      => $observacoes,
            'usuario_id'       => $usuarioId
        ];

        if (!Treinador::create($dados)) {
            die('Erro ao cadastrar treinador.');
        }

        header('Location: /pre-treino-rfc/index.php?success=1');
        exit;
    }

    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método inválido');
        }

        /* ─── captura dados ─── */
        $id               = (int)($_POST['id'] ?? 0);
        $nome             = $_POST['nome']             ?? null;
        $email            = $_POST['email']            ?? null;
        $telefone         = $_POST['telefone']         ?? null;
        $especialidade    = $_POST['especialidade']    ?? null;
        $data_contratacao = $_POST['data_contratacao'] ?? null;
        $observacoes      = $_POST['observacoes']      ?? null;

        if (!$id || !$nome || !$email) {
            die('ID, nome e e-mail são obrigatórios.');
        }

        /* ─── monta array e atualiza ─── */
        $dados = [
            'nome'             => $nome,
            'email'            => $email,
            'telefone'         => $telefone,
            'especialidade'    => $especialidade,
            'data_contratacao' => $data_contratacao,
            'observacoes'      => $observacoes
        ];

        if (!\Models\Treinador::update($id, $dados)) {
            die('Erro ao atualizar treinador.');
        }

        header('Location: /pre-treino-rfc/views/lista_treinadores.php?success=1');
        exit;
    }

    public function excluir(int $id): void
    {
        if (!\Models\Treinador::delete($id)) {          // ou TreinadoresModel
            die('Erro ao excluir treinador.');
        }
        header('Location: /pre-treino-rfc/views/lista_treinadores.php?deleted=1');
        exit;
    }


}
