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

/* evita “No direct script access allowed” de arquivos CI */
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

use Controllers\TreinadoresController;

/* instancia */
$ctrl = new TreinadoresController();

/* roteamento */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl->store();                // grava
} else {
    header('Location: /psa-cbg/views/lista_treinadores.php');
    exit;
}
