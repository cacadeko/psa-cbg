<?php
namespace Controllers;

use Config\Database;
use PDO;

/* Carrega classe de conexão */
require_once __DIR__ . '/../config/Database.php';

class LoginController
{
    public function login(): void
    {
        /* só aceita POST */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /psa-cbg/views/login.php');
            exit;
        }

        /* sanitiza e valida */
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            die('Erro: E-mail e senha são obrigatórios.');
        }

        /* consulta usuário na tabela USUARIOS */
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT id, nome, senha_hash, nivel
             FROM usuarios
             WHERE email = :email
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        /* valida senha */
        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            die('Erro: E-mail ou senha inválidos.');
        }

        /* grava sessão */
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario']    = $usuario['nome'];
        $_SESSION['nivel']      = $usuario['nivel'];              // admin | treinador | atleta
        $_SESSION['acesso']     = ($usuario['nivel'] === 'admin') ? 1 : 0; // compat.

        /* redireciona ao painel */
        header('Location: /psa-cbg/index.php');
        exit;
    }
}
