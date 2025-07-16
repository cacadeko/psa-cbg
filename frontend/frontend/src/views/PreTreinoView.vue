<template>
  <div class="pre-treino-container">
    <div class="header">
      <h1>Pré-Treino</h1>
      <Button label="Novo Pré-Treino" @click="abrirNovoPreTreino" class="p-button-primary" />
    </div>

    <DataTable 
      :value="preTreinos" 
      :loading="loading"
      :paginator="true" 
      :rows="10"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['atleta_id', 'data_avaliacao', 'tqr_recuperacao', 'fadiga_bem_estar', 'nivel_fadiga', 'qualidade_sono', 'horas_sono', 'tempo_dormir', 'vezes_acordou_noite', 'dor_muscular_geral', 'escala_dor_visual', 'local_dor_especifica', 'tipo_dor_muscular', 'nivel_estresse', 'humor', 'medicacao_uso', 'motivo_medicacao', 'observacoes']"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <template #header>
        <div class="flex justify-content-between">
          <span class="p-input-icon-left">
            <i class="pi pi-search" />
            <InputText v-model="filters.global.value" placeholder="Buscar..." />
          </span>
        </div>
      </template>

      <Column field="id" header="ID" sortable style="width: 60px"></Column>
      <Column field="atleta_id" header="Atleta ID" sortable style="width: 80px"></Column>
      <Column field="data_avaliacao" header="Data Avaliação" sortable style="width: 120px">
        <template #body="slotProps">
          {{ formatarData(slotProps.data.data_avaliacao) }}
        </template>
      </Column>
      <Column field="tqr_recuperacao" header="TQR Recuperação" sortable style="width: 100px"></Column>
      <Column field="fadiga_bem_estar" header="Fadiga/Bem-estar" sortable style="width: 110px"></Column>
      <Column field="nivel_fadiga" header="Nível Fadiga" sortable style="width: 100px"></Column>
      <Column field="qualidade_sono" header="Qualidade Sono" sortable style="width: 110px"></Column>
      <Column field="horas_sono" header="Horas Sono" sortable style="width: 100px"></Column>
      <Column field="tempo_dormir" header="Tempo Dormir" sortable style="width: 110px"></Column>
      <Column field="motivo_sono_inquieto" header="Motivo Sono" sortable style="width: 120px">
        <template #body="slotProps">
          {{ slotProps.data.motivo_sono_inquieto || '-' }}
        </template>
      </Column>
      <Column field="vezes_acordou_noite" header="Vezes Acordou" sortable style="width: 110px"></Column>
      <Column field="dor_muscular_geral" header="Dor Muscular" sortable style="width: 100px"></Column>
      <Column field="escala_dor_visual" header="Escala Dor" sortable style="width: 90px"></Column>
      <Column field="local_dor_especifica" header="Local Dor" sortable style="width: 100px">
        <template #body="slotProps">
          {{ slotProps.data.local_dor_especifica || '-' }}
        </template>
      </Column>
      <Column field="tipo_dor_muscular" header="Tipo Dor" sortable style="width: 100px">
        <template #body="slotProps">
          {{ slotProps.data.tipo_dor_muscular || '-' }}
        </template>
      </Column>
      <Column field="nivel_estresse" header="Estresse" sortable style="width: 80px"></Column>
      <Column field="humor" header="Humor" sortable style="width: 80px"></Column>
      <Column field="periodo_pre_menstrual" header="Pré-Menstrual" sortable style="width: 100px">
        <template #body="slotProps">
          {{ slotProps.data.periodo_pre_menstrual ? 'Sim' : 'Não' }}
        </template>
      </Column>
      <Column field="periodo_menstrual" header="Menstrual" sortable style="width: 90px">
        <template #body="slotProps">
          {{ slotProps.data.periodo_menstrual ? 'Sim' : 'Não' }}
        </template>
      </Column>
      <Column field="medicacao_uso" header="Medicação" sortable style="width: 120px">
        <template #body="slotProps">
          {{ slotProps.data.medicacao_uso || '-' }}
        </template>
      </Column>
      <Column field="motivo_medicacao" header="Motivo Med." sortable style="width: 120px">
        <template #body="slotProps">
          {{ slotProps.data.motivo_medicacao || '-' }}
        </template>
      </Column>
      <Column field="observacoes" header="Observações" sortable style="width: 120px">
        <template #body="slotProps">
          {{ slotProps.data.observacoes || '-' }}
        </template>
      </Column>
      
      <Column header="Ações" style="width: 150px">
        <template #body="slotProps">
          <Button 
            icon="pi pi-pencil" 
            class="p-button-sm p-button-text" 
            @click="editarPreTreino(slotProps.data)" 
          />
          <Button 
            icon="pi pi-trash" 
            class="p-button-sm p-button-text p-button-danger" 
            @click="confirmarExclusao(slotProps.data)" 
          />
        </template>
      </Column>
    </DataTable>

    <!-- Modal de Cadastro/Edição -->
    <Dialog 
      v-model:visible="showDialog" 
      :header="editando ? 'Editar Pré-Treino' : 'Novo Pré-Treino'"
      :style="{ width: '80vw' }" 
      :modal="true"
      :closable="true"
    >
      <form @submit.prevent="salvarPreTreino" class="pre-treino-form">
        <div class="form-grid">
          <!-- Primeira coluna -->
          <div class="form-col">
            <div class="p-field">
              <label for="atleta_id">Atleta *</label>
              <select id="atleta_id" v-model="preTreinoForm.atleta_id" class="p-inputtext p-component" required>
                <option value="">Selecione o atleta</option>
                <option v-for="atleta in atletas" :key="atleta.id" :value="atleta.id">{{ atleta.nome }}</option>
              </select>
            </div>
            
            <div class="p-field">
              <label for="data_avaliacao">Data de Avaliação *</label>
              <Calendar 
                id="data_avaliacao" 
                v-model="preTreinoForm.data_avaliacao" 
                dateFormat="dd/mm/yy" 
                class="p-inputtext p-component" 
                required 
              />
            </div>

            <div class="p-field">
              <label for="tqr_recuperacao">TQR Recuperação (1-10)</label>
              <Slider v-model="preTreinoForm.tqr_recuperacao" :min="1" :max="10" :step="1" />
              <span>{{ preTreinoForm.tqr_recuperacao }}</span>
            </div>

            <div class="p-field">
              <label for="fadiga_bem_estar">Fadiga/Bem-estar (1-5)</label>
              <Slider v-model="preTreinoForm.fadiga_bem_estar" :min="1" :max="5" :step="1" />
              <span>{{ preTreinoForm.fadiga_bem_estar }}</span>
            </div>

            <div class="p-field">
              <label for="nivel_fadiga">Nível de Fadiga (1-10)</label>
              <Slider v-model="preTreinoForm.nivel_fadiga" :min="1" :max="10" :step="1" />
              <span>{{ preTreinoForm.nivel_fadiga }}</span>
            </div>

            <div class="p-field">
              <label for="qualidade_sono">Qualidade do Sono (1-5)</label>
              <Slider v-model="preTreinoForm.qualidade_sono" :min="1" :max="5" :step="1" />
              <span>{{ preTreinoForm.qualidade_sono }}</span>
            </div>
          </div>

          <!-- Segunda coluna -->
          <div class="form-col">
            <div class="p-field">
              <label for="horas_sono">Horas de Sono</label>
              <select id="horas_sono" v-model="preTreinoForm.horas_sono" class="p-inputtext p-component" required>
                <option value="Menos que 6 horas">Menos que 6 horas</option>
                <option value="Entre 6 e 7 horas">Entre 6 e 7 horas</option>
                <option value="8 horas ou mais">8 horas ou mais</option>
              </select>
            </div>

            <div class="p-field">
              <label for="tempo_dormir">Tempo para Dormir</label>
              <select id="tempo_dormir" v-model="preTreinoForm.tempo_dormir" class="p-inputtext p-component" required>
                <option value="Menos que 30 min">Menos que 30 min</option>
                <option value="Entre 30 min e 1h">Entre 30 min e 1h</option>
                <option value="Mais que 1h">Mais que 1h</option>
              </select>
            </div>

            <div class="p-field">
              <label for="vezes_acordou_noite">Vezes que Acordou na Noite</label>
              <select id="vezes_acordou_noite" v-model="preTreinoForm.vezes_acordou_noite" class="p-inputtext p-component" required>
                <option value="1 vez">1 vez</option>
                <option value="2 a 4 vezes">2 a 4 vezes</option>
                <option value="4 vezes ou mais">4 vezes ou mais</option>
              </select>
            </div>

            <div class="p-field">
              <label for="dor_muscular_geral">Dor Muscular Geral (1-5)</label>
              <Slider v-model="preTreinoForm.dor_muscular_geral" :min="1" :max="5" :step="1" />
              <span>{{ preTreinoForm.dor_muscular_geral }}</span>
            </div>

            <div class="p-field">
              <label for="escala_dor_visual">Escala de Dor Visual (1-10)</label>
              <Slider v-model="preTreinoForm.escala_dor_visual" :min="1" :max="10" :step="1" />
              <span>{{ preTreinoForm.escala_dor_visual }}</span>
            </div>

            <div class="p-field">
              <label for="nivel_estresse">Nível de Estresse (1-5)</label>
              <Slider v-model="preTreinoForm.nivel_estresse" :min="1" :max="5" :step="1" />
              <span>{{ preTreinoForm.nivel_estresse }}</span>
            </div>

            <div class="p-field">
              <label for="humor">Humor (1-5)</label>
              <Slider v-model="preTreinoForm.humor" :min="1" :max="5" :step="1" />
              <span>{{ preTreinoForm.humor }}</span>
            </div>
          </div>

          <!-- Terceira coluna -->
          <div class="form-col">
            <div class="p-field">
              <label for="motivo_sono_inquieto">Motivo do Sono Inquieto</label>
              <Textarea 
                id="motivo_sono_inquieto" 
                v-model="preTreinoForm.motivo_sono_inquieto" 
                rows="3" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <label for="local_dor_especifica">Local da Dor Específica</label>
              <InputText 
                id="local_dor_especifica" 
                v-model="preTreinoForm.local_dor_especifica" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <label for="tipo_dor_muscular">Tipo de Dor Muscular</label>
              <InputText 
                id="tipo_dor_muscular" 
                v-model="preTreinoForm.tipo_dor_muscular" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <label for="medicacao_uso">Medicação em Uso</label>
              <Textarea 
                id="medicacao_uso" 
                v-model="preTreinoForm.medicacao_uso" 
                rows="3" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <label for="motivo_medicacao">Motivo da Medicação</label>
              <Textarea 
                id="motivo_medicacao" 
                v-model="preTreinoForm.motivo_medicacao" 
                rows="3" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <label for="observacoes">Observações</label>
              <Textarea 
                id="observacoes" 
                v-model="preTreinoForm.observacoes" 
                rows="3" 
                class="p-inputtext p-component" 
              />
            </div>

            <div class="p-field">
              <div class="checkbox-group">
                <Checkbox 
                  id="periodo_pre_menstrual" 
                  v-model="preTreinoForm.periodo_pre_menstrual" 
                  :binary="true" 
                />
                <label for="periodo_pre_menstrual">Período Pré-Menstrual</label>
              </div>
            </div>

            <div class="p-field">
              <div class="checkbox-group">
                <Checkbox 
                  id="periodo_menstrual" 
                  v-model="preTreinoForm.periodo_menstrual" 
                  :binary="true" 
                />
                <label for="periodo_menstrual">Período Menstrual</label>
              </div>
            </div>

            <div style="margin-top: 2rem;">
              <Button 
                :label="editando ? 'Atualizar' : 'Salvar'" 
                type="submit" 
                class="custom-primary-btn" 
                style="width: 100%; background-color: #2563eb; border-color: #2563eb; color: #fff;" 
              />
            </div>
          </div>
        </div>
      </form>
      <div v-if="erroCadastro" class="erro-msg">{{ erroCadastro }}</div>
    </Dialog>

    <!-- Confirmação de Exclusão -->
    <ConfirmDialog />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import ConfirmDialog from 'primevue/confirmdialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import Slider from 'primevue/slider';
import Checkbox from 'primevue/checkbox';
import { useConfirm } from 'primevue/useconfirm';
import { FilterMatchMode } from 'primevue/api';
import api from '../services/api';

const preTreinos = ref<any[]>([]);
const atletas = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const editando = ref(false);
const erroCadastro = ref('');
const confirm = useConfirm();

const preTreinoForm = ref<any>({
  id: null,
  atleta_id: '',
  data_avaliacao: new Date(),
  tqr_recuperacao: 10,
  fadiga_bem_estar: 3,
  nivel_fadiga: 5,
  qualidade_sono: 3,
  horas_sono: 'Entre 6 e 7 horas',
  tempo_dormir: 'Menos que 30 min',
  motivo_sono_inquieto: '',
  vezes_acordou_noite: '1 vez',
  dor_muscular_geral: 3,
  escala_dor_visual: 5,
  local_dor_especifica: '',
  tipo_dor_muscular: '',
  nivel_estresse: 3,
  humor: 3,
  periodo_pre_menstrual: false,
  periodo_menstrual: false,
  medicacao_uso: '',
  motivo_medicacao: '',
  observacoes: ''
});

const filters = ref<any>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  atleta_id: { value: null, matchMode: FilterMatchMode.EQUALS },
  data_avaliacao: { value: null, matchMode: FilterMatchMode.EQUALS },
  tqr_recuperacao: { value: null, matchMode: FilterMatchMode.EQUALS },
  fadiga_bem_estar: { value: null, matchMode: FilterMatchMode.EQUALS },
  nivel_fadiga: { value: null, matchMode: FilterMatchMode.EQUALS },
  qualidade_sono: { value: null, matchMode: FilterMatchMode.EQUALS },
  horas_sono: { value: null, matchMode: FilterMatchMode.EQUALS },
  tempo_dormir: { value: null, matchMode: FilterMatchMode.EQUALS },
  vezes_acordou_noite: { value: null, matchMode: FilterMatchMode.EQUALS },
  dor_muscular_geral: { value: null, matchMode: FilterMatchMode.EQUALS },
  escala_dor_visual: { value: null, matchMode: FilterMatchMode.EQUALS },
  nivel_estresse: { value: null, matchMode: FilterMatchMode.EQUALS },
  humor: { value: null, matchMode: FilterMatchMode.EQUALS }
});

async function carregarAtletas() {
  try {
    const { data } = await api.get('/atletas');
    atletas.value = data;
  } catch (e: any) {
    console.error('Erro ao carregar atletas:', e);
    erro.value = 'Erro ao carregar atletas: ' + (e.response?.data?.message || e.message);
  }
}

async function carregarPreTreinos() {
  loading.value = true;
  try {
    const { data } = await api.get('/pre-treino');
    preTreinos.value = data;
  } catch (e: any) {
    erro.value = 'Erro ao carregar pré-treinos';
    console.error('Erro:', e);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  carregarPreTreinos();
  carregarAtletas();
});

function editarPreTreino(preTreino: any) {
  editando.value = true;
  preTreinoForm.value = { 
    ...preTreino,
    data_avaliacao: new Date(preTreino.data_avaliacao),
    periodo_pre_menstrual: Boolean(preTreino.periodo_pre_menstrual),
    periodo_menstrual: Boolean(preTreino.periodo_menstrual)
  };
  showDialog.value = true;
}

function abrirNovoPreTreino() {
  editando.value = false;
  preTreinoForm.value = {
    id: null,
    atleta_id: '',
    data_avaliacao: new Date(),
    tqr_recuperacao: 10,
    fadiga_bem_estar: 3,
    nivel_fadiga: 5,
    qualidade_sono: 3,
    horas_sono: 'Entre 6 e 7 horas',
    tempo_dormir: 'Menos que 30 min',
    motivo_sono_inquieto: '',
    vezes_acordou_noite: '1 vez',
    dor_muscular_geral: 3,
    escala_dor_visual: 5,
    local_dor_especifica: '',
    tipo_dor_muscular: '',
    nivel_estresse: 3,
    humor: 3,
    periodo_pre_menstrual: false,
    periodo_menstrual: false,
    medicacao_uso: '',
    motivo_medicacao: '',
    observacoes: ''
  };
  showDialog.value = true;
}

function confirmarExclusao(preTreino: any) {
  confirm.require({
    message: `Deseja realmente excluir o pré-treino do atleta ID ${preTreino.atleta_id}?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirPreTreino(preTreino.id),
  });
}

async function excluirPreTreino(id: number) {
  try {
    await api.delete(`/pre-treino/${id}`);
    await carregarPreTreinos();
  } catch (e: any) {
    erro.value = 'Erro ao excluir pré-treino';
    console.error('Erro:', e);
  }
}

async function salvarPreTreino() {
  erroCadastro.value = '';
  
  try {
    const dadosParaEnviar = {
      ...preTreinoForm.value,
      data_avaliacao: formatarDataParaAPI(preTreinoForm.value.data_avaliacao),
      atleta_id: parseInt(preTreinoForm.value.atleta_id)
    };

    if (editando.value) {
      await api.put('/pre-treino', dadosParaEnviar);
    } else {
      const { id, ...novoPreTreino } = dadosParaEnviar;
      await api.post('/pre-treino', novoPreTreino);
    }
    
    showDialog.value = false;
    await carregarPreTreinos();
  } catch (e: any) {
    console.error('Erro ao salvar pré-treino:', e);
    erroCadastro.value = e?.response?.data?.error || (editando.value ? 'Erro ao atualizar pré-treino' : 'Erro ao cadastrar pré-treino');
  }
}

function formatarData(data: string): string {
  if (!data) return '-';
  return new Date(data).toLocaleDateString('pt-BR');
}

function formatarDataParaAPI(data: Date): string {
  return data.toISOString().split('T')[0];
}

function getAtletaNome(atletaId: number): string {
  if (!atletaId) return '-';
  const atleta = atletas.value.find(a => a.id === atletaId);
  return atleta ? atleta.nome : `ID: ${atletaId}`;
}
</script>

<style scoped>
.pre-treino-container {
  padding: 2rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.header h1 {
  margin: 0;
  color: #333;
}

.pre-treino-form {
  margin-top: 1rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.form-col {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.p-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.p-field label {
  font-weight: 600;
  color: #333;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.checkbox-group label {
  margin: 0;
  font-weight: normal;
}

.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
  text-align: center;
}

.custom-primary-btn {
  background-color: #2563eb !important;
  border-color: #2563eb !important;
  color: #fff !important;
}

.custom-primary-btn:hover {
  background-color: #1d4ed8 !important;
  border-color: #1d4ed8 !important;
}
</style> 