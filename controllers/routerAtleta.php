<?php
/******************************************************************************
 * Router – Atleta (sem incluir o index)
 * Recebe POST do atleta_form.php e chama AtletaController::store()
 *****************************************************************************/

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}

/* Carrega apenas o autoloader ou diretamente o controlador  */
require_once __DIR__ . '/AtletaController.php';     // caminho relativo

use Controllers\AtletaController;

$controller = new AtletaController();
$controller->store();
