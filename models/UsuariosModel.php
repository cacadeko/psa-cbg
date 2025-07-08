<?php
namespace Models;

require_once dirname(__DIR__) . '/config/Database.php';   // << NOVO


use Config\Database;
use PDO;

class UsuariosModel
{
    /** grava usuário e retorna ID */
   public static function adicionar_usuario(array $dados): int|false
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, nivel)
                VALUES (:nome, :email, :senha_hash, :nivel)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([
                ':nome'       => $dados['nome'],
                ':email'      => $dados['email'],
                ':senha_hash' => password_hash($dados['senha'], PASSWORD_DEFAULT),
                ':nivel'      => $dados['nivel']
            ])) {
            return (int)$pdo->lastInsertId();
        }
        return false;
    }


    /** busca usuário por e-mail */
    public static function buscar_por_email(string $email): array|false
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listar_todos(): array
    {
        $pdo=\Config\Database::getConnection();
        return $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")
                ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update(int $id, array $dados): bool
    {
        $pdo = Database::getConnection();
        $set = [];
        $params = ['id' => $id];

        foreach ($dados as $k => $v) {
            if ($k === 'senha') {
                $set[] = "senha_hash = :senha_hash";
                $params['senha_hash'] = password_hash($v, PASSWORD_DEFAULT);
            } else {
                $set[] = "$k = :$k";
                $params[$k] = $v;
            }
        }

        $sql = "UPDATE usuarios SET " . implode(',', $set) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }


    public static function delete(int $id): bool
    {
        $pdo=\Config\Database::getConnection();
        return $pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
    }

}
