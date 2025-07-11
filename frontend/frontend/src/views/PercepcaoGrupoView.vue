<template>
  <div class="percepcao-grupo">
    <div class="header">
      <div class="header-content">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="Logo CBG" class="logo">
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>

      <div class="filtros-header">
        <div class="filtro-item">
          <label>GRUPO</label>
          <Dropdown v-model="filtros.grupo1" :options="grupos" optionLabel="name" optionValue="value" placeholder="Selecione Grupo" />
        </div>
        <div class="filtro-item">
          <label>GRUPO</label>
          <Dropdown v-model="filtros.grupo2" :options="grupos" optionLabel="name" optionValue="value" placeholder="Selecione Grupo" />
        </div>
        <div class="filtro-item">
          <label>DATA</label>
          <Calendar v-model="filtros.dataInicio" dateFormat="yy-mm-dd" />
        </div>
        <div class="filtro-item">
          <label>&nbsp;</label>
          <Calendar v-model="filtros.dataFim" dateFormat="yy-mm-dd" />
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
      <canvas ref="graficoG1" height="90"></canvas>

      <div class="grupo-label">G2</div>
      <canvas ref="graficoG2" height="90"></canvas>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const graficoG1 = ref<HTMLCanvasElement>()
const graficoG2 = ref<HTMLCanvasElement>()
let chartG1: Chart | null = null
let chartG2: Chart | null = null

const filtros = ref({
  grupo1: 'G1',
  grupo2: 'G2',
  dataInicio: new Date('2025-05-18'),
  dataFim: new Date('2025-05-24')
})

const grupos = ref([
  { name: 'G1', value: 'G1' },
  { name: 'G2', value: 'G2' },
  { name: 'G3', value: 'G3' }
])

const baseOptions = {
  type: 'bar',
  options: {
    responsive: true,
    plugins: {
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
        max: 15,
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
        barThickness: 25
      }
    }
  }
}

const criarGraficoG1 = () => {
  if (!graficoG1.value) return

  if (chartG1) {
    chartG1.destroy()
  }

  const ctx = graficoG1.value.getContext('2d')
  if (!ctx) return

  chartG1 = new Chart(ctx, {
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
  })
}

const criarGraficoG2 = () => {
  if (!graficoG2.value) return

  if (chartG2) {
    chartG2.destroy()
  }

  const ctx = graficoG2.value.getContext('2d')
  if (!ctx) return

  chartG2 = new Chart(ctx, {
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
  })
}

const carregarDados = async () => {
  try {
    // Aqui você faria a chamada para a API
    // const response = await apiService.getPercepcaoGrupo(filtros.value)
    // Atualizar dados dos gráficos
    criarGraficoG1()
    criarGraficoG2()
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
.percepcao-grupo {
  font-family: Arial, sans-serif;
  background-color: #f7f7f7;
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

.header {
  background-color: #001f7f;
  color: white;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo {
  height: 40px;
}

.filtros-header {
  display: flex;
  align-items: center;
  gap: 15px;
  color: white;
  font-size: 13px;
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

.grafico-container {
  background-color: white;
  margin: 20px;
  padding: 20px;
  box-shadow: 0 0 4px rgba(0,0,0,0.1);
  border-radius: 8px;
}

.grafico-titulo {
  background-color: black;
  color: white;
  font-size: 18px;
  text-align: center;
  padding: 10px;
  font-weight: bold;
  margin-bottom: 20px;
  border-radius: 4px;
}

.legenda {
  text-align: left;
  padding: 10px 0 0 10px;
  font-size: 14px;
  margin-bottom: 20px;
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
  border-radius: 2px;
}

.grupo-label {
  font-weight: bold;
  font-size: 20px;
  margin: 20px 0 10px 10px;
  color: #333;
}

canvas {
  margin-bottom: 30px;
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
    text-align: center;
  }
  
  .legenda span {
    display: block;
    margin-bottom: 5px;
  }
}
</style> 