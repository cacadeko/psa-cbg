<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /index.php');
    exit;
}

require_once '../config/Database.php';
require_once '../models/PSE.php';
require_once '../controllers/PSEController.php';

use Models\PSE;
use Controllers\PSEController;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Lista de Pós treino</title>
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
        <h2 class="text-center">Lista de Pós treino</h2>

        <div class="container mt-4">
            <h2 class="text-center">Filtrar</h2>
                <form method="GET" action="/pre-treino-rfc/views/lista_pse.php">
                    <label class="form-label">Filtrar por Data</label>
                    <input type="date" class="form-control" name="data_filtro" value="<?php echo isset($_GET['data_filtro']) ? $_GET['data_filtro'] : ''; ?>">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </form>
                <br><br>
                <!-- Adição da tabela para listar PSE -->
                <div class="table-responsive">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>PSE</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pseController = new PSEController();
                            $data_filtro = isset($_GET['data_filtro']) ? $_GET['data_filtro'] : null;
                            $registros = $pseController->listarTodos($data_filtro);
                            foreach ($registros as $registro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($registro['atleta_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($registro['nota_pse']); ?></td>
                                    <td><?php echo htmlspecialchars($registro['created_at']); ?></td>
                                    <td>
                                        <a href="/pre-treino-rfc/controllers/routerExcluirPSE.php?id=<?php echo $registro['id']; ?>" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir este registro de PSE?');">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        
                            <?php if (!empty($pse)): ?>
<tr style="background-color: rgba(255,255,255,0.1); font-weight: bold;">
    <td><strong>MÉDIA</strong></td>
    <td colspan="1">—</td>
    <td><?php echo number_format(array_sum(array_column($pse, "avaliacao_pse")) / count($pse), 2, ",", "."); ?></td>
    <td><?php echo number_format(array_sum(array_column($pse, "avaliacao_carga")) / count($pse), 2, ",", "."); ?></td>
    <td><?php echo number_format(array_sum(array_column($pse, "avaliacao_volume")) / count($pse), 2, ",", "."); ?></td>
    <td colspan="2">—</td>
</tr>
<?php endif; ?>
</tbody>

                    </table>
                </div>
            <a href="/pre-treino-rfc/index.php" class="btn btn-warning btn-sm">Voltar para o menu</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>