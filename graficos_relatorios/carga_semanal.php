<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Carga Semanal por Atleta</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
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
    }
    .header img {
      height: 40px;
    }
    .grafico-container {
      background-color: white;
      margin: 20px;
      padding: 20px;
      text-align: center;
    }
    .grafico-titulo {
      background-color: black;
      color: white;
      font-size: 18px;
      padding: 10px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .legenda {
      display: flex;
      justify-content: center;
      gap: 20px;
      font-size: 12px;
      margin-top: 10px;
    }
    .cor {
      width: 14px;
      height: 14px;
      display: inline-block;
      margin-right: 5px;
      vertical-align: middle;
      border-radius: 2px;
    }
  </style>
</head>
<body>

<div class="header" style="flex-wrap: wrap; gap: 20px;">
  <!-- Lado esquerdo: logo + título -->
  <div style="display: flex; align-items: center; gap: 10px;">
    <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG">
    <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
  </div>

  <!-- Lado direito: filtros -->
  <div style="display: flex; align-items: center; gap: 20px; font-size: 13px; color: white;">
    <div>
      <label for="tag">TAG</label><br>
      <select id="tag" style="font-size: 13px;">
        <option>G1</option>
        <option>G2</option>
      </select>
    </div>
    <div>
      <label for="dataInicio">DATA INÍCIO</label><br>
      <input type="date" id="dataInicio" value="2025-05-18" style="font-size: 13px;">
    </div>
    <div>
      <label for="dataFim">DATA FIM</label><br>
      <input type="date" id="dataFim" value="2025-05-24" style="font-size: 13px;">
    </div>
  </div>
</div>


<div style="text-align:right;padding:0 20px 10px;font-size:12px;">
  <strong>Fórmula de Cálculo:</strong> PSE × Tempo do Treino (minutos) <br>
  <span style="font-style: italic;">ex: PSE da Atleta = 5 – Tempo de Treino = 50 minutos</span>
</div>

<div class="grafico-container">
  <div class="grafico-titulo">CARGA SEMANAL TOTAL POR ATLETA</div>
  <canvas id="graficoTotal" height="60"></canvas>
</div>

<div class="grafico-container">
  <div class="grafico-titulo">CARGA DETALHADA POR TIPO DE TREINO</div>
  <canvas id="graficoDetalhado" height="75"></canvas>

  <div class="legenda">
    <span><span class="cor" style="background:#ffe54c;"></span> PFE</span>
    <span><span class="cor" style="background:#b6f547;"></span> PFG</span>
    <span><span class="cor" style="background:#fc8d3c;"></span> PREVENTIVO</span>
    <span><span class="cor" style="background:#0D1036;"></span> TÉCNICO</span>
  </div>
</div>

<script>
Chart.register(ChartDataLabels);

const atletas = ['Sofia Nunes', 'Laura Rocha', 'Isabela Moura', 'Júlia Fernandes', 'Amanda Ribeiro', 'Camila Duarte', 'Elisa Martins'];

const pfe = [4630, 4019, 4134, 3740, 3636, 3611, 3016];
const pfg = [1265, 922, 1019, 1167, 917, 1009, 869];
const preventivo = [270, 0, 150, 600, 360, 240, 240];
const tecnico = [4885, 4333, 4236, 3811, 3923, 3195, 1314];

const totais = atletas.map((_, i) => pfe[i] + pfg[i] + preventivo[i] + tecnico[i]);

new Chart(document.getElementById('graficoTotal').getContext('2d'), {
  type: 'bar',
  data: {
    labels: atletas,
    datasets: [{
      label: 'Carga Total',
      data: totais,
      backgroundColor: '#0D1036'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        anchor: 'end',
        align: 'start',
        color: '#fff',
        backgroundColor: '#0D1036',
        borderRadius: 4,
        padding: 4,
        font: {
          weight: 'bold',
          size: 10
        },
        formatter: val => val.toLocaleString()
      },
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 2000 }
      }
    }
  },
  plugins: [ChartDataLabels]
});

new Chart(document.getElementById('graficoDetalhado').getContext('2d'), {
  type: 'bar',
  data: {
    labels: atletas,
    datasets: [
      { label: 'PFE', data: pfe, backgroundColor: '#ffe54c' },
      { label: 'PFG', data: pfg, backgroundColor: '#b6f547' },
      { label: 'PREVENTIVO', data: preventivo, backgroundColor: '#fc8d3c' },
      { label: 'TÉCNICO', data: tecnico, backgroundColor: '#0D1036' }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        anchor: 'end',
        align: 'start',
        color: '#fff',
        borderRadius: 4,
        padding: 4,
        font: {
          weight: 'bold',
          size: 9
        },
        formatter: val => val.toLocaleString()
      },
      legend: { display: false }
    },
    scales: {
      x: { stacked: false },
      y: {
        stacked: false,
        beginAtZero: true,
        ticks: { stepSize: 2000 }
      }
    }
  },
  plugins: [ChartDataLabels]
});
</script>

</body>
</html>
