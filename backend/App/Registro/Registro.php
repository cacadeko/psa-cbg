<?php
namespace App\Registro;

class Registro
{
    private int $id;
    private int $atletaId;
    private string $data;
    private string $descricao;

    public function __construct(int $id, int $atletaId, string $data, string $descricao)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->data = $data;
        $this->descricao = $descricao;
    }

    public function getId(): int { return $this->id; }
    public function getAtletaId(): int { return $this->atletaId; }
    public function setAtletaId(int $atletaId): void { $this->atletaId = $atletaId; }
    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }
    public function getDescricao(): string { return $this->descricao; }
    public function setDescricao(string $descricao): void { $this->descricao = $descricao; }
} 