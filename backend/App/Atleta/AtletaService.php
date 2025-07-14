<?php
namespace App\Atleta;

use App\Usuario\Usuario;
use App\Usuario\UsuarioRepository;

class AtletaService {
    private AtletaRepositoryInterface $repo;
    private UsuarioRepository $usuarioRepo;
    public function __construct(AtletaRepositoryInterface $repo, UsuarioRepository $usuarioRepo) {
        $this->repo = $repo;
        $this->usuarioRepo = $usuarioRepo;
    }

    public function cadastrar(array $data): int {
        error_log('AtletaService: Iniciando cadastro');
        // Cria usuário antes do atleta
        $senhaPadrao = '123456';
        $senhaHash = password_hash($senhaPadrao, PASSWORD_DEFAULT);
        error_log('AtletaService: Antes de criar Usuario');
        $usuario = new \App\Usuario\Usuario(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $senhaHash
        );
        error_log('AtletaService: Usuario instanciado');
        $this->usuarioRepo->save($usuario);
        error_log('AtletaService: Usuario salvo');
        $usuarioId = $usuario->getId();
        error_log('AtletaService: usuarioId=' . $usuarioId);

        error_log('AtletaService: Antes de criar Atleta');
        $atleta = new Atleta(
            $data['nome'] ?? '',
            $data['data_nascimento'] ?? '',
            $data['posicao'] ?? '',
            $data['email'] ?? '',
            $data['telefone'] ?? '',
            $data['categoria'] ?? '',
            $data['acesso'] ?? '',
            $data['senha'] ?? '',
            $data['treinador_id'] ? (int)$data['treinador_id'] : null,
            $usuarioId
        );
        error_log('AtletaService: Atleta instanciado');
        $id = $this->repo->save($atleta);
        error_log('AtletaService: Atleta salvo, id=' . $id);
        return $id;
    }

    public function listar(): array {
        return $this->repo->listarTodos();
    }

    public function editar(array $data): bool {
        return $this->repo->editar($data);
    }

    public function excluir(int $id): bool {
        return $this->repo->excluir($id);
    }
} 