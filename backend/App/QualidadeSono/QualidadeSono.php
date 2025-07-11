<?php
namespace App\QualidadeSono;

class QualidadeSono
{
    private int $id;
    private int $atletaId;
    private string $data;
    private int $qualidade;
    private ?string $observacoes;

    public function __construct(int $id, int $atletaId, string $data, int $qualidade, ?string $observacoes = null)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->data = $data;
        $this->qualidade = $qualidade;
        $this->observacoes = $observacoes;
    }

    public function getId(): int { return $this->id; }
    public function getAtletaId(): int { return $this->atletaId; }
    public function setAtletaId(int $atletaId): void { $this->atletaId = $atletaId; }
    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }
    public function getQualidade(): int { return $this->qualidade; }
    public function setQualidade(int $qualidade): void { $this->qualidade = $qualidade; }
    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $observacoes): void { $this->observacoes = $observacoes; }
} 