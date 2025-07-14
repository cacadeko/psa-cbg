<?php
namespace App\Treinador;

use App\Usuario\Usuario;
use App\Usuario\UsuarioRepository;

class TreinadorService
{
    private TreinadorRepository $repository;
    private UsuarioRepository $usuarioRepo;

    public function __construct(TreinadorRepository $repository, UsuarioRepository $usuarioRepo)
    {
        $this->repository = $repository;
        $this->usuarioRepo = $usuarioRepo;
    }

    public function cadastrar(array $data): int
    {
        // Log para debug
        error_log('Dados recebidos no cadastrar treinador: ' . json_encode($data));
        
        // Cria usuário antes do treinador
        $senha = $data['senha'] ?? '123456';
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $usuario = new Usuario(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $senhaHash,
            $data['nivel'] ?? 'treinador',
            $data['ativo'] ?? true
        );
        $this->usuarioRepo->save($usuario);
        $usuarioId = $usuario->getId();

        $treinador = new Treinador(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $data['telefone'] ?? '',
            $data['especialidade'] ?? '',
            $data['data_contratacao'] ?? '',
            $data['observacoes'] ?? '',
            $usuarioId,
            $data['nivel'] ?? 'treinador',
            $data['ativo'] ?? true
        );
        
        return $this->repository->save($treinador);
    }

    public function listar(): array
    {
        return $this->repository->listarTodos();
    }

    public function obterTreinador(int $id): ?Treinador
    {
        return $this->repository->getById($id);
    }

    public function editar(array $data): bool
    {
        $ok = $this->repository->editar($data);
        if ($ok && !empty($data['usuario_id'])) {
            // Atualizar dados do usuário se fornecidos
            $usuarioData = [];
            if (!empty($data['nome'])) $usuarioData['nome'] = $data['nome'];
            if (!empty($data['email'])) $usuarioData['email'] = $data['email'];
            if (!empty($data['nivel'])) $usuarioData['nivel'] = $data['nivel'];
            if (isset($data['ativo'])) $usuarioData['ativo'] = $data['ativo'];
            
            if (!empty($usuarioData)) {
                $this->usuarioRepo->atualizar($data['usuario_id'], $usuarioData);
            }
            
            // Atualizar senha se fornecida
            if (!empty($data['senha'])) {
                $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
                $this->usuarioRepo->atualizarSenha($data['usuario_id'], $senhaHash);
            }
        }
        return $ok;
    }

    public function excluir(int $id): bool
    {
        return $this->repository->excluir($id);
    }
} 