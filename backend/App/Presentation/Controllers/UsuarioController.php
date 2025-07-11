<?php
namespace App\Presentation\Controllers;

use App\Application\Services\UsuarioService;

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
} 