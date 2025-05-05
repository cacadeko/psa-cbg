<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

require_once '../controllers/PSATreinoController.php';
use Controllers\PSATreinoController;

$ctrl = new PSATreinoController();
$data_filtro = $_GET['data_filtro'] ?? null;
$registros = $ctrl->listarTodos($data_filtro);

$msg   = '';
$alert = '';
if (isset($_GET['success'])) { $msg = 'Registro salvo com sucesso!'; $alert='success'; }
if (isset($_GET['error']))   { $msg = 'Ocorreu um erro.';            $alert='danger';  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de PSATreino</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #07272D;
      color: #FFF;
      min-height: 100vh;
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
  <h3 class="text-center mb-4">Lista de PSATreino</h3>

  <?php if ($msg): ?>
    <div class="alert alert-<?= $alert ?>"><?= $msg ?></div>
  <?php endif; ?>

  <!-- Filtro -->
  <form method="GET" action="lista_psa_treino.php" class="mb-4">
    <label class="form-label">Filtrar por Data</label>
    <input type="date" class="form-control mb-2" name="data_filtro" value="<?= htmlspecialchars($data_filtro ?? '') ?>">
    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
  </form>

  <!-- Tabela -->
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Atleta</th>
          <th>Descrição</th>
          <th>Nota PSE</th>
          <th>Tempo Treino</th>
          <th>Turno</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($registros): foreach ($registros as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['atleta_nome']) ?></td>
            <td><?= htmlspecialchars($r['descricao']) ?></td>
            <td><?= htmlspecialchars($r['nota_pse']) ?></td>
            <td><?= htmlspecialchars($r['tempo_treino']) ?></td>
            <td><?= htmlspecialchars($r['turno']) ?></td>
            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center">Nenhum registro encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-end">
    <a href="/psa-cbg/index.php" class="btn btn-light mt-3">Voltar</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
