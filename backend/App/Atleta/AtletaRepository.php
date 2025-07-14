<?php
namespace App\Atleta;

use PDO;

interface AtletaRepositoryInterface {
    public function findById(int $id): ?Atleta;
    public function save(Atleta $atleta): int;
    public function listarTodos(): array;
    public function editar(array $data): bool;
    public function excluir(int $id): bool;
}

class AtletaRepository implements AtletaRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findById(int $id): ?Atleta {
        // Implementação mínima
        return null;
    }

    public function save(Atleta $atleta): int {
        $sql = "INSERT INTO atletas (nome, data_nascimento, posicao, email, telefone, categoria, senha, acesso, created_at, usuario_id, treinador_id)
                VALUES (:nome, :data_nascimento, :posicao, :email, :telefone, :categoria, :senha, :acesso, CURRENT_TIMESTAMP, :usuario_id, :treinador_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $atleta->getNome(),
            ':data_nascimento' => $atleta->getDataNascimento(),
            ':posicao' => $atleta->getPosicao(),
            ':email' => $atleta->getEmail(),
            ':telefone' => $atleta->getTelefone(),
            ':categoria' => $atleta->getCategoria(),
            ':senha' => $atleta->getSenha(),
            ':acesso' => $atleta->getAcesso(),
            ':usuario_id' => ($atleta->getUsuarioId() ? $atleta->getUsuarioId() : null),
            ':treinador_id' => ($atleta->getTreinadorId() !== null && $atleta->getTreinadorId() !== '') ? (int)$atleta->getTreinadorId() : null
        ]);
        
        return (int) $this->pdo->lastInsertId();
    }

    public function listarTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM atletas');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editar(array $data): bool {
        $sql = "UPDATE atletas SET nome = :nome, data_nascimento = :data_nascimento, posicao = :posicao, email = :email, telefone = :telefone, categoria = :categoria, acesso = :acesso, treinador_id = :treinador_id, usuario_id = :usuario_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':data_nascimento' => $data['data_nascimento'],
            ':posicao' => $data['posicao'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':categoria' => $data['categoria'],
            ':acesso' => $data['acesso'],
            ':treinador_id' => $data['treinador_id'],
            ':usuario_id' => $data['usuario_id'],
            ':id' => $data['id']
        ]);
    }

    public function excluir(int $id): bool {
        $sql = "DELETE FROM atletas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
} 