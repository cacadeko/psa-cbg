<?php
namespace App\Atleta;

class Atleta
{
    private int $id;
    private string $nome;
    private string $dataNascimento;
    private string $posicao;
    private string $email;
    private ?string $telefone;
    private ?string $categoria;
    private ?string $acesso;
    private ?string $senha;
    private ?int $treinadorId;
    private ?int $usuarioId;

    public function __construct(
        string $nome,
        string $dataNascimento,
        string $posicao,
        string $email,
        ?string $telefone,
        ?string $categoria,
        ?string $acesso,
        ?string $senha,
        ?int $treinadorId,
        ?int $usuarioId = null
    ) {
        $this->nome = $nome;
        $this->dataNascimento = $dataNascimento;
        $this->posicao = $posicao;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->categoria = $categoria;
        $this->acesso = $acesso;
        $this->senha = $senha;
        $this->treinadorId = $treinadorId !== null ? (int)$treinadorId : null;
        $this->usuarioId = $usuarioId;
    }

    // Getters e setters...

    public function getTreinadorId(): ?int {
        return $this->treinadorId;
    }

    public function getNome(): string { return $this->nome; }
    public function getDataNascimento(): string { return $this->dataNascimento; }
    public function getPosicao(): string { return $this->posicao; }
    public function getEmail(): string { return $this->email; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getCategoria(): ?string { return $this->categoria; }
    public function getAcesso(): ?string { return $this->acesso; }
    public function getSenha(): ?string { return $this->senha; }
    public function getUsuarioId(): ?int { return $this->usuarioId; }
} 