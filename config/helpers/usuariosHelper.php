<?php
/**
 * Helper: criar usuário automaticamente
 * Uso: $id = criar_usuario_automatico($nome, $email, 'treinador');
 */

require_once dirname(__DIR__, 2) . '/models/UsuariosModel.php';
use Models\UsuariosModel;

function criar_usuario_automatico(string $nome, string $email, string $nivel = 'aluno'): int|false
{
    /* evita duplicidade */
    if (UsuariosModel::buscar_por_email($email)) {
        return false;
    }

    /* gera senha randômica e faz hash */
    $senhaPlain = bin2hex(random_bytes(4));
    $senhaHash  = password_hash($senhaPlain, PASSWORD_DEFAULT);

    $dados = [
        'nome'  => $nome,
        'email' => $email,
        'senha' => $senhaHash,
        'nivel' => $nivel
    ];

    return UsuariosModel::adicionar_usuario($dados); // devolve ID ou false
}
