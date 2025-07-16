<?php
namespace App\Usuario;

use PDO;

interface UsuarioRepositoryInterface {
    public function findByEmail(string $email): ?Usuario;
    public function findById(int $id): ?Usuario;
    public function save(Usuario $usuario): int;
    public function listarTodos(): array;
    public function update(Usuario $usuario): bool;
    public function delete(int $id): bool;
    public function atualizar(int $usuarioId, array $data): void;
    public function atualizarSenha(int $usuarioId, string $senhaHash): void;
}

class UsuarioRepository implements UsuarioRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findByEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $usuario = new Usuario($row['nome'], $row['email'], $row['senha_hash'], $row['nivel'] ?? 'treinador', $row['ativo'] ?? true);
        $usuario->setId($row['id']);
        return $usuario;
    }

    public function findById(int $id): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $usuario = new Usuario($row['nome'], $row['email'], $row['senha_hash'], $row['nivel'] ?? 'treinador', $row['ativo'] ?? true);
        $usuario->setId($row['id']);
        return $usuario;
    }

    public function save(Usuario $usuario): int {
        error_log('UsuarioRepository: Antes do insert');
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash, nivel, ativo) VALUES (?, ?, ?, ?, ?)');
        error_log('UsuarioRepository: Statement preparado');
        try {
            $stmt->execute([
                $usuario->getNome(),
                $usuario->getEmail(),
                $usuario->getSenhaHash(),
                $usuario->getNivel(),
                $usuario->getAtivo()
            ]);
            error_log('UsuarioRepository: Insert executado');
            $id = (int)$this->pdo->lastInsertId();
            $usuario->setId($id);
            error_log('UsuarioRepository: Usuario salvo com id=' . $usuario->getId());
            return $id;
        } catch (\PDOException $e) {
            error_log('UsuarioRepository: ERRO NO INSERT: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listarTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM usuarios ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Usuario $usuario): bool {
        $stmt = $this->pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, nivel = ?, ativo = ? WHERE id = ?');
        return $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getNivel(),
            $usuario->getAtivo(),
            $usuario->getId()
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function atualizar(int $usuarioId, array $data): void {
        $campos = [];
        $valores = [];
        
        if (isset($data['nome'])) {
            $campos[] = 'nome = ?';
            $valores[] = $data['nome'];
        }
        if (isset($data['email'])) {
            $campos[] = 'email = ?';
            $valores[] = $data['email'];
        }
        if (isset($data['nivel'])) {
            $campos[] = 'nivel = ?';
            $valores[] = $data['nivel'];
        }
        if (isset($data['ativo'])) {
            $campos[] = 'ativo = ?';
            $valores[] = $data['ativo'];
        }
        
        if (!empty($campos)) {
            $valores[] = $usuarioId;
            $sql = 'UPDATE usuarios SET ' . implode(', ', $campos) . ' WHERE id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($valores);
        }
    }

    public function atualizarSenha(int $usuarioId, string $senhaHash): void {
        $stmt = $this->pdo->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
        $stmt->execute([$senhaHash, $usuarioId]);
    }
} 