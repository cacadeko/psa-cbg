<?php
namespace App\Usuario;

class Usuario
{
    private int $id;
    private string $nome;
    private string $email;
    private string $senhaHash;

    public function __construct(string $nome, string $email, string $senhaHash)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senhaHash = $senhaHash;
    }

    public function getId(): ?int { return $this->id ?? null; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }
    public function getSenhaHash(): string { return $this->senhaHash; }
    public function setId(int $id): void { $this->id = $id; }
} 