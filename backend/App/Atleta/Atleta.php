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
    private int $treinadorId;
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
        int $treinadorId,
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
        $this->treinadorId = $treinadorId;
        $this->usuarioId = $usuarioId;
    }

    // Getters e setters...
} 