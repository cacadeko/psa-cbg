<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Percepção de Esforço por Grupo</title>
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
      align-items: center;
      justify-content: space-between;
    }

    .header img {
      height: 40px;
    }

    .grafico-container {
      background-color: white;
      margin: 20px;
      padding: 20px;
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

    .legenda {
      text-align: left;
      padding: 10px 0 0 10px;
      font-size: 14px;
    }

    .legenda span {
      margin-right: 15px;
    }

    .legenda .cor {
      width: 12px;
      height: 12px;
      display: inline-block;
      margin-right: 5px;
      vertical-align: middle;
    }

    .grupo-label {
      font-weight: bold;
      font-size: 20px;
      margin: 20px 0 10px 10px;
    }

    canvas {
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<div class="header">
  <div style="display: flex; align-items: center; gap: 15px;">
    <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="Logo CBG" style="height: 40px;">
    <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
  </div>

  <!-- Filtros dentro da barra azul -->
  <div style="display: flex; align-items: center; gap: 15px; color: white; font-size: 13px;">
    <div>
      <label>GRUPO</label><br>
      <select><option>G1</option></select>
    </div>
    <div>
      <label>GRUPO</label><br>
      <select><option>G2</option></select>
    </div>
    <div>
      <label>DATA</label><br>
      <input type="date" value="2025-05-18" style="font-size: 13px;">
    </div>
    <div>
      <label>&nbsp;</label><br>
      <input type="date" value="2025-05-24" style="font-size: 13px;">
    </div>
  </div>
</div>


<div class="grafico-container">
  <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO POR DIA/GRUPO DE TREINO (MÉDIA)</div>

  <div class="legenda">
    <span><span class="cor" style="background:#ffe54c;"></span> PFE</span>
    <span><span class="cor" style="background:#b6f547;"></span> PFG</span>
    <span><span class="cor" style="background:#fc8d3c;"></span> PREVENTIVO</span>
    <span><span class="cor" style="background:#1f224f;"></span> TÉCNICO</span>
  </div>

  <div class="grupo-label">G1</div>
  <canvas id="graficoG1" height="90"></canvas>

  <div class="grupo-label">G2</div>
  <canvas id="graficoG2" height="90"></canvas>
</div>

<script>
Chart.register(ChartDataLabels);

const baseOptions = {
  type: 'bar',
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        color: '#fff',
        anchor: 'center',
        align: 'center',
        font: { weight: 'bold', size: 10 },
        formatter: val => val
      },
      legend: { display: false }
    },
    scales: {
      x: {
        stacked: true,
        ticks: { font: { size: 10 } },
        grid: { display: false }
      },
      y: {
        stacked: true,
        beginAtZero: true,
        max: 15, // espaço suficiente para empilhar tudo
        ticks: {
          stepSize: 3,
          font: { size: 10 }
        },
        grid: {
          display: true,
          drawTicks: false
        }
      }
    },
    layout: {
      padding: { top: 10, bottom: 10 }
    },
    elements: {
      bar: {
        borderSkipped: false,
        barThickness: 25 // barras finas para colunas altas com vários níveis
      }
    }
  },
  plugins: [ChartDataLabels]
};

// G1 - com todos os tipos em todos os dias
new Chart(document.getElementById('graficoG1').getContext('2d'), {
  ...baseOptions,
  data: {
    labels: ['segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'],
    datasets: [
    { label: 'PFE', backgroundColor: '#ffe54c', data: [5, 4, 4, 4, 5, 3] },
    { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 4, 5, 2] },
    { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [5, 2, 4, 3, 3, 5] },
    { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [7, 6, 8, 5, 6, 3] }
    ]
  }
});

// G2 - todos os tipos presentes, com zeros onde não há valor
new Chart(document.getElementById('graficoG2').getContext('2d'), {
  ...baseOptions,
  data: {
  labels: ['segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'],
  datasets: [
    { label: 'PFE', backgroundColor: '#ffe54c', data: [5, 4, 4, 4, 5, 3] },
    { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 4, 5, 2] },
    { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [5, 2, 3, 2, 3, 5] },
    { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [7, 6, 8, 5, 6, 3] }
  ]
}

});
</script>

</body>
</html>
