<?php
namespace App\Jogo;

class Jogo
{
    private int $id;
    private string $data;
    private string $local;
    private string $adversario;
    private ?string $resultado;
    private ?string $observacoes;

    public function __construct(int $id, string $data, string $local, string $adversario, ?string $resultado = null, ?string $observacoes = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->local = $local;
        $this->adversario = $adversario;
        $this->resultado = $resultado;
        $this->observacoes = $observacoes;
    }

    public function getId(): int { return $this->id; }
    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }
    public function getLocal(): string { return $this->local; }
    public function setLocal(string $local): void { $this->local = $local; }
    public function getAdversario(): string { return $this->adversario; }
    public function setAdversario(string $adversario): void { $this->adversario = $adversario; }
    public function getResultado(): ?string { return $this->resultado; }
    public function setResultado(?string $resultado): void { $this->resultado = $resultado; }
    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $observacoes): void { $this->observacoes = $observacoes; }
} 