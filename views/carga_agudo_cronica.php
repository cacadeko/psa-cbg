<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Carga Agudo-Crônica</title>
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
      text-align: center;
    }

    .grafico-titulo {
      background-color: black;
      color: white;
      font-size: 18px;
      padding: 10px;
      font-weight: bold;
    }

    .referencia {
      margin-top: 30px;
      padding: 10px;
      font-size: 13px;
    }

    .referencia table {
      border-collapse: collapse;
      margin: 0 auto;
      font-size: 13px;
    }

    .referencia td {
      padding: 6px 12px;
      border: 1px solid #ccc;
    }

    .ref-vermelho { background-color: #d90000; color: white; }
    .ref-verde { background-color: #00c934; color: black; }
    .ref-azul { background-color: #007BFF; color: white; }
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
  <div class="grafico-titulo">CARGA AGUDO-CRÔNICA (MÉDIA DA SEMANA)</div>
  <canvas id="graficoCarga" height="110"></canvas>
  <div style="margin-top: 10px; font-size: 12px; text-align: right; padding-right: 20px;">
    <span style="border-top: 2px dotted red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Linha média 1: <strong>1,06</strong>
  </div>
</div>

<div class="referencia">
  <strong>REFERÊNCIA</strong><br><br>
  <table>
    <tr>
      <td class="ref-vermelho">&gt; 1,5</td>
      <td>Excesso de Treinamento</td>
    </tr>
    <tr>
      <td class="ref-verde">0,8 a 1,3</td>
      <td>Padrão de Segurança</td>
    </tr>
    <tr>
      <td class="ref-azul">&lt; 0,7</td>
      <td>Destreinamento</td>
    </tr>
  </table>
</div>

<script>
Chart.register(ChartDataLabels);
Chart.register(window['chartjs-plugin-annotation']);

new Chart(document.getElementById('graficoCarga').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['18', '19', '20', '21', '22', '23', '24'],
    datasets: [{
      label: 'Carga',
      data: [1.2, 1.2, 1.1, 1.1, 1.1, 1.0, 0.8],
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
        formatter: value => value.toFixed(1)
      },
      legend: { display: false },
      annotation: {
        annotations: {
          linhaMedia: {
            type: 'line',
            yMin: 1.06,
            yMax: 1.06,
            borderColor: 'red',
            borderWidth: 2,
            borderDash: [5, 5],
            label: {
              display: true,
              content: 'Linha média 1: 1,06',
              position: 'end',
              backgroundColor: 'rgba(0,0,0,0.7)',
              color: '#fff',
              font: {
                size: 10,
                weight: 'bold'
              }
            }
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        suggestedMax: 2,
        ticks: {
          stepSize: 0.2
        }
      }
    },
    annotation: {
      annotations: {
        linhaMedia: {
          type: 'line',
          yMin: 1.06,
          yMax: 1.06,
          borderColor: 'red',
          borderWidth: 2,
          borderDash: [5, 5],
          label: {
            display: true,
            content: 'Linha média 1: 1,06',
            position: 'end',
            backgroundColor: 'transparent',
            color: 'red',
            font: {
              weight: 'bold',
              size: 11
            },
            padding: 4
          }
        }
      }
    }
  },
  plugins: [ChartDataLabels]
});
</script>

</body>
</html>
