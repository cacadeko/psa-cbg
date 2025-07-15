<?php
namespace App\Jogo;

class JogoRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Jogo $jogo): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO jogos (data_jogo, local_jogo, adversario, campeonato, resultado, observacoes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $jogo->getData(),
            $jogo->getLocal(),
            $jogo->getAdversario(),
            $jogo->getCampeonato(),
            $jogo->getResultado(),
            $jogo->getObservacoes()
        ]);
        $jogo->setId((int)$this->pdo->lastInsertId());
    }

    public function getById(int $id): ?Jogo
    {
        $stmt = $this->pdo->prepare('SELECT * FROM jogos WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new Jogo(
            (int)$row['id'],
            $row['data_jogo'],
            $row['local_jogo'],
            $row['adversario'],
            $row['campeonato'] ?? null,
            $row['resultado'],
            $row['observacoes']
        );
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM jogos ORDER BY data_jogo DESC');
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $jogos = [];
        foreach ($rows as $row) {
            if (!empty($row['id'])) {
                $jogos[] = [
                    'id' => (int)$row['id'],
                    'data_jogo' => $row['data_jogo'],
                    'adversario' => $row['adversario'],
                    'local_jogo' => $row['local_jogo'],
                    'campeonato' => $row['campeonato'] ?? null,
                    'resultado' => $row['resultado'],
                    'observacoes' => $row['observacoes']
                ];
            }
        }
        return $jogos;
    }

    public function update(Jogo $jogo): void
    {
        $stmt = $this->pdo->prepare('UPDATE jogos SET data_jogo = ?, local_jogo = ?, adversario = ?, campeonato = ?, resultado = ?, observacoes = ? WHERE id = ?');
        $stmt->execute([
            $jogo->getData(),
            $jogo->getLocal(),
            $jogo->getAdversario(),
            $jogo->getCampeonato(),
            $jogo->getResultado(),
            $jogo->getObservacoes(),
            $jogo->getId()
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM jogos WHERE id = ?');
        $stmt->execute([$id]);
    }
} 