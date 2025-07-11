<?php
namespace App\Usuario;

// Setup PDO
$pdo = new \PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$repo = new UsuarioRepository($pdo);
$service = new UsuarioService($repo, $_ENV['JWT_SECRET']);
$controller = new UsuarioController($service);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->login($data);
    exit;
} 