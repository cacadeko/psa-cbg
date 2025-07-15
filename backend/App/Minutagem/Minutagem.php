<?php
namespace App\Minutagem;

class Minutagem
{
    private int $id;
    private int $atletaId;
    private int $jogoId;
    private bool $titular;
    private ?int $minutoEntrou;
    private ?int $minutoSaiu;
    private int $minutosPrimeiroTempo;
    private int $minutosSegundoTempo;
    private ?string $observacoes;

    public function __construct(int $id, int $atletaId, int $jogoId, bool $titular, ?int $minutoEntrou, ?int $minutoSaiu, int $minutosPrimeiroTempo, int $minutosSegundoTempo, ?string $observacoes = null)
    {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->jogoId = $jogoId;
        $this->titular = $titular;
        $this->minutoEntrou = $minutoEntrou;
        $this->minutoSaiu = $minutoSaiu;
        $this->minutosPrimeiroTempo = $minutosPrimeiroTempo;
        $this->minutosSegundoTempo = $minutosSegundoTempo;
        $this->observacoes = $observacoes;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    
    public function getAtletaId(): int { return $this->atletaId; }
    public function setAtletaId(int $atletaId): void { $this->atletaId = $atletaId; }
    
    public function getJogoId(): int { return $this->jogoId; }
    public function setJogoId(int $jogoId): void { $this->jogoId = $jogoId; }
    
    public function getTitular(): bool { return $this->titular; }
    public function setTitular(bool $titular): void { $this->titular = $titular; }
    
    public function getMinutoEntrou(): ?int { return $this->minutoEntrou; }
    public function setMinutoEntrou(?int $minutoEntrou): void { $this->minutoEntrou = $minutoEntrou; }
    
    public function getMinutoSaiu(): ?int { return $this->minutoSaiu; }
    public function setMinutoSaiu(?int $minutoSaiu): void { $this->minutoSaiu = $minutoSaiu; }
    
    public function getMinutosPrimeiroTempo(): int { return $this->minutosPrimeiroTempo; }
    public function setMinutosPrimeiroTempo(int $minutosPrimeiroTempo): void { $this->minutosPrimeiroTempo = $minutosPrimeiroTempo; }
    
    public function getMinutosSegundoTempo(): int { return $this->minutosSegundoTempo; }
    public function setMinutosSegundoTempo(int $minutosSegundoTempo): void { $this->minutosSegundoTempo = $minutosSegundoTempo; }
    
    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $observacoes): void { $this->observacoes = $observacoes; }
} 