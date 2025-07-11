<template>
  <div class="percepcao-fadiga">
    <div class="header">
      <div class="header-content">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo">
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>
    </div>

    <div class="filtros">
      <div class="filtro-item">
        <label for="tag">TAG</label>
        <Dropdown v-model="filtros.tag" :options="tags" optionLabel="name" optionValue="value" placeholder="Selecione TAG" />
      </div>
      <div class="filtro-item">
        <label for="dataInicio">DATA</label>
        <Calendar v-model="filtros.dataInicio" dateFormat="yy-mm-dd" />
      </div>
      <div class="filtro-item">
        <label for="dataFim">&nbsp;</label>
        <Calendar v-model="filtros.dataFim" dateFormat="yy-mm-dd" />
      </div>
    </div>

    <div class="grafico-container">
      <div class="grafico-titulo">PERCEPÇÃO DE FADIGA (MÉDIA DA SEMANA)</div>
      <canvas ref="graficoFadiga" height="110"></canvas>
    </div>

    <div class="referencia">
      <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo-ref">
      <strong class="titulo-ref">REFERÊNCIA</strong>
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
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
import annotationPlugin from 'chartjs-plugin-annotation'

Chart.register(...registerables, annotationPlugin)

const graficoFadiga = ref<HTMLCanvasElement>()
let chart: Chart | null = null

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

const criarGrafico = () => {
  if (!graficoFadiga.value) return

  if (chart) {
    chart.destroy()
  }

  const ctx = graficoFadiga.value.getContext('2d')
  if (!ctx) return

  chart = new Chart(ctx, {
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
                xAdjust: -10,
                yAdjust: -10,
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
    }
  })
}

const carregarDados = async () => {
  try {
    // Aqui você faria a chamada para a API
    // const response = await apiService.getPercepcaoFadiga(filtros.value)
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
.percepcao-fadiga {
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
  padding: 10px 20px;
  background-color: white;
  justify-content: flex-end;
  font-size: 13px;
}

.filtro-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filtro-item label {
  font-weight: bold;
  color: #333;
}

.grafico-container {
  background-color: white;
  padding: 20px;
  text-align: center;
  margin: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.grafico-titulo {
  background-color: black;
  color: white;
  padding: 10px;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
  border-radius: 4px;
}

.referencia {
  text-align: center;
  margin: 20px;
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.logo-ref {
  height: 20px;
  margin-bottom: 10px;
}

.titulo-ref {
  color: #c00;
  display: block;
  margin-bottom: 15px;
}

.bolinhas {
  display: flex;
  justify-content: center;
  gap: 5px;
  margin-bottom: 10px;
  flex-wrap: wrap;
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
  flex-wrap: wrap;
}

.legenda-escala span {
  text-align: center;
  min-width: 120px;
}

@media (max-width: 768px) {
  .filtros {
    flex-direction: column;
    align-items: stretch;
  }
  
  .legenda-escala {
    flex-direction: column;
    gap: 10px;
  }
  
  .bolinhas {
    gap: 3px;
  }
  
  .bolinha {
    width: 20px;
    height: 20px;
    line-height: 20px;
    font-size: 10px;
  }
}
</style> 