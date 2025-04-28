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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Athletic Map - Relatório de Presença</title>
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
        <div class="container mt-4">
            <h2 class="text-center">Relatório de Presença</h2>
            
            <!-- Filtros de Mês e Semana -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Filtrar por Mês:</label>
                        <input type="month" class="form-control" name="mes_filtro" value="<?php echo isset($_GET['mes_filtro']) ? $_GET['mes_filtro'] : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Filtrar por Semana:</label>
                        <input type="week" class="form-control" name="semana_filtro" value="<?php echo isset($_GET['semana_filtro']) ? $_GET['semana_filtro'] : ''; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
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
                        
                        if (isset($_GET['mes_filtro']) && !empty($_GET['mes_filtro'])) {
                            $mes_filtro = $_GET['mes_filtro'] . '-01';
                            $whereClauses[] = "(DATE(qs.created_at) >= '$mes_filtro' AND DATE(qs.created_at) < DATE_ADD('$mes_filtro', INTERVAL 1 MONTH)) 
                                                OR (DATE(pse.created_at) >= '$mes_filtro' AND DATE(pse.created_at) < DATE_ADD('$mes_filtro', INTERVAL 1 MONTH))";
                        }
                        
                        if (isset($_GET['semana_filtro']) && !empty($_GET['semana_filtro'])) {
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
                                <td><?php echo htmlspecialchars($atleta); ?></td>
                                <?php for ($i = 1; $i <= 7; $i++): ?>
                                    <td class="text-center"><?php echo isset($dias[$i]) ? $dias[$i] : '-'; ?></td>
                                <?php endfor; ?>
                                <td class="text-center"><?php echo $faltas; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="/pre-treino-rfc/index.php" class="btn btn-secondary w-100 mt-2">Voltar para o menu</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
