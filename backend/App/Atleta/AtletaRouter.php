<?php

namespace App\Atleta;

$pdo = new \PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$repo = new AtletaRepository($pdo);
$usuarioRepo = new \App\Usuario\UsuarioRepository($pdo);
$service = new AtletaService($repo, $usuarioRepo);
$controller = new AtletaController($service);

// Removido o if que tratava POST para /api/atletas

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Exemplo: criar atleta
        $data = json_decode(file_get_contents('php://input'), true);
        $treinadorId = $_SESSION['usuario_id'] ?? null;
        try {
            $id = $controller->store($data, $treinadorId);
            http_response_code(201);
            echo json_encode(['id' => $id]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
    case 'GET':
        // Exemplo: listar atletas
        $result = $controller->listar();
        echo json_encode($result);
        break;
    case 'DELETE':
        // Corrigido: extrair o ID do path da URL
        $uri = $_SERVER['REQUEST_URI'];
        if (preg_match('#/api/atletas/(\\d+)#', $uri, $matches)) {
            $id = (int)$matches[1];
            if ($controller->excluir($id)) {
                echo json_encode(['deleted' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erro ao excluir']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido na URL']);
        }
        break;
    case 'PUT':
        // Exemplo: editar atleta
        $data = json_decode(file_get_contents('php://input'), true);
        if ($controller->editar($data)) {
            echo json_encode(['updated' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao editar']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
} 