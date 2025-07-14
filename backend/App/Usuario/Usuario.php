<?php
namespace App\Usuario;

class Usuario
{
    private int $id;
    private string $nome;
    private string $email;
    private string $senhaHash;
    private string $nivel;
    private bool $ativo;

    public function __construct(string $nome, string $email, string $senhaHash, string $nivel = 'treinador', bool $ativo = true)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senhaHash = $senhaHash;
        $this->nivel = $nivel;
        $this->ativo = $ativo;
    }

    public function getId(): ?int { return $this->id ?? null; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }
    public function getSenhaHash(): string { return $this->senhaHash; }
    public function getNivel(): string { return $this->nivel; }
    public function getAtivo(): bool { return $this->ativo; }
    
    public function setId(int $id): void { $this->id = $id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setSenhaHash(string $senhaHash): void { $this->senhaHash = $senhaHash; }
    public function setNivel(string $nivel): void { $this->nivel = $nivel; }
    public function setAtivo(bool $ativo): void { $this->ativo = $ativo; }
} 