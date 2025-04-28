<?php
namespace Models;
require_once __DIR__ . '/../config/Database.php';
use Config\Database; use PDO;

class Jogo
{
    public static function create(array $d): bool
    {
        $sql = "INSERT INTO jogos
                (data_jogo, local_jogo, adversario, campeonato, criado_por)
                VALUES (:data,:local,:adv,:camp,:user)";
        return Database::getConnection()->prepare($sql)->execute($d);
    }

    public static function listarTodos(): array
    {
        return Database::getConnection()
               ->query("SELECT * FROM jogos ORDER BY data_jogo DESC")
               ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorUsuario(int $uid): array
    {
        $st = Database::getConnection()
              ->prepare("SELECT * FROM jogos WHERE criado_por=? ORDER BY data_jogo DESC");
        $st->execute([$uid]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $st = Database::getConnection()->prepare("SELECT * FROM jogos WHERE id=?");
        $st->execute([$id]); return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function update(int $id, array $d): bool
    {
        $d[':id']=$id;
        $sql = "UPDATE jogos SET data_jogo=:data, local_jogo=:local,
                adversario=:adv, campeonato=:camp WHERE id=:id";
        return Database::getConnection()->prepare($sql)->execute($d);
    }

    public static function delete(int $id): bool
    {
        return Database::getConnection()
               ->prepare("DELETE FROM jogos WHERE id=?")->execute([$id]);
    }
}
