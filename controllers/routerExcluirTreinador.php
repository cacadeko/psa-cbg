<?php
/**
 * Router – Excluir Treinador
 * URL de chamada: /pre-treino-rfc/controllers/routerExcluirTreinador.php?id=1
 */

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}

/* ─── autoload simples ─── */
spl_autoload_register(function ($class) {
    $base = dirname(__DIR__) . '/';
    $file = $base . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

/* Evita “No direct script access allowed” */
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

use Controllers\TreinadoresController;

/* ─── valida ID ─── */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID inválido.');
}

/* ─── instancia controller e chama exclusão ─── */
$ctrl = new TreinadoresController();
$ctrl->excluir($id);           // você implementou excluir($id) no controller?
