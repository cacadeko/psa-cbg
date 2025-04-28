<?php

require_once '../index.php'; // Garante que o autoloader está carregado corretamente

use Controllers\SonoController;

$controller = new SonoController();
$controller->excluir();
?>