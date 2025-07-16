<?php
namespace App\Usuario;

use Firebase\JWT\JWT;

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

    public function cadastrar(array $data): int {
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
        $usuario = new Usuario(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $senhaHash
        );
        return $this->repo->save($usuario);
    }

    public function listar(): array {
        return $this->repo->listarTodos();
    }

    public function editar(array $data): bool {
        $usuario = $this->repo->findById($data['id']);
        if (!$usuario) {
            return false;
        }
        
        $usuario->setNome($data['nome'] ?? '');
        $usuario->setEmail($data['email'] ?? '');
        
        if (!empty($data['senha'])) {
            $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
            $usuario->setSenhaHash($senhaHash);
        }
        
        return $this->repo->update($usuario);
    }

    public function excluir(int $id): bool {
        return $this->repo->delete($id);
    }
} 