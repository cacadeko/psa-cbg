<?php
namespace App\Minutagem;

class MinutagemRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Minutagem $registro): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO minutagem (atleta_id, jogo_id, titular, minuto_entrou, minuto_saiu, minutos_primeiro_tempo, minutos_segundo_tempo, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $registro->getAtletaId(),
            $registro->getJogoId(),
            $registro->getTitular(),
            $registro->getMinutoEntrou(),
            $registro->getMinutoSaiu(),
            $registro->getMinutosPrimeiroTempo(),
            $registro->getMinutosSegundoTempo(),
            $registro->getObservacoes()
        ]);
        $registro->setId((int)$this->pdo->lastInsertId());
    }

    public function getById(int $id): ?Minutagem
    {
        $stmt = $this->pdo->prepare('SELECT * FROM minutagem WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new Minutagem(
            (int)$row['id'],
            (int)$row['atleta_id'],
            (int)$row['jogo_id'],
            (bool)$row['titular'],
            $row['minuto_entrou'],
            $row['minuto_saiu'],
            (int)$row['minutos_primeiro_tempo'],
            (int)$row['minutos_segundo_tempo'],
            $row['observacoes']
        );
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT m.*, 
                   a.nome as atleta_nome,
                   j.data_jogo as jogo_data,
                   j.adversario as jogo_adversario,
                   j.local_jogo as jogo_local
            FROM minutagem m
            LEFT JOIN atletas a ON m.atleta_id = a.id
            LEFT JOIN jogos j ON m.jogo_id = j.id
            ORDER BY m.created_at DESC
        ');
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $minutagens = [];
        foreach ($rows as $row) {
            if (!empty($row['id'])) {
                $minutagens[] = [
                    'id' => (int)$row['id'],
                    'atleta_id' => (int)$row['atleta_id'],
                    'jogo_id' => (int)$row['jogo_id'],
                    'titular' => (bool)$row['titular'],
                    'minuto_entrou' => $row['minuto_entrou'],
                    'minuto_saiu' => $row['minuto_saiu'],
                    'minutos_primeiro_tempo' => (int)$row['minutos_primeiro_tempo'],
                    'minutos_segundo_tempo' => (int)$row['minutos_segundo_tempo'],
                    'minutos_total' => (int)$row['minutos_total'],
                    'observacoes' => $row['observacoes'],
                    'atleta_nome' => $row['atleta_nome'],
                    'jogo_data' => $row['jogo_data'],
                    'jogo_adversario' => $row['jogo_adversario'],
                    'jogo_local' => $row['jogo_local']
                ];
            }
        }
        return $minutagens;
    }

    public function update(Minutagem $registro): void
    {
        $stmt = $this->pdo->prepare('UPDATE minutagem SET atleta_id = ?, jogo_id = ?, titular = ?, minuto_entrou = ?, minuto_saiu = ?, minutos_primeiro_tempo = ?, minutos_segundo_tempo = ?, observacoes = ? WHERE id = ?');
        $stmt->execute([
            $registro->getAtletaId(),
            $registro->getJogoId(),
            $registro->getTitular(),
            $registro->getMinutoEntrou(),
            $registro->getMinutoSaiu(),
            $registro->getMinutosPrimeiroTempo(),
            $registro->getMinutosSegundoTempo(),
            $registro->getObservacoes(),
            $registro->getId()
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM minutagem WHERE id = ?');
        $stmt->execute([$id]);
    }
} 