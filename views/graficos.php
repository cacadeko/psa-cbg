<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Distribuição Semanal dos Treinos</title>

  <!-- Chart.js e plugin de DataLabels -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f4f4;
      overflow-x: hidden;
      max-width: 100vw;
    }

    .container {
      display: flex;
      height: 100vh;
      max-width: 100%;
      overflow-x: hidden;
    }

    .col-esquerda {
      width: 60%;
      padding: 30px;
      box-sizing: border-box;
      background: #ffffff;
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      justify-content: space-between;
      gap: 20px;
    }

    .col-direita {
      width: 40%;
      padding: 30px;
      box-sizing: border-box;
      background: #eef2f7;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }

    .grafico-wrapper {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 0;
      box-sizing: border-box;
    }

    .grafico-bloco {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
      width: 85%;
    }

    .grafico-titulo {
      font-size: 16px;
      text-align: center;
      margin-bottom: 10px;
    }

    #graficoPizzaTreinos {
      width: 100%;
      height: auto;
      display: block;
    }

    #graficoBarrasMinutos,
    #graficoTreinoFita,
    #graficoTreinoMisto {
      width: 100%;
      height: 220px;
    }

    .legenda-sobre-grafico {
      width: 20%;
      background: #f9f9f9;
      padding: 10px 20px;
      border-left: 4px solid #0033A0;
      box-shadow: 0 0 4px rgba(0,0,0,0.05);
      box-sizing: border-box;
    }

    .legenda-lista {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .legenda-lista li {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
      font-size: 14px;
      word-break: break-word;
    }

    .legenda-lista .cor {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 10px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="col-esquerda">
    <div class="grafico-wrapper">
      <div class="grafico-bloco">
        
<div style="width: 100%; display: flex; justify-content: center; gap: 20px; margin-bottom: 10px;">
  <div>
    <label for="dataInicio">Data Início:</label><br>
    <input type="date" id="dataInicio" name="dataInicio">
  </div>
  <div>
    <label for="dataFim">Data Fim:</label><br>
    <input type="date" id="dataFim" name="dataFim">
  </div>
</div>

<h3 class="grafico-titulo">📊 Distribuição Semanal dos Tipos de Treino</h3>
        <canvas id="graficoPizzaTreinos"></canvas>
      </div>
    </div>
    <div class="legenda-sobre-grafico">
      <h3 style="font-size:16px;">Legenda</h3>
      <ul class="legenda-lista">
        <li><span class="cor" style="background:#FF6384;"></span> PFE: 23,51%</li>
        <li><span class="cor" style="background:#36A2EB;"></span> PFS: 16,38%</li>
        <li><span class="cor" style="background:#FFCE56;"></span> Técnico Misto: 22,30%</li>
        <li><span class="cor" style="background:#4BC0C0;"></span> Técnico Fita: 17,92%</li>
        <li><span class="cor" style="background:#9966FF;"></span> Físico-Técnico: 17,32%</li>
        <li><span class="cor" style="background:#FF9F40;"></span> Intervalo: 2,03%</li>
      </ul>
    </div>
  </div>

  <div class="col-direita">
    <div>
      <h2>📌 Somatório de Minutos</h2>
      <canvas id="graficoBarrasMinutos"></canvas>
    </div>
    <div>
      <h2>🎯 Treino Técnico Fita Semanal</h2>
      <canvas id="graficoTreinoFita"></canvas>
    </div>
    <div>
      <h2>📘 Treino Técnico Misto Semanal</h2>
      <canvas id="graficoTreinoMisto"></canvas>
    </div>
  </div>
</div>

<script>
  Chart.register(ChartDataLabels);

  fetch('../data/treinos_distribuicao_semanal.json')
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.tipo);
      const percentuais = data.map(item => item.percentual);
      const totalMinutos = 8 * 60 * 7;
      const minutos = percentuais.map(p => Math.round(totalMinutos * (p / 100)));

      new Chart(document.getElementById('graficoPizzaTreinos').getContext('2d'), {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            data: percentuais,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          layout: { padding: { top: 100, bottom: 100, left: 100, right: 100 } },
          plugins: {
            title: { display: false },
            legend: { display: false },
            datalabels: {
              color: '#000',
              formatter: (value, context) => {
                const label = context.chart.data.labels[context.dataIndex];
                return `${label} - ${value.toFixed(1)}%`;
              },
              anchor: 'end',
              align: 'end',
              offset: 10,
              clamp: true,
              borderRadius: 4,
              backgroundColor: '#ffffff',
              borderColor: '#ccc',
              borderWidth: 1,
              padding: 4
            }
          }
        },
        plugins: [ChartDataLabels]
      });

      new Chart(document.getElementById('graficoBarrasMinutos').getContext('2d'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Minutos por Tipo de Treino',
            data: minutos,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            datalabels: {
              anchor: 'end',
              align: 'end',
              color: '#000',
              font: { weight: 'bold' },
              formatter: (val) => `${val} min`
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 100 }
            }
          }
        },
        plugins: [ChartDataLabels]
      });

      new Chart(document.getElementById('graficoTreinoFita').getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Segunda-feira', 'Quarta-feira', 'Sexta-feira'],
          datasets: [{
            label: 'Minutos',
            data: [114, 100, 112],
            backgroundColor: '#4BC0C0'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            datalabels: {
              anchor: 'end',
              align: 'end',
              color: '#000',
              font: { weight: 'bold' },
              formatter: (val) => `${val} min`
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 20 }
            }
          }
        },
        plugins: [ChartDataLabels]
      });
    });
      new Chart(document.getElementById('graficoTreinoMisto').getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Terça-feira', 'Quarta-feira', 'Sábado'],
          datasets: [{
            label: 'Minutos',
            data: [179, 95, 134],
            backgroundColor: '#FFCE56'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            datalabels: {
              anchor: 'end',
              align: 'end',
              color: '#000',
              font: { weight: 'bold' },
              formatter: (val) => `${val} min`
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 20 }
            }
          }
        },
        plugins: [ChartDataLabels]
      });

</script>

</body>
</html>
