<?php
namespace App\Atleta;

use PDO;

interface AtletaRepositoryInterface {
    public function findById(int $id): ?Atleta;
    public function save(Atleta $atleta): void;
}

class AtletaRepository implements AtletaRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findById(int $id): ?Atleta {
        // Implementação mínima
        return null;
    }

    public function save(Atleta $atleta): void {
        // Implementação mínima
    }
} 