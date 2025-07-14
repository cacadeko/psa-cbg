<?php
namespace App\Treinador;

class Treinador
{
    private int $id;
    private string $nome;
    private string $email;
    private ?string $telefone;
    private ?string $especialidade;
    private ?string $dataContratacao;
    private ?string $observacoes;
    private ?int $usuarioId;
    private string $nivel;
    private bool $ativo;

    public function __construct(
        string $nome,
        string $email,
        ?string $telefone = null,
        ?string $especialidade = null,
        ?string $dataContratacao = null,
        ?string $observacoes = null,
        ?int $usuarioId = null,
        string $nivel = 'treinador',
        bool $ativo = true,
        ?int $id = null
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->especialidade = $especialidade;
        $this->dataContratacao = $dataContratacao;
        $this->observacoes = $observacoes;
        $this->usuarioId = $usuarioId;
        $this->nivel = $nivel;
        $this->ativo = $ativo;
        $this->id = $id ?? 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(?string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function getEspecialidade(): ?string
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?string $especialidade): void
    {
        $this->especialidade = $especialidade;
    }

    public function getDataContratacao(): ?string
    {
        return $this->dataContratacao;
    }

    public function setDataContratacao(?string $dataContratacao): void
    {
        $this->dataContratacao = $dataContratacao;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function setObservacoes(?string $observacoes): void
    {
        $this->observacoes = $observacoes;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(?int $usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    public function getNivel(): string
    {
        return $this->nivel;
    }

    public function setNivel(string $nivel): void
    {
        $this->nivel = $nivel;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
    }
} 