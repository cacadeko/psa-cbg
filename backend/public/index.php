<?php
// CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Exibir status apenas na rota raiz
if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'message' => 'API Backend PSA-CBG',
        'docs' => '/swagger.yaml'
    ]);
    exit;
}

// Roteamento baseado na URL
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remover /api se presente
if (strpos($path, '/api') === 0) {
    $path = substr($path, 4);
}

// Roteamento para diferentes endpoints
if (strpos($path, '/treinadores') === 0) {
    require __DIR__ . '/../App/Treinador/TreinadorRouter.php';
} elseif (strpos($path, '/atletas') === 0) {
    require __DIR__ . '/../App/Atleta/AtletaRouter.php';
} elseif (strpos($path, '/usuarios') === 0) {
    require __DIR__ . '/../App/Usuario/UsuarioRouter.php';
} elseif (strpos($path, '/jogos') === 0) {
    require __DIR__ . '/../App/Presentation/Routes/api.php';
} elseif (strpos($path, '/minutagem') === 0) {
    require __DIR__ . '/../App/Presentation/Routes/api.php';
} elseif (strpos($path, '/pre-treino') === 0) {
    require __DIR__ . '/../App/PreTreino/PreTreinoRouter.php';
} else {
    // Rota padrão - incluir atletas
    require __DIR__ . '/../App/Atleta/AtletaRouter.php';
} 