<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Formulário PSE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
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
        .home-icon { color: rgb(11, 104, 39); }
        .logout-icon { color: #dc3545; }
        .logout-icon:hover, .home-icon:hover {
            transform: scale(1.08);
            background: #e8e8ea;
        }
        .logo-fixed { width: 117px; }
        label { font-weight: 500; color: #dddddd; }

        .form-check-input {
            width: 1.5em;
            height: 1.5em;
        }

        .linha_sono {
            display: block;
            width: 100%;
            padding: 25px;
        }

        #linha_sono_0  { background: #bd2b2b;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_1  { background: #cb6767;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_2  { background: #96408b;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_3  { background: #b168a0;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_4  { background: #817fb1;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_5  { background: #3279a7;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_6  { background: #4099bb;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_7  { background: #65c0db;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_8  { background: #8cb435;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_9  { background: #aacc53;  font-size: 20px; font-weight: bold; color: #ffffff; }
        #linha_sono_10 { background: #999999;  font-size: 20px; font-weight: bold; color: #ffffff; }
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

<div class="container mt-5 pt-4">
    <h1 class="text-center">Controle de carga</h1>
    <h2 class="text-center">Pós-Técnico</h2><br><br>

    <form method="POST" action="/psa-cbg/controllers/routerPsaTreino.php">

        <div class="mb-3">
            <h4>Atleta:</h4>
            <input type="text" class="form-control" name="atleta_nome" value="<?= htmlspecialchars($_SESSION['usuario']); ?>" readonly>
            <input type="hidden" name="atleta_id" value="<?= $_SESSION['usuario_id']; ?>">
        </div>

        <div class="mb-3">
            <h4>Grupo de Treino</h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="grupo" value="G1" required>
                <label class="form-check-label">G1</label>
            </div>
            <br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="grupo" value="G2" required>
                <label class="form-check-label">G2</label>
            </div>
            <br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="grupo" value="G3" required>
                <label class="form-check-label">G3</label>
            </div>
        </div>
        <br><br>
        <div class="mb-3">
            <h4>QUAL FOI A SENSAÇÃO DE CANSAÇO FÍSICO DO TREINO/JOGO DE HOJE?</h4>
            <?php 
            $x = 10; $y = 0;
            $intensidade_labels = [
                '0 - Repouso', '1 - Muito, muito leve.', '2 - leve',
                '3 - Médio', '4 - Um pouco pesado', '5 - Pesado',
                '6 - Meio pesado', '7 - Muito pesado', '8 - Extremamente pesado',
                '9 - Máximo', '10 - Esforço máximo'
            ];
            foreach ($intensidade_labels as $valor): ?>
                <span class="linha_sono" id="linha_sono_<?php echo $x; ?>">
                    <input class="form-check-input" type="radio" name="nota_pse" value="<?php echo $y; ?>" required> <?php echo $valor; ?>
                </span>
            <?php $x--; $y++; endforeach; ?>
        </div>
        <br><br>
        <div class="mb-3">
            <h4>Quanto tempo treinou?</h4>
            <input type="text" class="form-control" name="tempo_treino" placeholder="Ex: 1h30min" required>
        </div>
        <br><br>
        <div class="mb-3">
            <h4>Qual turno de treino?</h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="turno" value="Manhã" required>
                <label class="form-check-label">Manhã</label>
            </div><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="turno" value="Tarde" required>
                <label class="form-check-label">Tarde</label>
            </div>
        </div>
        <br><br>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
        <a href="/psa-cbg/index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
