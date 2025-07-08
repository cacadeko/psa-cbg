<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

require_once __DIR__ . '/../controllers/AtletaController.php';
use Controllers\AtletaController;

$atletaController = new AtletaController();
$atletas = $atletaController->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Athletic Map - Lista de Atletas</title>
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
  <h3 class="text-center mb-4">Lista de Atletas</h3>
<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <div class="alert alert-success text-center" role="alert">
    ✅ Atleta excluído com sucesso.
  </div>
<?php elseif (isset($_GET['erro'])): ?>
  <?php if ($_GET['erro'] === 'pfe_associado'): ?>
    <div class="alert alert-warning text-center" role="alert">
      ⚠️ Não é possível excluir o atleta. Existe um registro de <strong>PFE</strong> vinculado a ele.
    </div>
  <?php elseif ($_GET['erro'] === 'id_nao_informado'): ?>
    <div class="alert alert-danger text-center" role="alert">
      ❌ ID do atleta não foi informado.
    </div>
  <?php elseif ($_GET['erro'] === 'erro_exclusao'): ?>
    <div class="alert alert-danger text-center" role="alert">
      ❌ Erro ao excluir o atleta.
    </div>
  <?php elseif ($_GET['erro'] === 'excecao'): ?>
    <div class="alert alert-danger text-center" role="alert">
      ❌ Erro inesperado durante a exclusão.
    </div>
  <?php endif; ?>
<?php endif; ?>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Data de Nascimento</th>
          <th>Posição</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Categoria</th>
          <th class="text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($atletas): ?>
          <?php foreach ($atletas as $atleta): ?>
            <tr>
              <td><?= htmlspecialchars($atleta['nome']) ?></td>
              <td><?= htmlspecialchars($atleta['data_nascimento']) ?></td>
              <td><?= htmlspecialchars($atleta['posicao']) ?></td>
              <td><?= htmlspecialchars($atleta['email']) ?></td>
              <td><?= htmlspecialchars($atleta['telefone']) ?></td>
              <td><?= htmlspecialchars($atleta['categoria']) ?></td>
              <td class="text-center">
                <a href="/psa-cbg/views/editar_atleta_form.php?id=<?= $atleta['id'] ?>"
                   class="btn btn-warning btn-sm">Editar</a>
                <a href="/psa-cbg/controllers/routerExcluirAtleta.php?id=<?= $atleta['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Tem certeza que deseja excluir este atleta?');">
                   Excluir
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center">Nenhum atleta encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-end">
    <a href="/psa-cbg/index.php" class="btn btn-light mt-3">Voltar</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.style.display = 'none');
  }, 5000);
</script>

</body>
</html>
