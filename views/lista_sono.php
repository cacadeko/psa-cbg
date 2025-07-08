<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

require_once '../config/Database.php';
require_once '../models/QualidadeSono.php';
require_once '../controllers/SonoController.php';

use Models\QualidadeSono;
use Controllers\SonoController;

$sonoController = new SonoController();
$data_filtro = isset($_GET['data_filtro']) ? $_GET['data_filtro'] : null;
$sono = $sonoController->listarTodos($data_filtro);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Athletic Map - Lista de PSR</title>
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
        .top-bar { position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; }
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
    <h3 class="text-center mb-4">Lista de PSR</h3>

    <!-- Filtro de Data -->
    <div class="container mt-4">
        <h4 class="text-center">Filtrar Registros de PSR</h4>
        <form method="GET" action="lista_sono.php">
            <div class="mb-3">
                <label class="form-label">Filtrar por Data</label>
                <input type="date" class="form-control" name="data_filtro" value="<?= htmlspecialchars($data_filtro); ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </form>
    </div>

    <!-- Tabela -->
    <div class="table-responsive mt-4">
  <table class="table table-dark table-striped align-middle">
    <thead>
      <tr>
        <th>Data</th>
        <th>Atleta</th>
        <th>Recuperação</th>
        <th>Fadiga</th>
        <th>Sono</th>
        <th>Estresse</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($sono as $registro): ?>
      <tr>
        <td><?= date('d/m/Y', strtotime($registro['data_registro'])) ?></td>
        <td><?= htmlspecialchars($registro['nome']) ?></td>
        <td><?= $registro['avaliacao_recuperacao'] ?></td>
        <td><?= $registro['avaliacao_fadiga'] ?></td>
        <td><?= $registro['avaliacao_sono'] ?></td>
        <td><?= $registro['estresse_geral'] ?></td>
        <td>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalView<?= $registro['id'] ?>">
            <i class="fas fa-eye"></i>
          </button>
          <a href="/psa-cbg/controllers/routerExcluirSono.php?id=<?= $registro['id']; ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Deseja excluir este registro?');">
             <i class="fas fa-trash-alt"></i>
          </a>
        </td>
      </tr>

      <!-- Modal Detalhes -->
      <div class="modal fade" id="modalView<?= $registro['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $registro['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content bg-dark text-light">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel<?= $registro['id'] ?>">Detalhes do Registro</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <p><strong>Recuperação:</strong> <?= $registro['avaliacao_recuperacao'] ?></p>
              <p><strong>Fadiga:</strong> <?= $registro['avaliacao_fadiga'] ?></p>
              <p><strong>Qualidade do Sono:</strong> <?= $registro['avaliacao_sono'] ?></p>
              <p><strong>Dor Muscular:</strong> <?= $registro['avaliacao_dor'] ?></p>
              <p><strong>Estresse Geral:</strong> <?= $registro['estresse_geral'] ?></p>
              <p><strong>Estresse Humor:</strong> <?= $registro['estresse_umor'] ?></p>
              <p><strong>Período Menstrual:</strong> <?= $registro['periodo_menstrual'] ?></p>
              <p><strong>Pré-Menstrual:</strong> <?= $registro['periodo_premenstrual'] ?></p>
              <p><strong>Uso de Medicação:</strong> <?= $registro['uso_medicacao'] ?></p>
              <p><strong>Motivo Medicação:</strong> <?= $registro['motivo_medicacao'] ?></p>
              <p><strong>Outro Medicamento:</strong> <?= $registro['uso_medicacao_outro'] ?></p>
              <p><strong>Outro Motivo:</strong> <?= $registro['motivo_medicacao_outro'] ?></p>
              <p><strong>Local da Dor:</strong> <?= $registro['local_dor'] ?></p>
              <p><strong>Data Registro:</strong> <?= date('d/m/Y H:i', strtotime($registro['data_registro'])) ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
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
