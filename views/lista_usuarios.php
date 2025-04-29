<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }

/* carrega o controlador manualmente (sem autoloader) */
require_once __DIR__ . '/../controllers/UsuariosController.php';

use Controllers\UsuariosController;
$ctrl      = new UsuariosController();
$usuarios  = $ctrl->listar();


/***** mensagens de feedback *****/
$msg   = '';
$alert = '';
if (isset($_GET['success'])) { $msg = 'Operação realizada com sucesso!'; $alert='success'; }
if (isset($_GET['error']))   { $msg = 'Ocorreu um erro.';                $alert='danger';  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-4">

    <h2 class="mb-3 text-center">Usuários do Sistema</h2>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $alert ?>"><?= $msg ?></div>
    <?php endif; ?>

    <a href="/psa-cbg/views/usuario_form.php"
       class="btn btn-primary mb-3">Cadastrar Novo Usuário</a>

    <?php if ($usuarios): ?>
        <table class="table table-striped table-dark align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Nível de Acesso</th>
                    <th class="text-center" style="width:150px;">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id']               ?></td>
                    <td><?= htmlspecialchars($u['nome'])  ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['nivel'] === 'admin' ? 'Admin' : ucfirst($u['nivel']) ?></td>
                    <td class="text-center">
                        <a href="/psa-cbg/views/editar_usuario_form.php?id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-warning">Editar</a>
                        <a href="/psa-cbg/controllers/routerExcluirUsuario.php?id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Confirma excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Nenhum usuário cadastrado.</p>
    <?php endif; ?>

    <a href="/psa-cbg/index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>
</body>
</html>
