<?php
/**
 * Migrar atletas → criar usuários e vincular usuario_id
 * Coloque este arquivo em assets/scripts/  (como você já fez)
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* 1. Inclui dependências – sobe DUAS pastas */
require_once dirname(__DIR__, 2) . '/config/Database.php';
require_once dirname(__DIR__, 2) . '/config/helpers/usuariosHelper.php';

use Config\Database;   // classe de conexão

/* 2. Conexão PDO */
$pdo = Database::getConnection();

/* 3. Seleciona atletas sem usuario_id */
$sql = "SELECT id, nome, email FROM atletas
        WHERE usuario_id IS NULL OR usuario_id = 0";
$atletas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "Atletas sem usuário: " . count($atletas) . PHP_EOL;

/* 4. Loop */
foreach ($atletas as $a) {
    $nome  = $a['nome'];
    $email = $a['email'];

    if (!$email) {
        echo "[SKIP] Atleta {$a['id']} sem e-mail.\n";
        continue;
    }

    // cria usuário (ou retorna false se já existe)
    $usuarioId = \criar_usuario_automatico($nome, $email, 'aluno');

    // se já existia, pega o id existente
    if (!$usuarioId) {
        $usuarioId = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?")
                         ->execute([$email]) ? $pdo->lastInsertId() : 0;
        echo "[EXISTE] {$email} (ID $usuarioId)\n";
    } else {
        echo "[CRIADO] {$email} (ID $usuarioId)\n";
    }

    // atualiza atleta
    if ($usuarioId) {
        $upd = $pdo->prepare("UPDATE atletas SET usuario_id = ? WHERE id = ?");
        $upd->execute([$usuarioId, $a['id']]);
        echo "  → Atleta {$a['id']} vinculado.\n";
    }
}

echo "Migração concluída!\n";
