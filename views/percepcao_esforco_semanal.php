<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Percepção de Esforço - Média Semanas</title>
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
    }

    .filtros label {
      display: block;
      margin-bottom: 2px;
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

    .escala {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 30px;
      padding: 0 30px;
    }

    .escala-esquerda {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
    }

    .escala-bolinhas {
      display: flex;
      gap: 5px;
    }

    .bolinha {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      text-align: center;
      line-height: 24px;
      font-size: 12px;
      font-weight: bold;
      color: white;
    }

    .escala-legendas {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      width: 100%;
      max-width: 400px;
    }

    .escala-direita {
      display: flex;
      gap: 15px;
      font-size: 12px;
    }

    .cor {
      display: inline-block;
      width: 12px;
      height: 12px;
      margin-right: 5px;
      vertical-align: middle;
    }
  </style>
</head>
<body>

<div class="header">
  <div style="display: flex; align-items: center; gap: 10px;">
    <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG">
    <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
  </div>
  <div class="filtros">
    <div>
      <label for="tag">TAG</label>
      <select id="tag"><option>G1</option></select>
    </div>
    <div>
      <label for="dataInicio">DATA</label>
      <input type="date" id="dataInicio" value="2025-04-27">
    </div>
    <div>
      <label for="dataFim">&nbsp;</label>
      <input type="date" id="dataFim" value="2025-05-24">
    </div>
  </div>
</div>

<div class="grafico-container">
  <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO ( MÉDIA SEMANAS )</div>
  <canvas id="graficoSemanas" height="150"></canvas>

  <div class="escala">
    <div class="escala-esquerda">
      <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" style="height: 25px;">
      <div style="color: #c00; font-weight: bold; margin-top: 5px;">INTENSIDADE DO TREINO</div>
      <div class="escala-bolinhas">
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
      <div class="escala-legendas">
        <span>LEVE</span>
        <span>MODERADO</span>
        <span>INTENSO</span>
        <span>MUITO INTENSO</span>
      </div>
    </div>

    <div class="escala-direita">
      <div><span class="cor" style="background:#ffe54c;"></span> PFE</div>
      <div><span class="cor" style="background:#b6f547;"></span> PFG</div>
      <div><span class="cor" style="background:#fc8d3c;"></span> PREVENTIVO</div>
      <div><span class="cor" style="background:#1f224f;"></span> TÉCNICO</div>
    </div>
  </div>
</div>

<script>
Chart.register(ChartDataLabels);

new Chart(document.getElementById('graficoSemanas').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['18', '19', '20', '21'],
    datasets: [
      { label: 'PFE', backgroundColor: '#ffe54c', data: [4, 4, 4, 4] },
      { label: 'PFG', backgroundColor: '#b6f547', data: [3, 3, 4, 4] },
      { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [2, 3, 1, 2] },
      { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [5, 5, 6, 5] }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        color: '#000',
        anchor: 'end',
        align: 'end',
        font: {
          weight: 'bold',
          size: 11
        },
        formatter: val => val
      },
      legend: {
        display: false
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
