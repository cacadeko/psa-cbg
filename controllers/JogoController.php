<?php
namespace Controllers;
require_once __DIR__.'/../models/Jogo.php';
use Models\Jogo;

class JogoController
{
    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $d = [
            ':data'  => $_POST['data_jogo'],
            ':local' => $_POST['local_jogo'],
            ':adv'   => $_POST['adversario'],
            ':camp'  => $_POST['campeonato'],
            ':user'  => $_SESSION['usuario_id']
        ];
        Jogo::create($d);
        header('Location: /psa-cbg/views/lista_jogos.php');
        exit;
    }

    public function listar(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return ($_SESSION['nivel']==='admin')
             ? Jogo::listarTodos()
             : Jogo::listarPorUsuario($_SESSION['usuario_id']);
    }
}
