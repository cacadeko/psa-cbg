<?php
namespace Models;

use Config\Database;
use PDO;

class Treinador
{
    public static function create(array $dados): bool
    {
        $pdo  = Database::getConnection();
        $sql  = "INSERT INTO treinadores
                 (nome,email,telefone,especialidade,data_contratacao,observacoes,usuario_id)
                 VALUES (:nome,:email,:telefone,:especialidade,:data_contratacao,:observacoes,:usuario_id)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($dados);
    }

    public static function update(int $id, array $dados): bool
    {
        $pdo = \Config\Database::getConnection();
        $sql = "UPDATE treinadores SET
                    nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    especialidade = :especialidade,
                    data_contratacao = :data_contratacao,
                    observacoes = :observacoes
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $dados['id'] = $id;
        return $stmt->execute($dados);
    }


    public static function listarTodos(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT * FROM treinadores ORDER BY id DESC")
                   ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete(int $id): bool
    {
        $pdo = \Config\Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM treinadores WHERE id = ?");
        return $stmt->execute([$id]);
    }


    /* implemente editar(), excluir(), buscar() se precisar */
}
