<?php

namespace App\Treinador;

use App\Usuario\UsuarioRepository;

// Configurar CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$pdo = new \PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$treinadorRepo = new TreinadorRepository($pdo);
$usuarioRepo = new UsuarioRepository($pdo);
$service = new TreinadorService($treinadorRepo, $usuarioRepo);
$controller = new TreinadorController($service);

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            // Criar treinador
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados JSON inválidos']);
                exit();
            }
            
            // Validar campos obrigatórios
            if (empty($data['nome']) || empty($data['email'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Nome e email são obrigatórios']);
                exit();
            }
            
            $id = $controller->store($data);
            http_response_code(201);
            echo json_encode(['id' => $id, 'message' => 'Treinador criado com sucesso']);
            break;
            
        case 'GET':
            // Listar treinadores
            header('Content-Type: application/json; charset=utf-8');
            $result = $controller->listar();
            echo json_encode($result);
            break;
            
        case 'PUT':
            // Editar treinador
            $data = json_decode(file_get_contents('php://input'), true);
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', $path);
            $id = end($segments);
            
            if (!$id || !is_numeric($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'ID inválido']);
                exit();
            }
            
            $data['id'] = (int)$id;
            $success = $controller->editar($data);
            
            if ($success) {
                echo json_encode(['message' => 'Treinador atualizado com sucesso']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erro ao atualizar treinador']);
            }
            break;
            
        case 'DELETE':
            // Excluir treinador
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', $path);
            $id = end($segments);
            
            if (!$id || !is_numeric($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'ID inválido']);
                exit();
            }
            
            $success = $controller->excluir((int)$id);
            
            if ($success) {
                echo json_encode(['message' => 'Treinador excluído com sucesso']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erro ao excluir treinador']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
            break;
    }
} catch (\Exception $e) {
    error_log('Erro no TreinadorRouter: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor']);
} 