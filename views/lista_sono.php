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
                    <th>Nome</th>
                    <th>PSR</th>
                    <th>Sono</th>
                    <th>Acordou à Noite</th>
                    <th>Hora que Dormiu</th>
                    <th>Hora que Acordou</th>
                    <th>Dor no Corpo</th>
                    <th>Local da Dor</th>
                    <th>Mapa da Dor</th>
                    <th>Intensidade da Dor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sono as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['atleta_nome']); ?></td>
                    <td><?= htmlspecialchars($registro['avaliacao_psr']); ?></td>
                    <td><?= htmlspecialchars($registro['avaliacao_sono']); ?></td>
                    <td><?= htmlspecialchars($registro['acordou_durante_a_noite']); ?></td>
                    <td><?= htmlspecialchars($registro['hora_dormir']); ?></td>
                    <td><?= htmlspecialchars($registro['hora_acordar']); ?></td>
                    <td><?= htmlspecialchars($registro['dor_corpo']); ?></td>
                    <td><?= htmlspecialchars($registro['local_dor']); ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalMapa<?= $registro['id']; ?>">
                            Ver Mapa
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalMapa<?= $registro['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $registro['id']; ?>" aria-hidden="true">
                          <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content bg-dark text-light">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?= $registro['id']; ?>">Mapa da Dor - <?= htmlspecialchars($registro['atleta_nome']); ?></h5>
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
                    <td><?= htmlspecialchars($registro['intensidade_dor']); ?></td>
                    <td>
                        <a href="/psa-cbg/controllers/routerExcluirSono.php?id=<?= $registro['id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir este registro de sono?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <!-- Média -->
                <tr style="background-color: rgba(255,255,255,0.1); font-weight: bold;">
                    <td>MÉDIA</td>
                    <td><?= number_format(array_sum(array_column($sono, "avaliacao_psr")) / count($sono), 2, ",", "."); ?></td>
                    <td><?= number_format(array_sum(array_column($sono, "avaliacao_sono")) / count($sono), 2, ",", "."); ?></td>
                    <td colspan="6">—</td>
                    <td><?= number_format(array_sum(array_column($sono, "intensidade_dor")) / count($sono), 2, ",", "."); ?></td>
                    <td></td>
                </tr>

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
