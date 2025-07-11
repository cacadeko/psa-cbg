<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require __DIR__ . '/../App/Usuario/UsuarioRouter.php';
require __DIR__ . '/../App/Atleta/AtletaRouter.php';

header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'API Backend PSA-CBG',
    'docs' => '/swagger.yaml'
]); 