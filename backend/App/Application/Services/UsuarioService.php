<?php
namespace App\Application\Services;

use App\Infrastructure\Persistence\UsuarioRepositoryInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsuarioService {
    private UsuarioRepositoryInterface $repo;
    private string $jwtSecret;
    public function __construct(UsuarioRepositoryInterface $repo, string $jwtSecret) {
        $this->repo = $repo;
        $this->jwtSecret = $jwtSecret;
    }

    public function autenticar(string $email, string $senha): ?string {
        $usuario = $this->repo->findByEmail($email);
        if (!$usuario || !password_verify($senha, $usuario->getSenhaHash())) {
            return null;
        }
        $payload = [
            'sub' => $usuario->getId(),
            'email' => $usuario->getEmail(),
            'iat' => time(),
            'exp' => time() + 3600
        ];
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
} 