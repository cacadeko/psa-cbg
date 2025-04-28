<?php
/**
 * assets/scripts/resetar_senhas.php
 * Redefine TODAS as senhas da tabela usuarios para “123456”
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* 1. Conexão PDO (sobe 2 níveis: assets/scripts → raiz) */
require_once dirname(__DIR__, 2) . '/config/Database.php';
$pdo = \Config\Database::getConnection();

/* 2. Novo hash */
$novoHash = password_hash('123456', PASSWORD_DEFAULT);

/* 3. Atualiza */
$sql  = "UPDATE usuarios SET senha_hash = :hash";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([':hash' => $novoHash])) {
    echo "✔ Senhas redefinidas (".$stmt->rowCount()." usuário[s]).";
} else {
    echo "⚠ Erro ao atualizar senhas.";
}
