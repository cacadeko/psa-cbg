<?php
/******************* SESSÃO & PROTEÇÃO *******************/
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

/******************* LISTA DE ATLETAS *******************/
require_once '../config/Database.php';
require_once '../models/Atleta.php';
use Models\Atleta;

$treinadorId = $_SESSION['usuario_id'];
$nivel       = $_SESSION['nivel'];                   // admin | treinador

$lista = ($nivel === 'admin')
       ? Atleta::listarTodos()
       : Atleta::listarPorTreinador($treinadorId);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Minutagem</title>

    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{
            background:#07272D;color:#FFF;min-height:100vh;
            display:block;margin:0 auto;padding:10px;width:80%;
        }
        /* top-bar iguais ao form de atleta */
        .top-bar{position:fixed;left:0;top:0;width:100%;z-index:1000;}
        .box-top-bar{width:100%;background:#5d5d5d;display:flex;
                     justify-content:space-around;align-items:center;padding:7px 0;}
        .home-icon,.logout-icon{width:46px;height:46px;font-size:26px;border-radius:18px;
                                background:#f5f5f7;display:flex;justify-content:center;align-items:center;
                                transition:.25s;text-decoration:none}
        .home-icon{color:rgb(11,104,39);}
        .logout-icon{color:#dc3545;}
        .logout-icon:hover,.home-icon:hover{transform:scale(1.08);background:#e8e8ea;}
        .logo-fixed{width:117px;}
        /* container igual */
        .box{background:rgba(255,255,255,.08);backdrop-filter:blur(4px);
             border-radius:14px;padding:32px 28px;width:100%;margin:60px 0 0 0;}
        label{font-weight:500;color:#dddddd;}
    </style>
</head>
<body>

<!-- TOP-BAR -->
<div class="top-bar">
    <div class="box-top-bar">
        <a href="/psa-cbg/" class="home-icon" title="Home"><i class="fas fa-home"></i></a>
        <img src="https://athleticmap.com/images/logo-atm.png" class="logo-fixed" alt="ATM">
        <a href="/psa-cbg/controllers/LogoutController.php" class="logout-icon" title="Sair">
            <i class="fas fa-power-off"></i></a>
    </div>
</div>

<!-- FORMULÁRIO -->
<div class="box">
    <h3 class="text-center mb-4">Cadastrar Minutagem</h3>

    <form action="/psa-cbg/controllers/routerMinutagem.php" method="POST">

        <!-- treina dor logado -->
        <input type="hidden" name="treinador_id" value="<?= $treinadorId ?>">

        <div class="row g-3 mb-3">
            <!-- Jogo -->
            <div class="mb-3">
            <label class="form-label">Jogo *</label>
            <select name="jogo_id" class="form-select" required>
                <option value="">Selecione…</option>
                <?php
                require_once '../models/Jogo.php';
                use Models\Jogo;
                $jogos = ($_SESSION['nivel']==='admin')
                        ? Jogo::listarTodos()
                        : Jogo::listarPorUsuario($treinadorId);
                foreach ($jogos as $j){
                    $txt = date('d/m/Y',strtotime($j['data_jogo'])) .' - '. $j['adversario'];
                    echo '<option value="'.$j['id'].'">'.htmlspecialchars($txt).'</option>';
                }
                ?>
            </select>
            </div>


            <div class="col-12">
                <label class="form-label">Atleta *</label>
                <select name="atleta_id" class="form-select" required>
                    <option value="" disabled selected>Selecione o atleta</option>
                    <?php foreach ($lista as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 form-check mb-2">
                <input class="form-check-input" type="checkbox" name="titular" id="chkTitular" checked>
                <label class="form-check-label" for="chkTitular">Titular</label>
            </div>

            <div class="col-md-6">
                <label class="form-label">Minuto que entrou</label>
                <input type="number" name="minuto_entrou" class="form-control" min="0" max="120">
            </div>

            <div class="col-md-6">
                <label class="form-label">Minuto que saiu</label>
                <input type="number" name="minuto_saiu" class="form-control" min="0" max="120">
            </div>

            <div class="col-md-6">
                <label class="form-label">Minutos 1º tempo *</label>
                <input type="number" name="minutos_primeiro_tempo" value="0" min="0" max="60" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Minutos 2º tempo *</label>
                <input type="number" name="minutos_segundo_tempo" value="0" min="0" max="60" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" rows="3" class="form-control"></textarea>
            </div>

        </div>

        <button class="btn btn-primary w-100">Salvar</button>
        <a href="/psa-cbg/index.php" class="btn btn-light w-100 mt-2">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
