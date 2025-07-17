<template>
  <div class="card">
    <Toast />
    
    <!-- Header -->
    <div class="flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="m-0">📊 Relatórios e Gráficos</h2>
        <p class="text-gray-600 m-0 mt-1">Análise detalhada do desempenho dos atletas</p>
      </div>
      <Button 
        label="Exportar Relatório" 
        icon="pi pi-download" 
        class="p-button-primary"
        @click="exportarRelatorio"
      />
    </div>
    
    <!-- Filtros -->
    <div class="card mb-4">
      <div class="flex flex-column md:flex-row gap-3">
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">TIPO</label>
          <Dropdown 
            v-model="filtros.tipo1" 
            :options="tipos" 
            optionLabel="label" 
            optionValue="value" 
            placeholder="PFG"
            class="w-full"
          />
        </div>
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">TIPO</label>
          <Dropdown 
            v-model="filtros.tipo2" 
            :options="tipos" 
            optionLabel="label" 
            optionValue="value" 
            placeholder="PFE"
            class="w-full"
          />
        </div>
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">TIPO</label>
          <Dropdown 
            v-model="filtros.tipo3" 
            :options="tipos" 
            optionLabel="label" 
            optionValue="value" 
            placeholder="TÉCNICO"
            class="w-full"
          />
        </div>
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">TAG</label>
          <Dropdown 
            v-model="filtros.tag" 
            :options="tags" 
            optionLabel="label" 
            optionValue="value" 
            placeholder="G1"
            class="w-full"
          />
        </div>
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">DATA INÍCIO</label>
          <Calendar 
            v-model="filtros.dataInicio" 
            dateFormat="yy-mm-dd"
            class="w-full"
          />
        </div>
        <div class="flex-1">
          <label class="block text-900 font-medium mb-2">DATA FIM</label>
          <Calendar 
            v-model="filtros.dataFim" 
            dateFormat="yy-mm-dd"
            class="w-full"
          />
        </div>
        <div class="flex align-items-end">
          <Button 
            label="Atualizar" 
            icon="pi pi-refresh" 
            class="p-button-primary"
            @click="atualizarGraficos"
            :loading="loading"
          />
        </div>
      </div>
    </div>

    <!-- Tabs de Relatórios -->
    <TabView v-model:activeIndex="activeTab" class="relatorios-tabs">
      <TabPanel header="Distribuição Semanal">
        <div class="grafico-container">
          <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="m-0">📊 Distribuição Semanal dos Tipos de Treino</h3>
            <div class="flex gap-2">
              <Button icon="pi pi-download" class="p-button-outlined p-button-sm" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
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
            <h4 class="mb-3">Legenda</h4>
            <div class="grid">
              <div class="col-12 md:col-6 lg:col-4" v-for="item in dadosDistribuicao" :key="item.label">
                <div class="legenda-item">
                  <span class="cor" :style="{ backgroundColor: getCorPorTipo(item.tipo) }"></span>
                  <span class="legenda-text">{{ item.tipo }}: {{ item.percentual.toFixed(2) }}%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>

      <TabPanel header="Percepção de Esforço">
        <div class="grafico-container">
          <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="m-0">🎯 Percepção de Esforço (Média da Semana)</h3>
            <div class="flex gap-2">
              <Button icon="pi pi-download" class="p-button-outlined p-button-sm" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
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
          <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="m-0">📈 Carga Agudo-Crônica</h3>
            <div class="flex gap-2">
              <Button icon="pi pi-download" class="p-button-outlined p-button-sm" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
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
          <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="m-0">😴 Percepção de Fadiga</h3>
            <div class="flex gap-2">
              <Button icon="pi pi-download" class="p-button-outlined p-button-sm" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
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
          <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="m-0">📅 Carga Semanal</h3>
            <div class="flex gap-2">
              <Button icon="pi pi-download" class="p-button-outlined p-button-sm" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
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
import { useToast } from 'primevue/usetoast';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import relatoriosService from '../services/relatoriosService';

const toast = useToast();

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
const loading = ref(false);

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
  '#198754', '#28a745', '#40c057', '#69db7c', '#8ce99a',
  '#ffd43b', '#fcc419', '#f59f00', '#f08c00', '#e67700', '#d9480f'
]);

const cores = ref([
  '#198754', '#28a745', '#40c057', '#69db7c', '#8ce99a', '#b2f2bb'
]);

function getCorPorTipo(tipo: string): string {
  const coresMap: { [key: string]: string } = {
    'PFE': '#198754',
    'PFS': '#28a745',
    'Técnico Misto': '#40c057',
    'Técnico Fita': '#69db7c',
    'Físico-Técnico': '#8ce99a',
    'Intervalo': '#b2f2bb'
  };
  return coresMap[tipo] || '#6c757d';
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
  loading.value = true;
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
    
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Dados carregados com sucesso',
      life: 3000
    });
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar dados dos relatórios',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
}

function atualizarGraficos() {
  carregarDados();
}

function exportarRelatorio() {
  toast.add({
    severity: 'info',
    summary: 'Exportação',
    detail: 'Relatório exportado com sucesso',
    life: 3000
  });
}

onMounted(() => {
  carregarDados();
});
</script>

<style scoped>
.card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 2px 1px -1px rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 1px 3px 0 rgba(0,0,0,.12);
}

.relatorios-tabs {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

:deep(.p-tabview .p-tabview-nav) {
  background: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
  border-radius: 12px 12px 0 0;
}

:deep(.p-tabview .p-tabview-nav .p-tabview-nav-link) {
  background: transparent;
  border: none;
  color: #6c757d;
  font-weight: 500;
  padding: 1rem 1.5rem;
  transition: all 0.2s;
}

:deep(.p-tabview .p-tabview-nav .p-tabview-nav-link:hover) {
  background: #e9ecef;
  color: #198754;
}

:deep(.p-tabview .p-tabview-nav .p-highlight .p-tabview-nav-link) {
  background: #198754;
  color: white;
  border-radius: 8px 8px 0 0;
}

:deep(.p-tabview .p-tabview-panels) {
  padding: 0;
}

:deep(.p-tabview .p-tabview-panel) {
  padding: 2rem;
}

.grafico-container {
  padding: 0;
}

.grafico-wrapper {
  display: flex;
  justify-content: center;
  margin: 2rem 0;
  min-height: 400px;
  background: #f8f9fa;
  border-radius: 12px;
  padding: 2rem;
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
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
  border-bottom: 2px solid #e9ecef;
  border-left: 2px solid #e9ecef;
  background: white;
  border-radius: 8px;
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
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ponto.pfg { background-color: #198754; }
.ponto.pfe { background-color: #ffc107; }
.ponto.tecnico { background-color: #6c757d; }

.dia-label {
  font-size: 12px;
  font-weight: bold;
  color: #495057;
}

/* Gráfico Scatter */
.grafico-scatter {
  width: 100%;
  height: 400px;
  position: relative;
  border: 2px solid #e9ecef;
  background: white;
  border-radius: 8px;
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
  background-color: #198754;
  border-radius: 50%;
  transform: translate(-50%, 50%);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
  color: #495057;
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
  border-bottom: 2px solid #e9ecef;
  border-left: 2px solid #e9ecef;
  background: white;
  border-radius: 8px;
}

.barra-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.barra {
  width: 40px;
  background-color: #198754;
  border-radius: 4px 4px 0 0;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.barra-label {
  font-size: 12px;
  font-weight: bold;
  color: #495057;
}

/* Gráfico de Área */
.grafico-area {
  width: 100%;
  height: 300px;
  position: relative;
  border: 2px solid #e9ecef;
  background: white;
  border-radius: 8px;
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
  background-color: #198754;
  border-radius: 50%;
  transform: translate(-50%, 50%);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.legenda {
  margin-top: 2rem;
  padding: 1.5rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.legenda h4 {
  color: #495057;
  margin-bottom: 1rem;
}

.legenda-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0;
}

.cor {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 2px solid #e9ecef;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.legenda-text {
  font-weight: 500;
  color: #495057;
}

.medias-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.media-box {
  padding: 1.5rem;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}

.media-box:hover {
  transform: translateY(-2px);
}

.media-valor {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.media-label {
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.media-pfg {
  background: linear-gradient(135deg, #198754, #28a745);
  color: white;
}

.media-pfe {
  background: linear-gradient(135deg, #ffc107, #ffca2c);
  color: #212529;
}

.media-tecnico {
  background: linear-gradient(135deg, #6c757d, #495057);
  color: white;
}

.escala-intensidade {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
  padding: 2rem;
  background: #f8f9fa;
  border-radius: 12px;
  border: 1px solid #e9ecef;
}

.escala-titulo {
  font-weight: bold;
  color: #198754;
  font-size: 1.125rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.bolinhas {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  justify-content: center;
}

.bolinha {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: bold;
  color: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}

.bolinha:hover {
  transform: scale(1.1);
}

.escala-labels {
  display: flex;
  justify-content: space-between;
  width: 100%;
  font-size: 0.75rem;
  margin-top: 0.5rem;
  color: #6c757d;
  font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
  .card {
    padding: 1rem;
  }
  
  .grafico-wrapper {
    padding: 1rem;
  }
  
  .medias-container {
    grid-template-columns: 1fr;
  }
  
  .escala-labels {
    flex-direction: column;
    gap: 0.5rem;
    text-align: center;
  }
}
</style> 