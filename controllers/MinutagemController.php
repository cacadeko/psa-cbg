<?php
namespace Controllers;

require_once __DIR__ . '/../models/Minutagem.php';
use Models\Minutagem;

class MinutagemController
{
    /** salva minutagem enviada pelo formulário */
    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pre-treino-rfc/index.php');
            exit;
        }

        $dados = [
            ':atleta_id'   => $_POST['atleta_id'],
            ':treinador_id'=> $_SESSION['usuario_id'],
            ':data_jogo'   => $_POST['data_jogo'],
            ':local_jogo'  => $_POST['local_jogo'],
            ':titular'     => isset($_POST['titular']) ? 1 : 0,
            ':min_entrou'  => $_POST['minuto_entrou'] ?: null,
            ':min_saiu'    => $_POST['minuto_saiu']   ?: null,
            ':min1'        => $_POST['minutos_primeiro_tempo'] ?: 0,
            ':min2'        => $_POST['minutos_segundo_tempo'] ?: 0,
            ':obs'         => $_POST['observacoes'] ?: null
        ];

        Minutagem::create($dados);
        header('Location: /pre-treino-rfc/views/lista_minutagem.php?success=1');
        exit;
    }

    /** devolve lista filtrada conforme perfil */
    public function listar(): array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SESSION['nivel'] === 'admin') {
            return Minutagem::listarTodos();
        }
        return Minutagem::listarPorTreinador($_SESSION['usuario_id']);
    }
}
