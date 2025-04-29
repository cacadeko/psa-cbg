<?php
// Criando um arquivo de logout para encerrar a sessão (controllers/logout.php)
namespace Controllers;

session_start();
session_destroy();
header('Location: /psa-cbg/views/login.php');
exit;
?>