<?php
namespace App\PFE;

class PFE
{
    private int $id;
    private int $atletaId;
    private string $data;
    private int $valor;
    private ?string $observacoes;

    public function __construct(int $id, int $atletaId, string $data, int $valor, ?string $observacoes = null)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->data = $data;
        $this->valor = $valor;
        $this->observacoes = $observacoes;
    }

    public function getId(): int { return $this->id; }
    public function getAtletaId(): int { return $this->atletaId; }
    public function setAtletaId(int $atletaId): void { $this->atletaId = $atletaId; }
    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }
    public function getValor(): int { return $this->valor; }
    public function setValor(int $valor): void { $this->valor = $valor; }
    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $observacoes): void { $this->observacoes = $observacoes; }
} 