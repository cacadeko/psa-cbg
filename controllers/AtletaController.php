<?php
namespace Controllers;

use Models\Atleta;

require_once __DIR__ . '/../models/Atleta.php';
require_once __DIR__ . '/../config/helpers/usuariosHelper.php';

class AtletaController
{
    /* ───────────────────────────── STORE ───────────────────────────── */
    public function store()
    {
        /* garante sessão e captura treinador_id */
        if (session_status() === PHP_SESSION_NONE) session_start();
        $treinadorId = $_SESSION['usuario_id'] ?? null;      // ★ ID do usuário logado

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Erro: Método inválido.");
        }

        try {
            /* ─── obtém dados do formulário ─────────────────────────── */
            $nome            = $_POST['nome']            ?? null;
            $data_nascimento = $_POST['data_nascimento'] ?? null;
            $posicao         = $_POST['posicao']         ?? null;
            $email           = $_POST['email']           ?? null;
            $telefone        = $_POST['telefone']        ?? null;
            $categoria       = $_POST['categoria']       ?? null;
            $acesso          = 2;
            $senha           = $_POST['senha']           ?? null;

            if (!$nome || !$data_nascimento || !$posicao || !$email) {
                throw new \Exception("Todos os campos obrigatórios devem ser preenchidos.");
            }
            if (!$treinadorId) {
                throw new \Exception("Sessão inválida: treinador não identificado.");
            }

            /* ─── grava atleta ─────────────────────────── */
            $atletaId = Atleta::create(
                $nome,
                $data_nascimento,
                $posicao,
                $email,
                $telefone,
                $categoria,
                $acesso,
                $senha,
                $treinadorId          // ★ novo parâmetro
            );

            if (!$atletaId) {
                throw new \Exception("Erro ao cadastrar atleta.");
            }

            /* ─── cria usuário automaticamente (nível = aluno) ──────── */
            $usuarioId = \criar_usuario_automatico($nome, $email, 'aluno');

            if ($usuarioId) {
                Atleta::vincularUsuario($atletaId, $usuarioId);
            }

            header('Location: /psa-cbg/index.php?success=1');
            exit;

        } catch (\Exception $e) {
            die("Erro: " . $e->getMessage());
        }
    }

    /* ───────────────────────────── LISTAR ──────────────────────────── */
    public function listar(): array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    
        $nivel       = $_SESSION['nivel']      ?? 'aluno';
        $treinadorId = $_SESSION['usuario_id'] ?? 0;
    
        /* admin vê tudo */
        if ($nivel === 'admin') {
            return \Models\Atleta::listarTodos();
        }
    
        /* treinador vê apenas seus atletas */
        if ($nivel === 'treinador') {
            return \Models\Atleta::listarPorTreinador($treinadorId);
        }
    
        /* demais perfis (atleta, etc.) veem nada ou lógica própria */
        return [];
    }
    
    

    /* ───────────────────────────── EXCLUIR ─────────────────────────── */
    public function excluir(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /psa-cbg/views/lista_atletas.php?erro=id_nao_informado');
            exit;
        }

        try {
            if (!\Models\Atleta::excluir($id)) {
                header('Location: /psa-cbg/views/lista_atletas.php?erro=erro_exclusao');
                exit;
            }

            header('Location: /psa-cbg/views/lista_atletas.php?deleted=1');
            exit;

        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: /psa-cbg/views/lista_atletas.php?erro=pfe_associado');
                exit;
            }

            header('Location: /psa-cbg/views/lista_atletas.php?erro=excecao');
            exit;
        }
    }


    /* ───────────────────────────── EDITAR ──────────────────────────── */
    public function editar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

            $id              = $_POST['id']              ?? null;
            $nome            = $_POST['nome']            ?? null;
            $data_nascimento = $_POST['data_nascimento'] ?? null;
            $posicao         = $_POST['posicao']         ?? null;
            $email           = $_POST['email']           ?? null;
            $telefone        = $_POST['telefone']        ?? null;
            $categoria       = $_POST['categoria']       ?? null;
            $acesso          = $_POST['acesso']          ?? null;
            $senha           = $_POST['senha']           ?? null;

            if (Atleta::editar(
                    $id,
                    $nome,
                    $data_nascimento,
                    $posicao,
                    $email,
                    $telefone,
                    $categoria,
                    $senha,
                    $acesso
                )) {
                header('Location: /psa-cbg/views/lista_atletas.php?success=1');
                exit;
            }
            die("Erro ao editar atleta.");
        }
    }
}
