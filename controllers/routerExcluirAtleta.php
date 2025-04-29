<?php
    require_once '../config/Database.php';
    require_once '../models/Atleta.php';
    require_once '../controllers/AtletaController.php';

    use Controllers\AtletaController;

    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: /psa-cbg/views/login.php');
        exit;
    }

    $controller = new AtletaController();
    $controller->excluir();
?>
