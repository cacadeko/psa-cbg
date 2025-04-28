<?php

require_once '../config/Database.php';
require_once './LoginController.php';

use Controllers\LoginController;

session_start(); // Certifique-se de iniciar a sessão aqui

$controller = new LoginController();
$controller->login();


?>