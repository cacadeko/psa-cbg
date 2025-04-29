<?php
/**
 * Router – Editar Treinador
 * Recebe POST do editar_treinador_form.php
 * e encaminha ao método editar() do TreinadoresController
 */

/******************* SEGURANÇA DE SESSÃO *******************/
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

/******************* AUTOLOAD BÁSICO ***********************/
spl_autoload_register(function ($class) {
    $base = dirname(__DIR__) . '/';                       // psa-cbg/
    $file = $base . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

/* evita “No direct script access allowed” vindos de arquivos CI */
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

/******************* CONTROLLER ****************************/
use Controllers\TreinadoresController;

/* instancia o controller */
$ctrl = new TreinadoresController();

/******************* ROTEAMENTO ****************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* chama o método editar() que você implementou no controller */
    $ctrl->editar();
} else {
    /* acesso incorreto: redireciona à lista de treinadores */
    header('Location: /psa-cbg/views/lista_treinadores.php');
    exit;
}
