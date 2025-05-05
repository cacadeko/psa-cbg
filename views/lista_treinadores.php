<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

require_once '../config/Database.php';
use Config\Database;

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query(
        "SELECT id,
                nome,
                email,
                telefone,
                especialidade,
                DATE_FORMAT(data_contratacao,'%d/%m/%Y') AS data_contratacao
         FROM treinadores
         ORDER BY id DESC"
    );
    $treinadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar treinadores: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Athletic Map – Lista de Treinadores</title>
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
  <h3 class="text-center mb-4">Lista de Treinadores</h3>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Especialidade</th>
          <th>Contratado em</th>
          <th class="text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($treinadores)): ?>
          <?php foreach ($treinadores as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t['nome']) ?></td>
              <td><?= htmlspecialchars($t['email']) ?></td>
              <td><?= htmlspecialchars($t['telefone']) ?></td>
              <td><?= htmlspecialchars($t['especialidade']) ?></td>
              <td><?= $t['data_contratacao'] ?></td>
              <td class="text-center">
                <a href="/psa-cbg/views/editar_treinador_form.php?id=<?= $t['id'] ?>"
                   class="btn btn-sm btn-primary">Editar</a>
                <a href="/psa-cbg/controllers/routerExcluirTreinador.php?id=<?= $t['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Excluir este treinador?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">Nenhum treinador encontrado.</td></tr>
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
