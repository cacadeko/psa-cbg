<template>
  <div>
    <h2>📊 Relatórios e Gráficos</h2>
    
    <!-- Filtros -->
    <div class="filtros-container">
      <div class="filtros">
        <div>
          <label for="tipo1">TIPO</label>
          <Dropdown v-model="filtros.tipo1" :options="tipos" optionLabel="label" optionValue="value" placeholder="PFG" />
        </div>
        <div>
          <label for="tipo2">TIPO</label>
          <Dropdown v-model="filtros.tipo2" :options="tipos" optionLabel="label" optionValue="value" placeholder="PFE" />
        </div>
        <div>
          <label for="tipo3">TIPO</label>
          <Dropdown v-model="filtros.tipo3" :options="tipos" optionLabel="label" optionValue="value" placeholder="TÉCNICO" />
        </div>
        <div>
          <label for="tag">TAG</label>
          <Dropdown v-model="filtros.tag" :options="tags" optionLabel="label" optionValue="value" placeholder="G1" />
        </div>
        <div>
          <label for="dataInicio">DATA INÍCIO</label>
          <Calendar v-model="filtros.dataInicio" dateFormat="yy-mm-dd" />
        </div>
        <div>
          <label for="dataFim">DATA FIM</label>
          <Calendar v-model="filtros.dataFim" dateFormat="yy-mm-dd" />
        </div>
        <Button label="Atualizar" icon="pi pi-refresh" @click="atualizarGraficos" />
      </div>
    </div>

    <!-- Tabs de Relatórios -->
    <TabView v-model:activeIndex="activeTab" class="relatorios-tabs">
      <TabPanel header="Distribuição Semanal">
        <div class="grafico-container">
          <h3>📊 Distribuição Semanal dos Tipos de Treino</h3>
          <div class="grafico-wrapper">
            <div class="grafico-pizza">
              <div class="pizza-chart">
                <div v-for="(item, index) in dadosDistribuicao" :key="item.tipo" 
                     class="pizza-slice" 
                     :style="getPizzaSliceStyle(index, item.percentual)">
                  <div class="slice-label">{{ item.tipo }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="legenda">
            <h4>Legenda</h4>
            <div class="legenda-item" v-for="item in dadosDistribuicao" :key="item.label">
              <span class="cor" :style="{ backgroundColor: getCorPorTipo(item.tipo) }"></span>
              {{ item.tipo }}: {{ item.percentual.toFixed(2) }}%
            </div>
          </div>
        </div>
      </TabPanel>

      <TabPanel header="Percepção de Esforço">
        <div class="grafico-container">
          <h3>🎯 Percepção de Esforço (Média da Semana)</h3>
          <div class="grafico-wrapper">
            <div class="grafico-linha">
              <div class="linha-grafico">
                <div v-for="(item, index) in dadosPSE" :key="item.dia" class="ponto-dados">
                  <div class="ponto pfg" :style="{ height: item.pfg * 20 + 'px' }" :title="`PFG: ${item.pfg}`"></div>
                  <div class="ponto pfe" :style="{ height: item.pfe * 20 + 'px' }" :title="`PFE: ${item.pfe}`"></div>
                  <div class="ponto tecnico" :style="{ height: item.tecnico * 20 + 'px' }" :title="`Técnico: ${item.tecnico}`"></div>
                  <div class="dia-label">{{ item.dia }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="medias-container">
            <div class="media-box media-pfg">
              <div class="media-valor">{{ medias.pfg }}</div>
              <div class="media-label">PFG MÉDIA GERAL</div>
            </div>
            <div class="media-box media-pfe">
              <div class="media-valor">{{ medias.pfe }}</div>
              <div class="media-label">PFE MÉDIA GERAL</div>
            </div>
            <div class="media-box media-tecnico">
              <div class="media-valor">{{ medias.tecnico }}</div>
              <div class="media-label">TÉCNICO MÉDIA GERAL</div>
            </div>
          </div>
          <div class="escala-intensidade">
            <div class="escala-titulo">INTENSIDADE DO TREINO</div>
            <div class="bolinhas">
              <div v-for="i in 11" :key="i-1" class="bolinha" :style="{ backgroundColor: coresIntensidade[i-1] }">
                {{ i-1 }}
              </div>
            </div>
            <div class="escala-labels">
              <span>LEVE</span>
              <span>MODERADO</span>
              <span>INTENSO</span>
              <span>MUITO INTENSO</span>
            </div>
          </div>
        </div>
      </TabPanel>

      <TabPanel header="Carga Agudo-Crônica">
        <div class="grafico-container">
          <h3>📈 Carga Agudo-Crônica</h3>
          <div class="grafico-wrapper">
            <div class="grafico-scatter">
              <div class="scatter-chart">
                <div v-for="item in dadosCarga" :key="item.data" 
                     class="scatter-ponto"
                     :style="{ 
                       left: (item.cargaCronica / 600) * 100 + '%',
                       bottom: (item.cargaAguda / 400) * 100 + '%'
                     }"
                     :title="`Carga Crônica: ${item.cargaCronica}, Carga Aguda: ${item.cargaAguda}`">
                </div>
              </div>
              <div class="scatter-labels">
                <div class="label-x">Carga Crônica</div>
                <div class="label-y">Carga Aguda</div>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>

      <TabPanel header="Percepção de Fadiga">
        <div class="grafico-container">
          <h3>😴 Percepção de Fadiga</h3>
          <div class="grafico-wrapper">
            <div class="grafico-barras">
              <div class="barras-chart">
                <div v-for="item in dadosFadiga" :key="item.dia" class="barra-container">
                  <div class="barra" :style="{ height: item.nivel * 30 + 'px' }" :title="`Fadiga: ${item.nivel}`"></div>
                  <div class="barra-label">{{ item.dia }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>

      <TabPanel header="Carga Semanal">
        <div class="grafico-container">
          <h3>📅 Carga Semanal</h3>
          <div class="grafico-wrapper">
            <div class="grafico-area">
              <div class="area-chart">
                <div v-for="(item, index) in dadosCargaSemanal" :key="item.semana" 
                     class="area-ponto"
                     :style="{ 
                       left: (index / (dadosCargaSemanal.length - 1)) * 100 + '%',
                       bottom: (item.carga / 400) * 100 + '%'
                     }"
                     :title="`${item.semana}: ${item.carga}`">
                </div>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>
    </TabView>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import relatoriosService from '../services/relatoriosService';

// Filtros
const filtros = ref({
  tipo1: 'PFG',
  tipo2: 'PFE',
  tipo3: 'TÉCNICO',
  tag: 'G1',
  dataInicio: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), // 7 dias atrás
  dataFim: new Date()
});

const activeTab = ref(0);

// Opções dos filtros
const tipos = ref([
  { label: 'PFG', value: 'PFG' },
  { label: 'PFE', value: 'PFE' },
  { label: 'TÉCNICO', value: 'TÉCNICO' }
]);

const tags = ref([
  { label: 'G1', value: 'G1' },
  { label: 'G2', value: 'G2' },
  { label: 'G3', value: 'G3' }
]);

// Dados dos gráficos
const dadosDistribuicao = ref([]);
const dadosPSE = ref([]);
const dadosCarga = ref([]);
const dadosFadiga = ref([]);
const dadosCargaSemanal = ref([]);
const medias = ref({
  pfg: 0,
  pfe: 0,
  tecnico: 0
});

const coresIntensidade = ref([
  '#007BFF', '#1e90ff', '#28c745', '#a0e518', '#fcea60',
  '#fdb940', '#fc8d3c', '#f6523c', '#d62c2c', '#990000', '#5c0000'
]);

const cores = ref([
  '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
]);

function getCorPorTipo(tipo: string): string {
  const coresMap: { [key: string]: string } = {
    'PFE': '#FF6384',
    'PFS': '#36A2EB',
    'Técnico Misto': '#FFCE56',
    'Técnico Fita': '#4BC0C0',
    'Físico-Técnico': '#9966FF',
    'Intervalo': '#FF9F40'
  };
  return coresMap[tipo] || '#999';
}

function getPizzaSliceStyle(index: number, percentual: number) {
  const totalPercentual = dadosDistribuicao.value.reduce((acc, item) => acc + item.percentual, 0);
  const startAngle = dadosDistribuicao.value
    .slice(0, index)
    .reduce((acc, item) => acc + (item.percentual / totalPercentual) * 360, 0);
  const angle = (percentual / totalPercentual) * 360;
  
  return {
    transform: `rotate(${startAngle}deg)`,
    background: `conic-gradient(${getCorPorTipo(dadosDistribuicao.value[index].tipo)} 0deg ${angle}deg, transparent ${angle}deg)`
  };
}

async function carregarDados() {
  try {
    dadosDistribuicao.value = await relatoriosService.getDistribuicaoSemanal();
    dadosPSE.value = await relatoriosService.getDadosPSE(
      filtros.value.dataInicio.toISOString().split('T')[0],
      filtros.value.dataFim.toISOString().split('T')[0]
    );
    dadosCarga.value = await relatoriosService.getCargaAgudoCronica();
    dadosFadiga.value = await relatoriosService.getDadosFadiga();
    dadosCargaSemanal.value = await relatoriosService.getCargaSemanal();
    
    const mediasData = await relatoriosService.getMediasPSE();
    medias.value = mediasData;
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
  }
}

function atualizarGraficos() {
  carregarDados();
}

onMounted(() => {
  carregarDados();
});
</script>

<style scoped>
.filtros-container {
  background: white;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filtros {
  display: flex;
  gap: 1rem;
  align-items: end;
  flex-wrap: wrap;
}

.filtros > div {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filtros label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.relatorios-tabs {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.grafico-container {
  padding: 2rem;
}

.grafico-wrapper {
  display: flex;
  justify-content: center;
  margin: 2rem 0;
  min-height: 400px;
}

/* Gráfico de Pizza */
.grafico-pizza {
  width: 300px;
  height: 300px;
  position: relative;
}

.pizza-chart {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
}

.pizza-slice {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
}

.slice-label {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 12px;
  font-weight: bold;
  color: white;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
}

/* Gráfico de Linha */
.grafico-linha {
  width: 100%;
  height: 300px;
  position: relative;
}

.linha-grafico {
  display: flex;
  justify-content: space-around;
  align-items: end;
  height: 200px;
  padding: 20px;
  border-bottom: 2px solid #ddd;
  border-left: 2px solid #ddd;
}

.ponto-dados {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
}

.ponto {
  width: 20px;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.ponto.pfg { background-color: #b6f547; }
.ponto.pfe { background-color: #ffe54c; }
.ponto.tecnico { background-color: #1f224f; }

.dia-label {
  font-size: 12px;
  font-weight: bold;
}

/* Gráfico Scatter */
.grafico-scatter {
  width: 100%;
  height: 400px;
  position: relative;
  border: 2px solid #ddd;
}

.scatter-chart {
  width: 100%;
  height: 100%;
  position: relative;
}

.scatter-ponto {
  position: absolute;
  width: 12px;
  height: 12px;
  background-color: #FF6384;
  border-radius: 50%;
  transform: translate(-50%, 50%);
}

.scatter-labels {
  position: absolute;
  bottom: -30px;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
}

.label-x, .label-y {
  font-size: 12px;
  font-weight: bold;
}

/* Gráfico de Barras */
.grafico-barras {
  width: 100%;
  height: 300px;
}

.barras-chart {
  display: flex;
  justify-content: space-around;
  align-items: end;
  height: 250px;
  padding: 20px;
  border-bottom: 2px solid #ddd;
  border-left: 2px solid #ddd;
}

.barra-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.barra {
  width: 40px;
  background-color: #36A2EB;
  border-radius: 4px 4px 0 0;
  transition: all 0.3s ease;
}

.barra-label {
  font-size: 12px;
  font-weight: bold;
}

/* Gráfico de Área */
.grafico-area {
  width: 100%;
  height: 300px;
  position: relative;
  border: 2px solid #ddd;
}

.area-chart {
  width: 100%;
  height: 100%;
  position: relative;
}

.area-ponto {
  position: absolute;
  width: 8px;
  height: 8px;
  background-color: #4BC0C0;
  border-radius: 50%;
  transform: translate(-50%, 50%);
}

.legenda {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 1rem;
}

.legenda-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.cor {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 1px solid #ccc;
}

.medias-container {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin: 2rem 0;
}

.media-box {
  padding: 1rem 1.5rem;
  border-radius: 8px;
  text-align: center;
  min-width: 120px;
}

.media-valor {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.media-label {
  font-size: 0.875rem;
  font-weight: 500;
}

.media-pfg {
  background-color: #b6f547;
  color: #000;
}

.media-pfe {
  background-color: #ffe54c;
  color: #000;
}

.media-tecnico {
  background-color: #1f224f;
  color: white;
}

.escala-intensidade {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
  padding: 1rem;
  background: #f9f9f9;
  border-radius: 8px;
}

.escala-titulo {
  font-weight: bold;
  color: #c00;
}

.bolinhas {
  display: flex;
  gap: 0.5rem;
}

.bolinha {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: bold;
  color: white;
}

.escala-labels {
  display: flex;
  justify-content: space-between;
  width: 100%;
  font-size: 0.75rem;
  margin-top: 0.5rem;
}
</style> 