<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../controllers/UsuariosController.php';

use Controllers\UsuariosController;
$ctrl      = new UsuariosController();
$usuarios  = $ctrl->listar();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #07272D;
      color: #FFF;
      min-height: 100vh;
      display: block;
      padding: 10px;
      margin: 0 auto;
      width: 80%;
    }
    .top-bar {
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
    }
    .box-top-bar {
      background: #5d5d5d;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 7px 0;
    }
    .home-icon, .logout-icon {
      width: 46px;
      height: 46px;
      font-size: 26px;
      border-radius: 18px;
      background: #f5f5f7;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all .25s;
      text-decoration: none;
    }
    .home-icon { color: rgb(11,104,39); }
    .logout-icon { color: #dc3545; }
    .home-icon:hover, .logout-icon:hover {
      transform: scale(1.08);
      background: #e8e8ea;
    }
    .logo-fixed { width: 117px; }
    label { font-weight: 500; color: #dddddd; }
    .table-responsive { white-space: nowrap; }
  </style>
</head>

<body>

<!-- TOP-BAR -->
<div class="top-bar">
  <div class="box-top-bar">
    <a href="/psa-cbg/" class="home-icon" title="Home"><i class="fas fa-home"></i></a>
    <img src="https://athleticmap.com/images/logo-atm.png" class="logo-fixed" alt="ATM">
    <a href="/psa-cbg/controllers/LogoutController.php" class="logout-icon" title="Sair"><i class="fas fa-power-off"></i></a>
  </div>
</div>

<div class="container mt-5 pt-5">
  <h3 class="text-center mb-4">Usuários do Sistema</h3>
  <?php if (isset($_GET['erro']) && $_GET['erro'] === 'usuario_vinculado'): ?>
    <div class="alert alert-warning text-center">
      ❌ Não é possível excluir este usuário. Ele está vinculado a um atleta no sistema.
    </div>
  <?php endif; ?>

  <?php if ($msg): ?>
    <div class="alert alert-<?= $alert ?>"><?= $msg ?></div>
  <?php endif; ?>

  <a href="/psa-cbg/views/usuario_form.php"
     class="btn btn-primary mb-3">Cadastrar Novo Usuário</a>

  <?php if ($usuarios): ?>
    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle">
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
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nome']) ?></td>
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
    </div>
  <?php else: ?>
    <p class="text-center">Nenhum usuário cadastrado.</p>
  <?php endif; ?>

  <a href="/psa-cbg/index.php" class="btn btn-light mt-3">Voltar</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
