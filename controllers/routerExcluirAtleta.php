<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

/* autoload simples */
spl_autoload_register(function ($class) {
    $base = dirname(__DIR__) . '/';
    $file = $base . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

use Controllers\AtletaController;

/* instancia o controller */
$ctrl = new AtletaController();

/* executa a exclusão */
$ctrl->excluir();
