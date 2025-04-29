<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /index.php');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Lista de PSR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #07272D;
            color: white;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        .logo {
            width: 150px;
            display: block;
            margin: 0 auto 20px;
        }
        .table-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://athleticmap.com/images/logo-atm.png" alt="Athletic Map Logo" class="logo">
        <h2 class="text-center">Lista de PSR</h2>

        <div class="container mt-4">
            <h2 class="text-center">Filtrar Registros de PSR</h2>
            <form method="GET" action="lista_sono.php">
                <div class="mb-3">
                    <label class="form-label">Filtrar por Data</label>
                    <input type="date" class="form-control" name="data_filtro" value="<?php echo $data_filtro; ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>PSR</th>
                        <th>Sono</th>
                        <th>Acordou a noite</th>
                        <th>Hora que dormiu</th>
                        <th>Hora que acordou</th>
                        <th>Dor no corpo</th>
                        <th>Local da dor</th>
<th>Mapa da dor</th>
                        <th>Intensidade da dor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sono as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['atleta_nome']); ?></td>
                            <td><?php echo htmlspecialchars($registro['avaliacao_psr']); ?></td>
                            <td><?php echo htmlspecialchars($registro['avaliacao_sono']); ?></td>
                            <td><?php echo htmlspecialchars($registro['acordou_durante_a_noite']); ?></td>
                            <td><?php echo htmlspecialchars($registro['hora_dormir']); ?></td>
                            <td><?php echo htmlspecialchars($registro['hora_acordar']); ?></td>
                            <td><?php echo htmlspecialchars($registro['dor_corpo']); ?></td>
                            <td><?php echo htmlspecialchars($registro['local_dor']); ?></td>
<td>
    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalMapa<?php echo $registro['id']; ?>">
        Ver Mapa
    </button>

    <!-- Modal -->
    <div class="modal fade" id="modalMapa<?php echo $registro['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $registro['id']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel<?php echo $registro['id']; ?>">Mapa da Dor - <?php echo $registro['atleta_nome']; ?></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body text-center">
            <img src="https://athleticmap.com/images/mapador.png" alt="Mapa da dor" class="img-fluid">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
</td>

                            <td><?php echo htmlspecialchars($registro['intensidade_dor']); ?></td>
                            <td>
                                <a href="/psa-cbg/controllers/routerExcluirSono.php?id=<?php echo $registro['id']; ?>" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Tem certeza que deseja excluir este registro de sono?');">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                
<tr style="background-color: rgba(255,255,255,0.1); font-weight: bold;">
    <td>MÉDIA</td>
    <td><?php echo number_format(array_sum(array_column($sono, "avaliacao_psr")) / count($sono), 2, ",", "."); ?></td>
    <td><?php echo number_format(array_sum(array_column($sono, "avaliacao_sono")) / count($sono), 2, ",", "."); ?></td>
    <td colspan="3">—</td>
    <td>—</td>
    <td>—</td>
    <td>—</td>
    <td><?php echo number_format(array_sum(array_column($sono, "intensidade_dor")) / count($sono), 2, ",", "."); ?></td>
    <td></td>
</tr>
</tbody>

            </table>
        </div>
        <a href="/psa-cbg/index.php" class="btn btn-warning btn-sm">Voltar para o menu</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>