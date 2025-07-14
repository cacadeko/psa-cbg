<?php
namespace App\Treinador;

class TreinadorRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Treinador $treinador): int
    {
        if ($treinador->getId() > 0) {
            // Update
            $stmt = $this->pdo->prepare("
                UPDATE treinadores 
                SET nome = ?, email = ?, telefone = ?, especialidade = ?, data_contratacao = ?, observacoes = ?, usuario_id = ?, nivel = ?, ativo = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $treinador->getNome(),
                $treinador->getEmail(),
                $treinador->getTelefone(),
                $treinador->getEspecialidade(),
                $treinador->getDataContratacao(),
                $treinador->getObservacoes(),
                $treinador->getUsuarioId(),
                $treinador->getNivel(),
                $treinador->getAtivo(),
                $treinador->getId()
            ]);
            
            return $treinador->getId();
        } else {
            // Insert
            $stmt = $this->pdo->prepare("
                INSERT INTO treinadores (nome, email, telefone, especialidade, data_contratacao, observacoes, usuario_id, nivel, ativo, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            
            $stmt->execute([
                $treinador->getNome(),
                $treinador->getEmail(),
                $treinador->getTelefone(),
                $treinador->getEspecialidade(),
                $treinador->getDataContratacao(),
                $treinador->getObservacoes(),
                $treinador->getUsuarioId(),
                $treinador->getNivel(),
                $treinador->getAtivo()
            ]);
            
            $id = $this->pdo->lastInsertId();
            $treinador->setId($id);
            return $id;
        }
    }

    public function getById(int $id): ?Treinador
    {
        $stmt = $this->pdo->prepare("SELECT * FROM treinadores WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return new Treinador(
            $data['nome'],
            $data['email'],
            $data['telefone'],
            $data['especialidade'],
            $data['data_contratacao'],
            $data['observacoes'],
            $data['usuario_id'],
            $data['nivel'] ?? 'treinador',
            $data['ativo'] ?? true,
            $data['id']
        );
    }

    public function listarTodos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM treinadores ORDER BY nome");
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $treinadores = [];
        foreach ($data as $row) {
            $treinadores[] = new Treinador(
                $row['nome'],
                $row['email'],
                $row['telefone'],
                $row['especialidade'],
                $row['data_contratacao'],
                $row['observacoes'],
                $row['usuario_id'],
                $row['nivel'] ?? 'treinador',
                $row['ativo'] ?? true,
                $row['id']
            );
        }
        
        return $treinadores;
    }

    public function editar(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE treinadores 
            SET nome = ?, email = ?, telefone = ?, especialidade = ?, data_contratacao = ?, observacoes = ?, nivel = ?, ativo = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['nome'],
            $data['email'],
            $data['telefone'] ?? null,
            $data['especialidade'] ?? null,
            $data['data_contratacao'] ?? null,
            $data['observacoes'] ?? null,
            $data['nivel'] ?? 'treinador',
            $data['ativo'] ?? true,
            $data['id']
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM treinadores WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 