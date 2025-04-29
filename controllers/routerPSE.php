<?php
require_once '../config/Database.php';
require_once '../models/PSE.php';
require_once '../controllers/PSEController.php';

use Controllers\PSEController;

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

$pseController = new PSEController();
$pseController->store();
?>
