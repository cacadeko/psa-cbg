<?php
    require_once '../index.php'; // Garante que o autoloader está carregado corretamente

    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    use Controllers\RegistroController;

    if (!isset($_SESSION['usuario'])) {
        header('Location: /pre-treino-rfc/views/login.php');
        exit;
    }
    
    // print_r($_POST);

    $registroController = new RegistroController();
    $registroController->alterarStatusRegistro($_POST['status']);
    header('Location: /pre-treino-rfc/index.php?success=1&status=1');
    exit;
?>
