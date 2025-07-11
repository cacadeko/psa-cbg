<?php
namespace App\Infrastructure\Persistence;

use App\Domain\Usuario\Usuario;
use PDO;

interface UsuarioRepositoryInterface {
    public function findByEmail(string $email): ?Usuario;
    public function save(Usuario $usuario): void;
}

class UsuarioRepository implements UsuarioRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findByEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $usuario = new Usuario($row['nome'], $row['email'], $row['senha_hash']);
        $usuario->setId($row['id']);
        return $usuario;
    }

    public function save(Usuario $usuario): void {
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)');
        $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getSenhaHash()
        ]);
        $usuario->setId((int)$this->pdo->lastInsertId());
    }
} 