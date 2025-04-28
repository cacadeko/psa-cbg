<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}

require_once __DIR__ . '/../controllers/MinutagemController.php';
use Controllers\MinutagemController;

$ctrl       = new MinutagemController();
$registros  = $ctrl->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Minutagens</title>

    <!-- Bootstrap + FA -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{background:#07272D;color:#FFF;min-height:100vh;
             display:block;margin:0 auto;padding:10px;width:80%;}
        /* top-bar */
        .top-bar{position:fixed;left:0;top:0;width:100%;z-index:1000;}
        .box-top-bar{width:100%;background:#5d5d5d;display:flex;
                     justify-content:space-around;align-items:center;padding:7px 0;}
        .home-icon,.logout-icon{width:46px;height:46px;font-size:26px;border-radius:18px;
                                background:#f5f5f7;display:flex;justify-content:center;align-items:center;
                                transition:.25s;text-decoration:none}
        .home-icon{color:rgb(11,104,39);} .logout-icon{color:#dc3545;}
        .home-icon:hover,.logout-icon:hover{transform:scale(1.08);background:#e8e8ea;}
        .logo-fixed{width:117px;}
        /* container */
        .box{background:rgba(255,255,255,.08);backdrop-filter:blur(4px);
             border-radius:14px;padding:32px;width:100%;margin:60px 0 0 0;}
        label{font-weight:500;color:#dddddd;}
        .table-responsive{white-space:nowrap;}
    </style>
</head>
<body>

<!-- TOP-BAR -->
<div class="top-bar">
    <div class="box-top-bar">
        <a href="/pre-treino-rfc/" class="home-icon" title="Home"><i class="fas fa-home"></i></a>
        <img src="https://athleticmap.com/images/logo-atm.png" class="logo-fixed" alt="ATM">
        <a href="/pre-treino-rfc/controllers/LogoutController.php"
           class="logout-icon" title="Sair"><i class="fas fa-power-off"></i></a>
    </div>
</div>

<!-- LISTA -->
<div class="box">
    <h3 class="text-center mb-4">Minutagens Registradas</h3>

    <div class="table-responsive">
        <table class="table table-dark table-striped align-middle">
            <thead>
            <tr>
                <th>Data</th><th>Local</th><th>Atleta</th>
                <th>Titular</th><th>1ºT</th><th>2ºT</th>
                <th>Entrou</th><th>Saiu</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($registros): foreach ($registros as $m): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['data_jogo'])) ?></td>
                    <td><?= htmlspecialchars($m['local_jogo']) ?></td>
                    <td><?= htmlspecialchars($m['atleta']) ?></td>
                    <td><?= $m['titular'] ? 'Sim' : 'Não' ?></td>
                    <td><?= $m['minutos_primeiro_tempo'] ?></td>
                    <td><?= $m['minutos_segundo_tempo'] ?></td>
                    <td><?= $m['minuto_entrou'] ?? '-' ?></td>
                    <td><?= $m['minuto_saiu']   ?? '-' ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center">Nenhum registro encontrado.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <a href="/pre-treino-rfc/index.php" class="btn btn-light mt-3">Voltar</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
