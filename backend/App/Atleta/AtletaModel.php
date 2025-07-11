<?php

namespace App\Atleta;

use Backend\Config\Database;
use PDO;

class AtletaModel {
    public static function create(
        string $nome,
        string $data_nascimento,
        string $posicao,
        string $email,
        ?string $telefone,
        ?string $categoria,
        ?string $acesso,
        ?string $senha,
        int    $treinador_id,
        ?int   $usuario_id = null
    ): int|false {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO atletas
                (nome, data_nascimento, posicao, email, telefone,
                 categoria, acesso, senha, treinador_id, usuario_id)
                VALUES
                (:nome, :data_nascimento, :posicao, :email, :telefone,
                 :categoria, :acesso, :senha, :treinador_id, :usuario_id)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':nome'          => $nome,
            ':data_nascimento'=> $data_nascimento,
            ':posicao'       => $posicao,
            ':email'         => $email,
            ':telefone'      => $telefone,
            ':categoria'     => $categoria,
            ':acesso'        => $acesso,
            ':senha'         => $senha,
            ':treinador_id'  => $treinador_id,
            ':usuario_id'    => $usuario_id
        ]);
        return $ok ? (int)$pdo->lastInsertId() : false;
    }

    public static function vincularUsuario(int $atletaId, int $usuarioId): bool
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE atletas SET usuario_id = :usuario WHERE id = :atleta";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':usuario' => $usuarioId,
            ':atleta'  => $atletaId
        ]);
    }

    public static function listarTodos() {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM atletas");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erro ao buscar atletas: " . $e->getMessage());
        }
    }

    public static function listarPorTreinador(int $treinadorId): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT * FROM atletas
             WHERE treinador_id = :tid
             ORDER BY id DESC"
        );
        $stmt->execute([':tid' => $treinadorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function excluir($id)
    {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM atletas WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public static function editar($id, $nome, $data_nascimento, $posicao, $email, $telefone, $categoria, $senha, $acesso) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE atletas 
            SET nome = ?, data_nascimento = ?, posicao = ?, email = ?, telefone = ?, categoria = ?, senha = ?, acesso = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$nome, $data_nascimento, $posicao, $email, $telefone, $categoria, $senha, $acesso, $id]);
    }

    public static function getSenhaAtual($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT senha FROM atletas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public static function buscarPorId($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM atletas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 