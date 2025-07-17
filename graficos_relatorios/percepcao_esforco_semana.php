<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Percepção de Esforço - Média da Semana</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f7f7f7;
    }

    .header {
      background-color: #001f7f;
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .header img {
      height: 40px;
    }

    .filtros {
      background-color: white;
      padding: 15px 20px;
      display: flex;
      gap: 20px;
      align-items: center;
      justify-content: flex-start;
      flex-wrap: wrap;
    }

    .filtros label {
      font-size: 14px;
      margin-right: 5px;
    }

    .grafico-container {
      background-color: white;
      padding: 20px;
      margin: 20px;
      box-shadow: 0 0 4px rgba(0,0,0,0.1);
    }

    .grafico-titulo {
      background-color: black;
      color: white;
      font-size: 18px;
      text-align: center;
      padding: 10px;
      font-weight: bold;
    }

    .media-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 30px;
    }

    .media-box {
      padding: 15px 20px;
      color: white;
      font-size: 20px;
      font-weight: bold;
      text-align: center;
      border-radius: 4px;
      width: 120px;
    }

    .media-pfg { background-color: #b6f547; color: #000; }
    .media-pfe { background-color: #ffe54c; color: #000; }
    .media-tecnico { background-color: #1f224f; }

    .escala-intensidade {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      margin-top: 40px;
      gap: 10px;
    }

    .escala-intensidade .bolinha {
      width: 25px;
      height: 25px;
      border-radius: 50%;
      display: inline-block;
      text-align: center;
      line-height: 25px;
      font-size: 12px;
      color: white;
    }

    .label-intensidade {
      font-size: 12px;
      margin-top: 5px;
      text-align: center;
    }

    .bolinha {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  display: inline-block;
  text-align: center;
  line-height: 25px;
  font-size: 12px;
  color: white;
  font-weight: bold;
}

  </style>
</head>
<body>

<div class="header">
  <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="Logo CBG">
  <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
</div>

<div class="filtros">
  <div>
    <label for="tipo1">TIPO</label>
    <select id="tipo1"><option>PFG</option></select>
  </div>
  <div>
    <label for="tipo2">TIPO</label>
    <select id="tipo2"><option>PFE</option></select>
  </div>
  <div>
    <label for="tipo3">TIPO</label>
    <select id="tipo3"><option>TÉCNICO</option></select>
  </div>
  <div>
    <label for="tag">TAG</label>
    <select id="tag"><option>G1</option></select>
  </div>
  <div>
    <label for="dataInicio">DATA</label>
    <input type="date" id="dataInicio" value="2025-05-18">
  </div>
  <div>
    <label for="dataFim">&nbsp;</label>
    <input type="date" id="dataFim" value="2025-05-24">
  </div>
</div>

<div class="grafico-container">
  <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO (MÉDIA DA SEMANA)</div>
  <canvas id="graficoSemanas" height="110"></canvas>


<div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 20px 30px; background: #fefefe;">
  
  <!-- ESQUERDA: Escala de intensidade -->
  <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 8px;">
    <!-- Logo + Título -->
    <div style="display: flex; align-items: center; gap: 10px;">
      <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" style="height: 30px;">
      <span style="color: #c00; font-weight: bold;">INTENSIDADE DO TREINO</span>
    </div>

    <!-- Bolinhas numeradas -->
    <div style="display: flex; gap: 6px; margin-top: 5px;">
      <div class="bolinha" style="background:#007BFF;">0</div>
      <div class="bolinha" style="background:#1e90ff;">1</div>
      <div class="bolinha" style="background:#28c745;">2</div>
      <div class="bolinha" style="background:#a0e518;">3</div>
      <div class="bolinha" style="background:#fcea60;">4</div>
      <div class="bolinha" style="background:#fdb940;">5</div>
      <div class="bolinha" style="background:#fc8d3c;">6</div>
      <div class="bolinha" style="background:#f6523c;">7</div>
      <div class="bolinha" style="background:#d62c2c;">8</div>
      <div class="bolinha" style="background:#990000;">9</div>
      <div class="bolinha" style="background:#5c0000;">10</div>
    </div>

    <!-- Legendas por agrupamento -->
    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 5px; width: 100%;">
      <span style="margin-left: 0;">LEVE</span>
      <span style="margin-left: 60px;">MODERADO</span>
      <span style="margin-left: 70px;">INTENSO</span>
      <span style="margin-left: 60px;">MUITO INTENSO</span>
    </div>
  </div>

  <!-- DIREITA: Médias dos grupos -->
  <div style="display: flex; gap: 20px;">
    <!-- PFG -->
    <div style="text-align: center;">
      <div style="font-size: 26px; font-weight: bold;">4</div>
      <div style="background:#00FF00; color:#000; font-weight:bold; padding:10px 20px; width:100px;">
        PFG<br>MÉDIA GERAL
      </div>
    </div>

    <!-- PFE -->
    <div style="text-align: center;">
      <div style="font-size: 26px; font-weight: bold;">4</div>
      <div style="background:#FFDD00; color:#000; font-weight:bold; padding:10px 20px; width:100px;">
        PFE<br>MÉDIA GERAL
      </div>
    </div>

    <!-- TÉCNICO -->
    <div style="text-align: center;">
      <div style="font-size: 26px; font-weight: bold;">4</div>
      <div style="background:#0D1036; color:#fff; font-weight:bold; padding:10px 20px; width:100px;">
        TÉCNICO<br>MÉDIA GERAL
      </div>
    </div>

    <!-- PREVENTIVO -->
    <div style="text-align: center;">
      <div style="font-size: 26px; font-weight: bold;">4</div>
      <div style="background:#fc8d3c; color:#000; font-weight:bold; padding:10px 20px; width:100px;">
        PREVENTIVO<br>MÉDIA GERAL
      </div>
    </div>
  </div>
</div>

<script>
 new Chart(document.getElementById('graficoSemanas').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['Sofia', 'Laura', 'Isabela', 'Amanda', 'Camila', 'Beatriz', 'Elisa'],
    datasets: [
      { label: 'PFE', backgroundColor: '#ffe54c', data: [4, 3, 5, 4, 3, 4, 2] },
      { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 5, 4, 4, 3] },
      { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [5, 5, 5, 5, 4, 4, 1] },
      { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [3, 3, 3, 2, 3, 2, 2] }
    ]
  },
  options: {
  responsive: true,
  plugins: {
      legend: {
        display: true,
        position: 'top'
      },
      datalabels: {
        anchor: 'end',
        align: 'start',
        color: '#000',
        font: { weight: 'bold', size: 11 },
        formatter: value => value + '%'
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        max: 10,
        ticks: { stepSize: 1 }
      }
    }
  },
  plugins: [ChartDataLabels]
});

</script>

</body>
</html>
