<?php
namespace App\Usuario;

class UsuarioController {
    private UsuarioService $service;
    public function __construct(UsuarioService $service) {
        $this->service = $service;
    }

    public function login(array $request): void {
        $email = $request['email'] ?? '';
        $senha = $request['senha'] ?? '';
        $token = $this->service->autenticar($email, $senha);
        if ($token) {
            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciais inválidas']);
        }
    }

    public function store(array $data): int {
        return $this->service->cadastrar($data);
    }

    public function listar(): array {
        return $this->service->listar();
    }

    public function editar(array $data): bool {
        return $this->service->editar($data);
    }

    public function excluir(int $id): bool {
        return $this->service->excluir($id);
    }
} 