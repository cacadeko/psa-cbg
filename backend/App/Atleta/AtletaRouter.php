<?php

namespace App\Atleta;

$pdo = new \PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$repo = new AtletaRepository($pdo);
$service = new AtletaService($repo);
$controller = new AtletaController($service);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/atletas') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->cadastrar($data);
    exit;
}

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
        $nivel = $_SESSION['nivel'] ?? 'aluno';
        $treinadorId = $_SESSION['usuario_id'] ?? 0;
        $result = $controller->listar($nivel, $treinadorId);
        echo json_encode($result);
        break;
    case 'DELETE':
        // Exemplo: excluir atleta
        parse_str($_SERVER['QUERY_STRING'], $params);
        $id = $params['id'] ?? null;
        if ($id && $controller->excluir((int)$id)) {
            echo json_encode(['deleted' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao excluir']);
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