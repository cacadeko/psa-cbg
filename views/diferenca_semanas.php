<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Diferença entre as Semanas</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }

    .header {
      background-color: #001f7f;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .header img {
      height: 40px;
    }

    .filtros {
      display: flex;
      gap: 15px;
      align-items: center;
      font-size: 13px;
      background-color: white;
      padding: 10px 20px;
      justify-content: flex-end;
    }

    .filtros label {
      display: block;
      margin-bottom: 2px;
    }

    .grafico-container {
      background-color: white;
      margin: 0;
      padding: 20px;
      box-shadow: 0 0 4px rgba(0,0,0,0.1);
      text-align: center;
    }

    .grafico-titulo {
      background-color: black;
      color: white;
      font-size: 18px;
      padding: 10px;
      font-weight: bold;
    }

    .bloco-volume {
      display: flex;
      justify-content: center;
      gap: 50px;
      background-color: #fefefe;
      padding: 20px 0;
    }

    .bloco {
      text-align: center;
      padding: 15px 25px;
      font-weight: bold;
      flex: 1;
      min-width: 300px;
    }


    .g1-bloco {
      background-color: #0d1f86;
      color: white;
    }

    .g2-bloco {
      background-color: #00c934;
      color: white;
    }

    .valor {
      font-size: 34px;
      margin: 10px 0;
    }
  </style>
</head>
<body>

<div class="header">
  <div style="display: flex; align-items: center; gap: 10px;">
    <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG">
    <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
  </div>
</div>

<div class="filtros">
  <div>
    <label for="dataInicioG1">DATA G1</label>
    <input type="date" id="dataInicioG1" value="2025-05-18">
  </div>
  <div>
    <label for="dataFimG1">&nbsp;</label>
    <input type="date" id="dataFimG1" value="2025-05-24">
  </div>
  <div>
    <label for="dataInicioG2">DATA G2</label>
    <input type="date" id="dataInicioG2" value="2025-04-27">
  </div>
  <div>
    <label for="dataFimG2">&nbsp;</label>
    <input type="date" id="dataFimG2" value="2025-05-24">
  </div>
</div>

<div class="grafico-container">
  <div class="grafico-titulo">DIFERENÇA ENTRE AS SEMANAS</div>
  <div style="margin: 10px 0; font-weight: bold;">GRUPOS <span style="color:#0d1f86;">G1</span> | <span style="color:#00c934;">G2</span></div>
  <canvas id="graficoDiferenca" height="100"></canvas>
</div>

<div class="bloco-volume">
  <div class="bloco g1-bloco">
    G1: MANUTENÇÃO DO VOLUME DE TREINO.<br>
    <div class="valor">34,80</div>
    VOLUME SEMANAL (HORAS)
  </div>
  <div class="bloco g2-bloco">
    G2: MANUTENÇÃO DO VOLUME DE TREINO.<br>
    <div class="valor">46,30</div>
    VOLUME SEMANAL (HORAS)
  </div>
</div>

<script>
Chart.register(ChartDataLabels);

new Chart(document.getElementById('graficoDiferenca').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['18', '19', '20', '21'],
    datasets: [
      {
        label: 'G1',
        data: [41, 34, 18, 35],
        backgroundColor: '#0d1f86'
      },
      {
        label: 'G2',
        data: [44, 46, 45, 46],
        backgroundColor: '#00c934'
      }
    ]
  },
  options: {
  responsive: true,
  plugins: {
    legend: { display: false },
    datalabels: {
      anchor: 'end',
      align: 'start',
      backgroundColor: '#1f224f',
      color: '#fff',
      borderRadius: 4,
      padding: 6,
      font: {
        weight: 'bold',
        size: 12
      },
      formatter: value => value
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 60,
      ticks: { stepSize: 10 }
    }
  }
},
  plugins: [ChartDataLabels]
});
</script>

</body>
</html>
