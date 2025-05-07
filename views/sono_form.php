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
  <title>Cadastro de PSR</title>
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
    .home-icon { color: rgb(11, 104, 39); }
    .logout-icon { color: #dc3545; }
    .logout-icon:hover, .home-icon:hover {
      transform: scale(1.08);
      background: #e8e8ea;
    }
    .logo-fixed { width: 117px; }
    label { font-weight: 500; color: #dddddd; font-size: 19px; }
    .linha_sono {
      display: block;
      width: 100%;
      padding: 25px;
    }
    #linha_sono_0  { background: #bd2b2b; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_1  { background: #cb6767; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_2  { background: #96408b; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_3  { background: #b168a0; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_4  { background: #817fb1; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_5  { background: #3279a7; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_6  { background: #4099bb; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_7  { background: #65c0db; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_8  { background: #8cb435; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_9  { background: #aacc53; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_10 { background: #999999; color: #fff; font-size: 20px; font-weight: bold; }

    #linha_sono_cinco_5  { background: #bd2b2b; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_cinco_4  { background: #cb6767; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_cinco_3  { background: #b168a0; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_cinco_2  { background: #3279a7; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_cinco_1  { background: #65c0db; color: #fff; font-size: 20px; font-weight: bold; }
    #linha_sono_cinco_0  { background: #8cb435; color: #fff; font-size: 20px; font-weight: bold; }

    .form-check {
      min-height: 3.5rem !important;
    }
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

<!-- FORMULÁRIO -->
<form method="POST" action="/psa-cbg/controllers/routerSono.php" class="mt-5 pt-5">
<div class="container mt-4">
  <h1 class="text-center mb-4">Percepção Subjetiva de Repouso - Início da Manhã (ONLINE)</h1><br><br>
  <div class="mb-3">
    <h4>Atleta:</h4>
    <input type="text" name="atleta_nome" value="<?= htmlspecialchars($_SESSION['usuario']); ?>" readonly class="form-control">
    <input type="hidden" name="atleta_id" value="<?= $_SESSION['usuario_id']; ?>">
  </div>
  <br>
  <div class="mb-3">
    <h4>Como você se sente em relação à sua recuperação? *</h4>
    <br>
    <p>Escala de Qualidade Total de Recuperação (TQR)</p>
    <?php 
    $labels = [
      "0"=>"1 - Nada recuperado", "1"=>"2 - Extremamente mal recuperado", "2"=>"3 - Muito mal recuperado",
      "3"=>"4 - Mal recuperado", "4"=>"5 - Razoavelmente recuperado", "5"=>"6 - Bem recuperado",
      "6"=>"7 - Muito bem recuperado", "7"=>"8 - Extremamente bem recuperado", "8"=>"9 - Totalmente bem recuperado"
    ];
    foreach ($labels as $valor => $descricao): ?>
      <span id="linha_sono_<?= $valor ?>" class="linha_sono">
        <input class="form-check-input" type="radio" name="avaliacao_psr" value="<?= $valor ?>" required> <?= $descricao ?>
      </span>
    <?php endforeach; ?>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Bem-Estar (FADIGA)*</h4>
    <?php 
    $labels = [
      "0"=>"5 - Muito descansada", "1"=>"4 - Descansada", "2"=>"3 - Normal", "3"=>"2 - Mais cansada que o normal", 
       "4"=>"1 - Muito cansada"
    ];
    foreach ($labels as $valor => $descricao): ?>
      <span id="linha_sono_cinco_<?= $valor ?>" class="linha_sono">
        <input class="form-check-input" type="radio" name="avaliacao_sono" value="<?= $valor ?>" required> <?= $descricao ?>
      </span>
    <?php endforeach; ?>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>O quanto você se sente cansada? *</h4>
    <br>
    <?php 
    $labels = [
      "0"=>"5 - Nenhum pouco Fadigado", "1"=>"4 - Um pouco fadigado", "2"=>"3 - Moderadamente fadigado", "3"=>"2 - Muito fadigado", "4"=>"1 - Fadiga e Exaustão total - Nada sobrando"
      
    ];
    foreach ($labels as $valor => $descricao): ?>
      <span id="linha_sono_cinco_<?= $valor ?>" class="linha_sono">
        <input class="form-check-input" type="radio" name="avaliacao_psr" value="<?= $valor ?>" required> <?= $descricao ?>
      </span>
    <?php endforeach; ?>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Bem-Estar (QUALIDADE DO SONO)*</h4>
    <br>
    <?php 
    $labels = [
      "0"=>"5 - Sono tranquilo e revigorante", "1"=>"4 - Bom", "2"=>"3 - Dificuldade em adormecer", "3"=>"2 - Sono inquieto", "4"=>"1 - Insônia"
      
    ];
    foreach ($labels as $valor => $descricao): ?>
      <span id="linha_sono_cinco_<?= $valor ?>" class="linha_sono">
        <input class="form-check-input" type="radio" name="avaliacao_sono" value="<?= $valor ?>" required> <?= $descricao ?>
      </span>
    <?php endforeach; ?>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Quantas horas de sono? *</h4>
    <br>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="Menos que 6 horas" required>
        <label class="form-check-label">Menos que 6 horas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="Entre 6 e 7 horas" required>
        <label class="form-check-label">Entre 6 e 7 horas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="8 horas ou mais" required>
        <label class="form-check-label">8 horas ou mais</label>
    </div>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Demorou a dormir? Se sim, quanto tempo? *</h4>
    <br>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="Menos que 6 horas" required>
        <label class="form-check-label">Menos que 30 min</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="Entre 6 e 7 horas" required>
        <label class="form-check-label">Entre 30 min a 1h</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="horas_sono_intervalo" value="8 horas ou mais" required>
        <label class="form-check-label">Mais que 1h</label>
    </div>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Se teve sono inquieto, qual o motivo?</h4>
    <br>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Dor">
        <label class="form-check-label">Dor</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Sem motivo específico">
        <label class="form-check-label">Sem motivo específico</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Questões ambientais (ex: muito calor)">
        <label class="form-check-label">Questões ambientais (ex: muito calor)</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Pesadelos">
        <label class="form-check-label">Pesadelos</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Questões pessoais">
        <label class="form-check-label">Questões pessoais</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Fatores relacionados ao treinamento">
        <label class="form-check-label">Fatores relacionados ao treinamento</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Outro">
        <label class="form-check-label">Outro</label>
    </div>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Acordou Durante a Noite? Se sim, quantas vezes?</h4>
    <br>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="acordou_durante_a_noite" value="1" required> 1 vez
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="acordou_durante_a_noite" value="2" required> 2 a 4 vezes
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="acordou_durante_a_noite" value="3" required> 4 vezes ou mais
    </div>
  </div>
  <br><br>
  <div class="mb-3">
  <h4>Bem-Estar (DOR MUSCULAR GERAL)*</h4>
  <br>
  <span class="linha_sono" id="linha_sono_cinco_0">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="1 - Muito relaxada" required>
    5 – Sentindo-me ótima
  </span>
  <span class="linha_sono" id="linha_sono_cinco_1">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="2 - Relaxada" required>
    4 – Sentindo-me bem
  </span>
  <span class="linha_sono" id="linha_sono_cinco_2">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="3 - Normal" required>
    3 – Normal
  </span>
  <span class="linha_sono" id="linha_sono_cinco_3">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="4 - Sentindo-me estressada" required>
    2 – Aumento das dores musculares
  </span>
  <span class="linha_sono" id="linha_sono_cinco_4">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="5 - Altamente estressada" required>
    1 – Muito dolorida
  </span>
</div>
<br><br>
  <div class="mb-3">
    <h4>Caracterize a sua DOR MUSCULAR GERAL de acordo com essa escala: *</h4>
    <br>
    <img src="../assets/img/escala-dor.png" alt="Mapa Dor" class="img-fluid" style="width: 800px;">
    <br><br>
    <div class="form-check">
      <label>Sim</label>
      <input class="form-check-input" type="radio" name="dor_corpo" value="Sim" required>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="dor_corpo" value="Não" required> Não
    </div>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Se a sua dor muscular for específica, qual o local?</h4>
    <input type="text" class="form-control" name="local_dor" placeholder="Descreva o local da dor">
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Com relação dor muscular específica, classifique de acordo com a imagem como é: *</h4>
    <img src="../assets/img/codigo-dor.png" alt="Mapa Dor" class="img-fluid" style="width: 800px;">
    <br>
    <br>
    <div class="form-check">
      <label>Sim</label>
      <input class="form-check-input" type="radio" name="dor_corpo" value="Sim" required>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="dor_corpo" value="Não" required> Não
    </div>
  </div>
  <br><br>
  <div class="mb-3">
  <h4>Bem-Estar (NÍVEL DE ESTRESSE) *</h4>
  <span class="linha_sono" id="linha_sono_cinco_0">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="1 - Muito relaxada" required>
    1 – Muito relaxada
  </span>
  <span class="linha_sono" id="linha_sono_cinco_1">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="2 - Relaxada" required>
    2 – Relaxada
  </span>
  <span class="linha_sono" id="linha_sono_cinco_2">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="3 - Normal" required>
    3 – Normal
  </span>
  <span class="linha_sono" id="linha_sono_cinco_3">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="4 - Sentindo-me estressada" required>
    4 – Sentindo-me estressada
  </span>
  <span class="linha_sono" id="linha_sono_cinco_4">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="5 - Altamente estressada" required>
    5 – Altamente estressada
  </span>
</div>
<br><br>
<div class="mb-3">
  <h4>Bem-Estar (HUMOR) *</h4>
  <span class="linha_sono" id="linha_sono_cinco_0">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="5" required>
    5 – Muito bom humor
  </span>
  <span class="linha_sono" id="linha_sono_cinco_1">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="4" required>
    4 – Bom humor em geral
  </span>
  <span class="linha_sono" id="linha_sono_cinco_2">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="3" required>
    3 – Menos interessada em outras atividades que em habitual
  </span>
  <span class="linha_sono" id="linha_sono_cinco_3">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="2" required>
    2 – Mal humorada com a família e colegas de trabalho
  </span>
  <span class="linha_sono" id="linha_sono_cinco_4">
    <input class="form-check-input" type="radio" name="nivel_estresse" value="1" required>
    1 – Muito irritada e triste
  </span>
</div>
  <br><br>
  <div class="mb-3">
    <h4>Período Pré-mestrual? *</h4>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="periodo_premenstrual" value="Sim" required>
        <label class="form-check-label">SIM</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="periodo_premenstrual" value="Não" required>
        <label class="form-check-label">NÃO</label>
    </div>
  </div>
  <br><br>
  <div class="mb-3">
  <h4>Período mestrual? *</h4>
  <div class="form-check">
        <input class="form-check-input" type="radio" name="periodo_premenstrual" value="Sim" required>
        <label class="form-check-label">SIM</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="periodo_premenstrual" value="Não" required>
        <label class="form-check-label">NÃO</label>
    </div>
  </div>
<br><br>
  <div class="mb-3">
    <h4>Está fazendo uso de medicação?</h4>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Arcoxia">
        <label class="form-check-label">Arcoxia</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Lisador Dip">
        <label class="form-check-label">Lisador Dip</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Paracetamol">
        <label class="form-check-label">Paracetamol</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Meloxicam">
        <label class="form-check-label">Meloxicam</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Diprospan">
        <label class="form-check-label">Diprospan</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Tormiv">
        <label class="form-check-label">Tormiv</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Paco">
        <label class="form-check-label">Paco</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="uso_medicacao[]" value="Serenata">
        <label class="form-check-label">Serenata</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="outro">
        <label class="form-check-label">Outro</label>
    </div>
  </div>
  <br><br>
  <div class="mb-3">
    <h4>Para que está usando a medicação?</h4>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="DOMS">
        <label class="form-check-label">DOMS</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="Quadril">
        <label class="form-check-label">Quadril</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="Tornozelo">
        <label class="form-check-label">Tornozelo</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="Pé">
        <label class="form-check-label">Pé</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="Ombro">
        <label class="form-check-label">Ombro</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="Coluna">
        <label class="form-check-label">Coluna</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="PS">
        <label class="form-check-label">PS</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="motivo_medicacao[]" value="outro">
        <label class="form-check-label">Outro</label>
    </div>
  </div>
  
  <button type="submit" class="btn btn-primary w-100">Registrar</button>
  <a href="/psa-cbg/index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>

</div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
