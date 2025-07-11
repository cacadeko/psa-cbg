<?php
namespace App\Minutagem;

class Minutagem
{
    private int $id;
    private int $atletaId;
    private string $data;
    private int $minutos;
    private string $atividade;

    public function __construct(int $id, int $atletaId, string $data, int $minutos, string $atividade)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->data = $data;
        $this->minutos = $minutos;
        $this->atividade = $atividade;
    }

    public function getId(): int { return $this->id; }
    public function getAtletaId(): int { return $this->atletaId; }
    public function setAtletaId(int $atletaId): void { $this->atletaId = $atletaId; }
    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }
    public function getMinutos(): int { return $this->minutos; }
    public function setMinutos(int $minutos): void { $this->minutos = $minutos; }
    public function getAtividade(): string { return $this->atividade; }
    public function setAtividade(string $atividade): void { $this->atividade = $atividade; }
} 