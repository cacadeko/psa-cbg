<?php
use Config\Database;

require_once '../config/Database.php';
require_once '../models/QualidadeSono.php';
require_once '../models/PSE.php';
use Models\QualidadeSono;
use Models\PSE;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Athletic Map - Relatório de Presença</title>
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
  <h3 class="text-center mb-4">Relatório de Presença</h3>

  <!-- Filtros -->
  <form method="GET" class="mb-4">
    <div class="row">
      <div class="col-md-6">
        <label class="form-label">Filtrar por Mês:</label>
        <input type="month" class="form-control" name="mes_filtro" value="<?= htmlspecialchars($_GET['mes_filtro'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Filtrar por Semana:</label>
        <input type="week" class="form-control" name="semana_filtro" value="<?= htmlspecialchars($_GET['semana_filtro'] ?? '') ?>">
      </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-3">Filtrar</button>
  </form>

  <!-- Tabela -->
  <div class="table-responsive mt-4">
    <table class="table table-dark table-striped table-bordered">
      <thead>
        <tr>
          <th>Atleta</th>
          <th>Segunda</th>
          <th>Terça</th>
          <th>Quarta</th>
          <th>Quinta</th>
          <th>Sexta</th>
          <th>Sábado</th>
          <th>Domingo</th>
          <th>Faltas no Mês</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $conn = Database::getConnection();
        $query = "SELECT a.nome AS atleta_nome, DATE(qs.created_at) AS data_sono, DATE(pse.created_at) AS data_pse 
                  FROM atletas a 
                  LEFT JOIN qualidade_sono qs ON a.id = qs.atleta_id 
                  LEFT JOIN pse ON a.id = pse.atleta_id ";

        $whereClauses = [];

        if (!empty($_GET['mes_filtro'])) {
            $mes_filtro = $_GET['mes_filtro'] . '-01';
            $whereClauses[] = "(DATE(qs.created_at) >= '$mes_filtro' AND DATE(qs.created_at) < DATE_ADD('$mes_filtro', INTERVAL 1 MONTH))
                               OR (DATE(pse.created_at) >= '$mes_filtro' AND DATE(pse.created_at) < DATE_ADD('$mes_filtro', INTERVAL 1 MONTH))";
        }

        if (!empty($_GET['semana_filtro'])) {
            $semana_filtro = $_GET['semana_filtro'];
            $whereClauses[] = "(YEARWEEK(qs.created_at, 1) = YEARWEEK('$semana_filtro', 1))
                               OR (YEARWEEK(pse.created_at, 1) = YEARWEEK('$semana_filtro', 1))";
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $query .= " GROUP BY a.id, DATE(qs.created_at), DATE(pse.created_at) ";

        $stmt = $conn->query($query);
        $presencas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dados_atletas = [];
        foreach ($presencas as $presenca) {
            if ($presenca['data_sono'] && $presenca['data_pse'] && $presenca['data_sono'] == $presenca['data_pse']) {
                $dia_semana = date('N', strtotime($presenca['data_sono']));
                $dados_atletas[$presenca['atleta_nome']][$dia_semana] = '✔️';
            }
        }

        foreach ($dados_atletas as $atleta => $dias):
            $faltas = 0;
            for ($i = 1; $i <= 7; $i++) {
                if (!isset($dias[$i])) {
                    $faltas++;
                }
            }
        ?>
        <tr>
          <td><?= htmlspecialchars($atleta) ?></td>
          <?php for ($i = 1; $i <= 7; $i++): ?>
            <td class="text-center"><?= $dias[$i] ?? '-' ?></td>
          <?php endfor; ?>
          <td class="text-center"><?= $faltas ?></td>
        </tr>
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
