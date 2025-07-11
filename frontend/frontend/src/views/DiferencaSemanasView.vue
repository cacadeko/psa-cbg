<template>
  <div class="diferenca-semanas">
    <div class="header">
      <div class="header-content">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo">
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>
    </div>

    <div class="filtros">
      <div class="filtro-item">
        <label for="dataInicioG1">DATA G1</label>
        <Calendar v-model="filtros.dataInicioG1" dateFormat="yy-mm-dd" />
      </div>
      <div class="filtro-item">
        <label for="dataFimG1">&nbsp;</label>
        <Calendar v-model="filtros.dataFimG1" dateFormat="yy-mm-dd" />
      </div>
      <div class="filtro-item">
        <label for="dataInicioG2">DATA G2</label>
        <Calendar v-model="filtros.dataInicioG2" dateFormat="yy-mm-dd" />
      </div>
      <div class="filtro-item">
        <label for="dataFimG2">&nbsp;</label>
        <Calendar v-model="filtros.dataFimG2" dateFormat="yy-mm-dd" />
      </div>
    </div>

    <div class="grafico-container">
      <div class="grafico-titulo">DIFERENÇA ENTRE AS SEMANAS</div>
      <div class="grupos-info">GRUPOS <span class="g1-color">G1</span> | <span class="g2-color">G2</span></div>
      <canvas ref="graficoDiferenca" height="100"></canvas>
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
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const graficoDiferenca = ref<HTMLCanvasElement>()
let chart: Chart | null = null

const filtros = ref({
  dataInicioG1: new Date('2025-05-18'),
  dataFimG1: new Date('2025-05-24'),
  dataInicioG2: new Date('2025-04-27'),
  dataFimG2: new Date('2025-05-24')
})

const criarGrafico = () => {
  if (!graficoDiferenca.value) return

  if (chart) {
    chart.destroy()
  }

  const ctx = graficoDiferenca.value.getContext('2d')
  if (!ctx) return

  chart = new Chart(ctx, {
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
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 60,
          ticks: { stepSize: 10 }
        }
      }
    }
  })
}

const carregarDados = async () => {
  try {
    // Aqui você faria a chamada para a API
    // const response = await apiService.getDiferencaSemanas(filtros.value)
    // Atualizar dados do gráfico
    criarGrafico()
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
.diferenca-semanas {
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

.filtros {
  display: flex;
  gap: 15px;
  align-items: center;
  font-size: 13px;
  background-color: white;
  padding: 10px 20px;
  justify-content: flex-end;
  flex-wrap: wrap;
}

.filtro-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filtro-item label {
  display: block;
  margin-bottom: 2px;
  font-weight: bold;
  color: #333;
}

.grafico-container {
  background-color: white;
  margin: 0;
  padding: 20px;
  box-shadow: 0 0 4px rgba(0,0,0,0.1);
  text-align: center;
  border-radius: 8px;
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

.grupos-info {
  margin: 10px 0;
  font-weight: bold;
}

.g1-color {
  color: #0d1f86;
}

.g2-color {
  color: #00c934;
}

.bloco-volume {
  display: flex;
  justify-content: center;
  gap: 50px;
  background-color: #fefefe;
  padding: 20px 0;
  margin: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  flex-wrap: wrap;
}

.bloco {
  text-align: center;
  padding: 15px 25px;
  font-weight: bold;
  flex: 1;
  min-width: 300px;
  border-radius: 8px;
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

@media (max-width: 768px) {
  .filtros {
    flex-direction: column;
    align-items: stretch;
  }
  
  .bloco-volume {
    flex-direction: column;
    gap: 20px;
  }
  
  .bloco {
    min-width: auto;
  }
}
</style> 