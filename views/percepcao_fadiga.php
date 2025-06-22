<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Percepção de Fadiga</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0/dist/chartjs-plugin-annotation.min.js"></script>
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
    }
    .header img {
      height: 40px;
    }
    .filtros {
      display: flex;
      gap: 15px;
      padding: 10px 20px;
      background-color: white;
      justify-content: flex-end;
      font-size: 13px;
    }
    .grafico-container {
      background-color: white;
      padding: 20px;
      text-align: center;
    }
    .grafico-titulo {
      background-color: black;
      color: white;
      padding: 10px;
      font-size: 18px;
      font-weight: bold;
    }
    .referencia {
      text-align: center;
      margin: 20px;
    }
    .bolinhas {
      display: flex;
      justify-content: center;
      gap: 5px;
      margin-bottom: 10px;
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
    .legenda-escala {
      display: flex;
      justify-content: center;
      font-size: 12px;
      gap: 20px;
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
  <div class="grafico-titulo">PERCEPÇÃO DE FADIGA (MÉDIA DA SEMANA)</div>
  <canvas id="graficoFadiga" height="110"></canvas>
</div>

<div class="referencia">
  <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" style="height: 20px; margin-bottom: 10px;"><br>
  <strong style="color:#c00;">REFERÊNCIA</strong>
  <div class="bolinhas">
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
  <div class="legenda-escala">
    <span>NADA FADIGADO</span>
    <span>UM POUCO FADIGADO</span>
    <span>MODERADAMENTE FADIGADO</span>
    <span>MUITO FADIGADO</span>
    <span>FADIGA / EXAUSTÃO TOTAL</span>
  </div>
</div>

<script>
Chart.register(ChartDataLabels);
Chart.register(window['chartjs-plugin-annotation']);

new Chart(document.getElementById('graficoFadiga').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB', 'DOM'],
    datasets: [{
      label: 'Fadiga',
      data: [5, 4, 4, 3, 3, 3, 2],
      backgroundColor: '#0D1036'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      datalabels: {
        anchor: 'end',
        align: 'start',
        backgroundColor: '#0D1036',
        color: '#fff',
        borderRadius: 4,
        padding: 6,
        font: { weight: 'bold', size: 12 },
        formatter: value => value.toFixed(0)
      },
      legend: { display: false },
      annotation: {
        annotations: {
          linhaMedia: {
            type: 'line',
            yMin: 3,
            yMax: 3,
            borderColor: 'red',
            borderWidth: 2,
            borderDash: [5, 5],
            label: {
              display: true,
              content: ['Linha média 1: 3'],
              enabled: true,
              position: 'end',
              backgroundColor: 'transparent',
              color: 'red',
              font: {
                size: 12,
                weight: 'bold'
              },
              xAdjust: -10,  // empurra levemente à esquerda se necessário
              yAdjust: -10,  // sobe para ficar acima da linha
              padding: 4
            }
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        suggestedMax: 6,
        ticks: {
          stepSize: 1
        }
      }
    }
  },
  plugins: [ChartDataLabels]
});
</script>

</body>
</html>
