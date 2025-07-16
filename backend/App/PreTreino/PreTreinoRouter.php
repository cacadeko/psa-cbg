<?php

namespace App\PreTreino;

$pdo = new \PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$repo = new PreTreinoRepository($pdo);
$service = new PreTreinoService($repo);
$controller = new PreTreinoController($service);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Criar pré-treino
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $id = $controller->store($data);
            http_response_code(201);
            echo json_encode(['id' => $id, 'message' => 'Pré-treino criado com sucesso']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        
    case 'GET':
        // Listar pré-treinos
        try {
            $result = $controller->listar();
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao listar pré-treinos: ' . $e->getMessage()]);
        }
        break;
        
    case 'PUT':
        // Atualizar pré-treino
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            if ($controller->editar($data)) {
                echo json_encode(['message' => 'Pré-treino atualizado com sucesso']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erro ao atualizar pré-treino']);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        
    case 'DELETE':
        // Excluir pré-treino
        $uri = $_SERVER['REQUEST_URI'];
        if (preg_match('#/api/pre-treino/(\\d+)#', $uri, $matches)) {
            $id = (int)$matches[1];
            try {
                if ($controller->excluir($id)) {
                    echo json_encode(['message' => 'Pré-treino excluído com sucesso']);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Erro ao excluir pré-treino']);
                }
            } catch (\Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido na URL']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        break;
} 