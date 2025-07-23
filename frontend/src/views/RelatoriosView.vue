<template>
  <div class="relatorios-container">
    <div class="header">
      <div class="header-left">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo" />
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>
      <div class="filtros">
        <div class="filtro-item">
          <label for="tag">GRUPO</label>
          <select v-model="filtros.tag" id="tag">
            <option value="G1">G1</option>
            <option value="G2">G2</option>
          </select>
        </div>
        <div class="filtro-item">
          <label for="dataInicio">DATA INÍCIO</label>
          <input type="date" v-model="filtros.dataInicio" id="dataInicio" />
        </div>
        <div class="filtro-item">
          <label for="dataFim">DATA FIM</label>
          <input type="date" v-model="filtros.dataFim" id="dataFim" />
        </div>
      </div>
    </div>

    <div class="nav-graficos">
      <button
        v-for="grafico in tiposGraficos"
        :key="grafico.id"
        @click="graficoAtivo = grafico.id"
        :class="['nav-btn', { active: graficoAtivo === grafico.id }]"
      >
        {{ grafico.nome }}
      </button>
    </div>

    <div class="graficos-content">
      <!-- 2. Percepção de Esforço Semanal -->
      <div v-if="graficoAtivo === 'percepcao-semanal'" class="grafico-container">
        <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO (MÉDIA DA SEMANA)</div>
        <canvas ref="graficoSemanas" height="110"></canvas>
        <div class="escala-intensidade">
          <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" style="height: 22px; margin-right: 8px;">
          <span style="color:#c00; font-weight:bold; margin-right: 10px;">INTENSIDADE DO TREINO</span>
          <div class="bolinhas">
            <span v-for="n in 11" :key="n" :style="{ background: coresIntensidade[n-1] }" class="bolinha">{{ n-1 }}</span>
          </div>
          <div class="legenda-escala">
            <span>LEVE</span>
            <span>MODERADO</span>
            <span>INTENSO</span>
            <span>MUITO INTENSO</span>
          </div>
        </div>
        <div class="medias-gerais">
          <div class="media-box media-pfg">4<br><span>PFG<br>MÉDIA GERAL</span></div>
          <div class="media-box media-pfe">4<br><span>PFE<br>MÉDIA GERAL</span></div>
          <div class="media-box media-tecnico">4<br><span>TÉCNICO<br>MÉDIA GERAL</span></div>
          <div class="media-box media-preventivo">4<br><span>PREVENTIVO<br>MÉDIA GERAL</span></div>
        </div>
      </div>
      <!-- Demais gráficos permanecem iguais -->
      <div v-if="graficoAtivo === 'distribuicao'" class="grafico-container">
        <div class="grafico-titulo">DISTRIBUIÇÃO SEMANAL DOS TIPOS DE TREINO</div>
        <canvas ref="graficoPizzaTreinos" height="120"></canvas>
        <canvas ref="graficoBarrasMinutos" height="120"></canvas>
      </div>
      <div v-if="graficoAtivo === 'percepcao-grupo'" class="grafico-container">
        <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO POR DIA/GRUPO DE TREINO (MÉDIA)</div>
        <canvas ref="graficoG1" height="90"></canvas>
        <canvas ref="graficoG2" height="90"></canvas>
      </div>
      <div v-if="graficoAtivo === 'carga-agudo-cronica'" class="grafico-container">
        <div class="grafico-titulo">CARGA AGUDO-CRÔNICA (MÉDIA DA SEMANA)</div>
        <canvas ref="graficoCarga" height="110"></canvas>
      </div>
      <div v-if="graficoAtivo === 'percepcao-fadiga'" class="grafico-container">
        <div class="grafico-titulo">PERCEPÇÃO DE FADIGA (MÉDIA DA SEMANA)</div>
        <canvas ref="graficoFadiga" height="110"></canvas>
      </div>
      <div v-if="graficoAtivo === 'carga-semanal'" class="grafico-container">
        <div class="grafico-titulo">CARGA SEMANAL TOTAL POR ATLETA</div>
        <canvas ref="graficoTotal" height="60"></canvas>
        <div class="grafico-titulo">CARGA DETALHADA POR TIPO DE TREINO</div>
        <canvas ref="graficoDetalhado" height="75"></canvas>
      </div>
      <div v-if="graficoAtivo === 'diferenca-semanas'" class="grafico-container">
        <div class="grafico-titulo">DIFERENÇA ENTRE AS SEMANAS</div>
        <canvas ref="graficoDiferenca" height="100"></canvas>
      </div>
    </div>
  </div>

  <!-- Percepção de Esforço (Média da Semana) - Legacy Chart Migration -->
  <div class="legacy-percepcao-esforco">
    <div class="header">
      <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="Logo CBG" />
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
        <input type="date" id="dataInicio" value="2025-05-18" />
      </div>
      <div>
        <label for="dataFim">&nbsp;</label>
        <input type="date" id="dataFim" value="2025-05-24" />
      </div>
    </div>
    <div class="grafico-container">
      <div class="grafico-titulo">PERCEPÇÃO DE ESFORÇO (MÉDIA DA SEMANA)</div>
      <BarChartPercepcaoEsforco />
      <div class="below-chart">
        <div class="intensidade-scale">
          <div class="scale-header">
            <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" style="height: 30px;" />
            <span style="color: #c00; font-weight: bold;">INTENSIDADE DO TREINO</span>
          </div>
          <div class="bolinhas">
            <span v-for="(color, idx) in bolinhaColors" :key="idx" class="bolinha" :style="{ background: color }">{{ idx }}</span>
          </div>
          <div class="legendas">
            <span>LEVE</span>
            <span style="margin-left: 60px;">MODERADO</span>
            <span style="margin-left: 70px;">INTENSO</span>
            <span style="margin-left: 60px;">MUITO INTENSO</span>
          </div>
        </div>
        <div class="summary-boxes">
          <div class="summary-box pfg">4<br /><span>PFG<br />MÉDIA GERAL</span></div>
          <div class="summary-box pfe">4<br /><span>PFE<br />MÉDIA GERAL</span></div>
          <div class="summary-box tecnico">4<br /><span>TÉCNICO<br />MÉDIA GERAL</span></div>
          <div class="summary-box preventivo">4<br /><span>PREVENTIVO<br />MÉDIA GERAL</span></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'
import ChartDataLabels from 'chartjs-plugin-datalabels'
Chart.register(ChartDataLabels)

const graficoAtivo = ref('percepcao-semanal')
const filtros = ref({
  tag: 'G1',
  dataInicio: '2025-05-18',
  dataFim: '2025-05-24'
})
const tiposGraficos = [
  { id: 'distribuicao', nome: 'Distribuição Semanal' },
  { id: 'percepcao-semanal', nome: 'Percepção Semanal de Esforço' },
  { id: 'percepcao-grupo', nome: 'Percepção de Esforço por Grupo' },
  { id: 'carga-agudo-cronica', nome: 'Carga Agudo-Crônica' },
  { id: 'percepcao-fadiga', nome: 'Percepção de Fadiga' },
  { id: 'carga-semanal', nome: 'Carga Semanal' },
  { id: 'diferenca-semanas', nome: 'Diferença entre Semanas' }
]
const coresIntensidade = [
  '#007BFF', '#1e90ff', '#28c745', '#a0e518', '#fcea60',
  '#fdb940', '#fc8d3c', '#f6523c', '#d62c2c', '#990000', '#5c0000'
]
const graficoPizzaTreinos = ref(null)
const graficoBarrasMinutos = ref(null)
const graficoSemanas = ref(null)
const graficoG1 = ref(null)
const graficoG2 = ref(null)
const graficoCarga = ref(null)
const graficoFadiga = ref(null)
const graficoTotal = ref(null)
const graficoDetalhado = ref(null)
const graficoDiferenca = ref(null)
let charts = {}

function criarGraficos() {
  Object.values(charts).forEach(chart => chart && chart.destroy())
  charts = {}
  // 2. Percepção de Esforço Semanal (ajustado para barras agrupadas)
  if (graficoAtivo.value === 'percepcao-semanal') {
    const atletas = ['Sofia', 'Laura', 'Isabela', 'Amanda', 'Camila', 'Beatriz', 'Elisa']
    const pfe = [4, 3, 5, 5, 3, 4, 3]
    const pfg = [5, 4, 4, 5, 4, 4, 3]
    const tecnico = [5, 5, 5, 5, 4, 4, 1]
    const preventivo = [3, 3, 3, 2, 3, 2, 2]
    charts.semanal = new Chart(graficoSemanas.value, {
      type: 'bar',
      data: {
        labels: atletas,
        datasets: [
          { label: 'PFE', backgroundColor: '#ffe54c', data: pfe },
          { label: 'PFG', backgroundColor: '#b6f547', data: pfg },
          { label: 'TÉCNICO', backgroundColor: '#1f224f', data: tecnico },
          { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: preventivo }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true },
          datalabels: {
            color: '#000',
            anchor: 'end',
            align: 'end',
            font: { weight: 'bold', size: 13 },
            formatter: val => val
          }
        },
        scales: {
          x: { stacked: false },
          y: { beginAtZero: true, max: 10, ticks: { stepSize: 1 } }
        }
      },
      plugins: [ChartDataLabels]
    })
  }
  // Demais gráficos permanecem iguais...
  if (graficoAtivo.value === 'distribuicao') {
    charts.pizza = new Chart(graficoPizzaTreinos.value, {
      type: 'pie',
      data: {
        labels: ['PFE', 'PFS', 'Técnico Misto', 'Técnico Fita', 'Físico-Técnico', 'Intervalo'],
        datasets: [{
          data: [23.51, 16.38, 22.3, 17.92, 17.32, 2.03],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
        }]
      },
      options: { plugins: { legend: { display: true } } }
    })
    charts.barras = new Chart(graficoBarrasMinutos.value, {
      type: 'bar',
      data: {
        labels: ['PFE', 'PFS', 'Técnico Misto', 'Técnico Fita', 'Físico-Técnico', 'Intervalo'],
        datasets: [{
          label: 'Minutos',
          data: [113, 79, 107, 86, 83, 10],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
        }]
      },
      options: { plugins: { legend: { display: false } } }
    })
  }
  if (graficoAtivo.value === 'percepcao-grupo') {
    charts.g1 = new Chart(graficoG1.value, {
      type: 'bar',
      data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        datasets: [
          { label: 'PFE', backgroundColor: '#ffe54c', data: [5, 4, 4, 4, 5, 3] },
          { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 4, 5, 2] },
          { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [5, 2, 4, 3, 3, 5] },
          { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [7, 6, 8, 5, 6, 3] }
        ]
      },
      options: { responsive: true, plugins: { legend: { display: true } }, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, max: 15 } } }
    })
    charts.g2 = new Chart(graficoG2.value, {
      type: 'bar',
      data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        datasets: [
          { label: 'PFE', backgroundColor: '#ffe54c', data: [5, 4, 4, 4, 5, 3] },
          { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 4, 5, 2] },
          { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [5, 2, 3, 2, 3, 5] },
          { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [7, 6, 8, 5, 6, 3] }
        ]
      },
      options: { responsive: true, plugins: { legend: { display: true } }, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, max: 15 } } }
    })
  }
  if (graficoAtivo.value === 'carga-agudo-cronica') {
    charts.carga = new Chart(graficoCarga.value, {
      type: 'bar',
      data: {
        labels: ['18', '19', '20', '21', '22', '23', '24'],
        datasets: [{ label: 'Carga', data: [1.2, 1.2, 1.1, 1.1, 1.1, 1.0, 0.8], backgroundColor: '#0D1036' }]
      },
      options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, suggestedMax: 2, ticks: { stepSize: 0.2 } } } }
    })
  }
  if (graficoAtivo.value === 'percepcao-fadiga') {
    charts.fadiga = new Chart(graficoFadiga.value, {
      type: 'bar',
      data: {
        labels: ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'DOM'],
        datasets: [{ label: 'Fadiga', data: [5, 4, 4, 3, 3, 3], backgroundColor: '#0D1036' }]
      },
      options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, suggestedMax: 6, ticks: { stepSize: 1 } } } }
    })
  }
  if (graficoAtivo.value === 'carga-semanal') {
    const atletas = ['Sofia', 'Laura', 'Isabela', 'Júlia', 'Amanda', 'Camila', 'Elisa']
    const pfe = [4630, 4019, 4134, 3740, 3636, 3611, 3161]
    const pfg = [1265, 922, 1019, 1167, 917, 1009, 869]
    const preventivo = [270, 150, 160, 0, 0, 0, 240]
    const tecnico = [4885, 4333, 4236, 3811, 3923, 3195, 1314]
    const totais = atletas.map((_, i) => pfe[i] + pfg[i] + preventivo[i] + tecnico[i])
    charts.total = new Chart(graficoTotal.value, {
      type: 'bar',
      data: { labels: atletas, datasets: [{ label: 'Carga Total', data: totais, backgroundColor: '#0D1036' }] },
      options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 2000 } } } }
    })
    charts.detalhado = new Chart(graficoDetalhado.value, {
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
      options: { responsive: true, plugins: { legend: { display: true } }, scales: { x: { stacked: false }, y: { stacked: false, beginAtZero: true, ticks: { stepSize: 2000 } } } }
    })
  }
  if (graficoAtivo.value === 'diferenca-semanas') {
    charts.diferenca = new Chart(graficoDiferenca.value, {
      type: 'bar',
      data: {
        labels: ['18', '19', '20', '21'],
        datasets: [
          { label: 'G1', data: [41, 34, 18, 35], backgroundColor: '#0d1f86' },
          { label: 'G2', data: [44, 46, 45, 46], backgroundColor: '#00c934' }
        ]
      },
      options: { responsive: true, plugins: { legend: { display: true } }, scales: { y: { beginAtZero: true, max: 60, ticks: { stepSize: 10 } } } }
    })
  }
}
onMounted(criarGraficos)
watch([graficoAtivo, filtros], criarGraficos)
</script>

<script setup>
import { defineComponent, ref } from 'vue';
import { Bar } from 'vue-chartjs';
import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend, ChartDataLabels);

const bolinhaColors = [
  '#007BFF', '#1e90ff', '#28c745', '#a0e518', '#fcea60',
  '#fdb940', '#fc8d3c', '#f6523c', '#d62c2c', '#990000', '#5c0000'
];

const chartData = {
  labels: ['Sofia', 'Laura', 'Isabela', 'Amanda', 'Camila', 'Beatriz', 'Elisa'],
  datasets: [
    { label: 'PFE', backgroundColor: '#ffe54c', data: [4, 3, 5, 4, 3, 4, 2] },
    { label: 'PFG', backgroundColor: '#b6f547', data: [5, 4, 4, 5, 4, 4, 3] },
    { label: 'TÉCNICO', backgroundColor: '#1f224f', data: [5, 5, 5, 5, 4, 4, 1] },
    { label: 'PREVENTIVO', backgroundColor: '#fc8d3c', data: [3, 3, 3, 2, 3, 2, 2] },
  ],
};

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      display: true,
      position: 'top',
    },
    datalabels: {
      anchor: 'end',
      align: 'start',
      color: '#000',
      font: { weight: 'bold', size: 11 },
      formatter: value => value + '%',
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 10,
      ticks: { stepSize: 1 },
    },
  },
};

const BarChartPercepcaoEsforco = defineComponent({
  name: 'BarChartPercepcaoEsforco',
  setup() {
    return () => (
      <Bar data={chartData} options={chartOptions} plugins={[ChartDataLabels]} />
    );
  },
});
</script>

<style scoped>
.relatorios-container { background: #f7f7f7; min-height: 100vh; }
.header { background: #001f7f; color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
.header-left { display: flex; align-items: center; gap: 10px; }
.logo { height: 40px; }
.filtros { display: flex; gap: 15px; align-items: center; font-size: 13px; }
.filtro-item { display: flex; flex-direction: column; }
.filtro-item label { margin-bottom: 2px; font-size: 12px; }
.filtro-item select, .filtro-item input { font-size: 13px; padding: 4px; border: none; border-radius: 4px; }
.nav-graficos { background: #fff; padding: 15px 20px; display: flex; gap: 10px; flex-wrap: wrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.nav-btn { padding: 8px 16px; border: 1px solid #ddd; background: #fff; color: #333; border-radius: 4px; cursor: pointer; font-size: 14px; transition: all 0.3s; }
.nav-btn.active, .nav-btn:hover { background: #001f7f; color: #fff; border-color: #001f7f; }
.graficos-content { padding: 20px; }
.grafico-container { background: #fff; margin-bottom: 20px; padding: 20px; box-shadow: 0 0 4px rgba(0,0,0,0.1); border-radius: 8px; }
.grafico-titulo { background: #222; color: #fff; font-size: 18px; text-align: center; padding: 10px; font-weight: bold; margin-bottom: 20px; border-radius: 4px; }
.escala-intensidade { display: flex; align-items: center; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
.bolinhas { display: flex; gap: 4px; margin-left: 10px; }
.bolinha { width: 22px; height: 22px; border-radius: 50%; display: inline-block; text-align: center; line-height: 22px; font-size: 12px; color: #fff; font-weight: bold; }
.legenda-escala { display: flex; gap: 30px; font-size: 12px; margin-left: 20px; }
.medias-gerais { display: flex; gap: 20px; justify-content: center; margin-top: 30px; }
.media-box { padding: 10px 20px; color: #000; font-size: 22px; font-weight: bold; text-align: center; border-radius: 4px; width: 120px; display: flex; flex-direction: column; align-items: center; }
.media-pfg { background: #b6f547; }
.media-pfe { background: #ffe54c; }
.media-tecnico { background: #1f224f; color: #fff; }
.media-preventivo { background: #fc8d3c; }
.media-box span { font-size: 13px; font-weight: normal; text-transform: uppercase; }

.legacy-percepcao-esforco {
  background: #f7f7f7;
  margin: 30px auto;
  border-radius: 8px;
  box-shadow: 0 0 8px rgba(0,0,0,0.04);
  max-width: 1100px;
  padding-bottom: 30px;
}
.legacy-percepcao-esforco .header {
  background: #001f7f;
  color: #fff;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  gap: 20px;
}
.legacy-percepcao-esforco .header img {
  height: 40px;
}
.legacy-percepcao-esforco .filtros {
  background: #fff;
  padding: 15px 20px;
  display: flex;
  gap: 20px;
  align-items: center;
  flex-wrap: wrap;
  border-bottom: 1px solid #eee;
}
.legacy-percepcao-esforco .filtros label {
  font-size: 14px;
  margin-right: 5px;
}
.legacy-percepcao-esforco .grafico-container {
  background: #fff;
  margin: 20px;
  padding: 20px;
  box-shadow: 0 0 4px rgba(0,0,0,0.1);
  border-radius: 8px;
}
.legacy-percepcao-esforco .grafico-titulo {
  background: #000;
  color: #fff;
  font-size: 18px;
  text-align: center;
  padding: 10px;
  font-weight: bold;
  border-radius: 4px;
  margin-bottom: 10px;
}
.legacy-percepcao-esforco .below-chart {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px 30px;
  background: #fefefe;
  border-radius: 8px;
  margin-top: 20px;
}
.legacy-percepcao-esforco .intensidade-scale {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;
}
.legacy-percepcao-esforco .scale-header {
  display: flex;
  align-items: center;
  gap: 10px;
}
.legacy-percepcao-esforco .bolinhas {
  display: flex;
  gap: 6px;
  margin-top: 5px;
}
.legacy-percepcao-esforco .bolinha {
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
.legacy-percepcao-esforco .legendas {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  margin-top: 5px;
  width: 100%;
}
.legacy-percepcao-esforco .summary-boxes {
  display: flex;
  gap: 20px;
}
.legacy-percepcao-esforco .summary-box {
  text-align: center;
  font-size: 26px;
  font-weight: bold;
  border-radius: 4px;
  padding: 15px 20px;
  width: 100px;
  color: #000;
  background: #eee;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.legacy-percepcao-esforco .summary-box.pfg { background: #b6f547; color: #000; }
.legacy-percepcao-esforco .summary-box.pfe { background: #ffe54c; color: #000; }
.legacy-percepcao-esforco .summary-box.tecnico { background: #1f224f; color: #fff; }
.legacy-percepcao-esforco .summary-box.preventivo { background: #fc8d3c; color: #000; }
</style> 