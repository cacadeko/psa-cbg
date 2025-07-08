<?php
namespace Controllers;

/* ------------- inclui o Model manualmente ------------- */
require_once __DIR__ . '/../models/UsuariosModel.php';
use Models\UsuariosModel;

class UsuariosController
{
    /* LISTAR */
    public function listar(): array
    {
        return UsuariosModel::listar_todos();          // método estático no model
    }

    /* CRIAR */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Método inválido');

        $dados = [
            'nome'  => $_POST['nome']  ?? '',
            'email' => $_POST['email'] ?? '',
            'nivel' => $_POST['nivel'] ?? 'aluno',
            'senha' => password_hash($_POST['senha'] ?? '123456', PASSWORD_DEFAULT)
        ];
        UsuariosModel::adicionar_usuario($dados);

        header('Location: /psa-cbg/views/lista_usuarios.php?success=1');
        exit;
    }

    /* EDITAR */
    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Método inválido');

        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'nome'  => $_POST['nome'],
            'email' => $_POST['email'],
            'nivel' => $_POST['nivel']
        ];
        if (!empty($_POST['senha'])) {
            $data['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }
        UsuariosModel::update($id, $data);

        header('Location: /psa-cbg/views/lista_usuarios.php?edited=1');
        exit;
    }

    /* EXCLUIR */
    public function excluir(int $id): void
    {
        try {
            if (!\Models\UsuariosModel::delete($id)) {
                header('Location: /psa-cbg/views/lista_usuarios.php?erro=erro_exclusao');
                exit;
            }

            header('Location: /psa-cbg/views/lista_usuarios.php?deleted=1');
            exit;

        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: /psa-cbg/views/lista_usuarios.php?erro=usuario_vinculado');
                exit;
            }

            header('Location: /psa-cbg/views/lista_usuarios.php?erro=excecao');
            exit;
        }
    }

}
