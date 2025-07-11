<template>
  <div class="carga-semanal">
    <div class="header">
      <div class="header-content">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo">
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>
      
      <div class="filtros-header">
        <div class="filtro-item">
          <label for="tag">TAG</label>
          <Dropdown v-model="filtros.tag" :options="tags" optionLabel="name" optionValue="value" placeholder="Selecione TAG" />
        </div>
        <div class="filtro-item">
          <label for="dataInicio">DATA INÍCIO</label>
          <Calendar v-model="filtros.dataInicio" dateFormat="yy-mm-dd" />
        </div>
        <div class="filtro-item">
          <label for="dataFim">DATA FIM</label>
          <Calendar v-model="filtros.dataFim" dateFormat="yy-mm-dd" />
        </div>
      </div>
    </div>

    <div class="formula-info">
      <strong>Fórmula de Cálculo:</strong> PSE × Tempo do Treino (minutos)<br>
      <span class="exemplo">ex: PSE da Atleta = 5 – Tempo de Treino = 50 minutos</span>
    </div>

    <div class="grafico-container">
      <div class="grafico-titulo">CARGA SEMANAL TOTAL POR ATLETA</div>
      <canvas ref="graficoTotal" height="60"></canvas>
    </div>

    <div class="grafico-container">
      <div class="grafico-titulo">CARGA DETALHADA POR TIPO DE TREINO</div>
      <canvas ref="graficoDetalhado" height="75"></canvas>
      
      <div class="legenda">
        <span><span class="cor" style="background:#ffe54c;"></span> PFE</span>
        <span><span class="cor" style="background:#b6f547;"></span> PFG</span>
        <span><span class="cor" style="background:#fc8d3c;"></span> PREVENTIVO</span>
        <span><span class="cor" style="background:#0D1036;"></span> TÉCNICO</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const graficoTotal = ref<HTMLCanvasElement>()
const graficoDetalhado = ref<HTMLCanvasElement>()
let chartTotal: Chart | null = null
let chartDetalhado: Chart | null = null

const filtros = ref({
  tag: 'G1',
  dataInicio: new Date('2025-05-18'),
  dataFim: new Date('2025-05-24')
})

const tags = ref([
  { name: 'G1', value: 'G1' },
  { name: 'G2', value: 'G2' },
  { name: 'G3', value: 'G3' }
])

const atletas = ['Sofia Nunes', 'Laura Rocha', 'Isabela Moura', 'Júlia Fernandes', 'Amanda Ribeiro', 'Camila Duarte', 'Elisa Martins']
const pfe = [4630, 4019, 4134, 3740, 3636, 3611, 3016]
const pfg = [1265, 922, 1019, 1167, 917, 1009, 869]
const preventivo = [270, 0, 150, 600, 360, 240, 240]
const tecnico = [4885, 4333, 4236, 3811, 3923, 3195, 1314]
const totais = atletas.map((_, i) => pfe[i] + pfg[i] + preventivo[i] + tecnico[i])

const criarGraficoTotal = () => {
  if (!graficoTotal.value) return

  if (chartTotal) {
    chartTotal.destroy()
  }

  const ctx = graficoTotal.value.getContext('2d')
  if (!ctx) return

  chartTotal = new Chart(ctx, {
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
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 2000 }
        }
      }
    }
  })
}

const criarGraficoDetalhado = () => {
  if (!graficoDetalhado.value) return

  if (chartDetalhado) {
    chartDetalhado.destroy()
  }

  const ctx = graficoDetalhado.value.getContext('2d')
  if (!ctx) return

  chartDetalhado = new Chart(ctx, {
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
    }
  })
}

const carregarDados = async () => {
  try {
    // Aqui você faria a chamada para a API
    // const response = await apiService.getCargaSemanal(filtros.value)
    // Atualizar dados dos gráficos
    criarGraficoTotal()
    criarGraficoDetalhado()
  } catch (error) {
    console.error('Erro ao carregar dados:', error)
  }
}

watch(filtros, () => {
  carregarDados()
}, { deep: true })

onMounted(() => {
  carregarDados()
})
</script>

<style scoped>
.carga-semanal {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

.header {
  background-color: #001f7f;
  color: white;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  height: 40px;
}

.filtros-header {
  display: flex;
  align-items: center;
  gap: 20px;
  font-size: 13px;
  color: white;
}

.filtro-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filtro-item label {
  font-weight: bold;
  color: white;
}

.formula-info {
  text-align: right;
  padding: 0 20px 10px;
  font-size: 12px;
  background-color: white;
  margin: 0;
}

.exemplo {
  font-style: italic;
}

.grafico-container {
  background-color: white;
  margin: 20px;
  padding: 20px;
  text-align: center;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.grafico-titulo {
  background-color: black;
  color: white;
  font-size: 18px;
  padding: 10px;
  font-weight: bold;
  margin-bottom: 10px;
  border-radius: 4px;
}

.legenda {
  display: flex;
  justify-content: center;
  gap: 20px;
  font-size: 12px;
  margin-top: 10px;
  flex-wrap: wrap;
}

.cor {
  width: 14px;
  height: 14px;
  display: inline-block;
  margin-right: 5px;
  vertical-align: middle;
  border-radius: 2px;
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filtros-header {
    flex-direction: column;
    gap: 10px;
  }
  
  .legenda {
    flex-direction: column;
    gap: 10px;
  }
}
</style> 