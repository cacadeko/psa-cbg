<template>
  <div>
    <div class="header-actions">
      <h2>😴 PFE - Percepção de Fadiga Específica</h2>
      <Button label="Nova Avaliação" icon="pi pi-plus" @click="showCreateDialog = true" />
    </div>

    <!-- Filtros -->
    <div class="filters-container">
      <div class="filters">
        <div>
          <label for="search">Buscar</label>
          <InputText v-model="filters.search" placeholder="Atleta ou descrição..." />
        </div>
        <div>
          <label for="atleta">Atleta</label>
          <Dropdown v-model="filters.atleta_id" :options="atletas" optionLabel="label" optionValue="value" placeholder="Todos os atletas" showClear />
        </div>
        <div>
          <label for="data_inicio">Data Início</label>
          <Calendar v-model="filters.dataInicio" dateFormat="yy-mm-dd" />
        </div>
        <div>
          <label for="data_fim">Data Fim</label>
          <Calendar v-model="filters.dataFim" dateFormat="yy-mm-dd" />
        </div>
        <Button label="Limpar" icon="pi pi-refresh" @click="clearFilters" />
      </div>
    </div>

    <!-- Tabela -->
    <DataTable 
      :value="pfeList" 
      :loading="loading"
      :paginator="true" 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['atleta_nome', 'descricao']"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <Column field="data" header="Data" sortable>
        <template #body="{ data }">
          {{ formatDate(data.data) }}
        </template>
      </Column>
      <Column field="atleta_nome" header="Atleta" sortable>
        <template #body="{ data }">
          <div class="atleta-info">
            <div class="atleta-name">{{ data.atleta_nome }}</div>
            <div class="atleta-idade">{{ data.atleta_idade }} anos</div>
          </div>
        </template>
      </Column>
      <Column field="descricao" header="Descrição" sortable>
        <template #body="{ data }">
          <div class="descricao-info">
            <div class="descricao-texto">{{ data.descricao }}</div>
            <div class="descricao-turno">{{ data.turno || 'Não informado' }}</div>
          </div>
        </template>
      </Column>
      <Column field="nota_pse" header="Nota PSE" sortable>
        <template #body="{ data }">
          <span class="pse-badge" :class="getPSEClass(data.nota_pse)">
            {{ data.nota_pse }}/10
          </span>
        </template>
      </Column>
      <Column field="tempo_treino" header="Tempo Treino" sortable>
        <template #body="{ data }">
          {{ data.tempo_treino ? `${data.tempo_treino} min` : '-' }}
        </template>
      </Column>
      <Column field="nivel_fadiga" header="Nível Fadiga" sortable>
        <template #body="{ data }">
          <div class="fadiga-info">
            <div class="fadiga-nivel">{{ data.nivel_fadiga }}/10</div>
            <div class="fadiga-status" :class="getFadigaClass(data.nivel_fadiga)">
              {{ getFadigaStatus(data.nivel_fadiga) }}
            </div>
          </div>
        </template>
      </Column>
      <Column header="Ações" :exportable="false" style="min-width:8rem">
        <template #body="{ data }">
          <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editPFE(data)" />
          <Button icon="pi pi-trash" class="p-button-rounded p-button-warning" @click="confirmDeletePFE(data)" />
        </template>
      </Column>
    </DataTable>

    <!-- Dialog Criar/Editar -->
    <Dialog v-model:visible="showCreateDialog" :header="editingPFE ? 'Editar Avaliação PFE' : 'Nova Avaliação PFE'" modal class="p-fluid">
      <div class="form-grid">
        <div class="field">
          <label for="atleta_id">Atleta *</label>
          <Dropdown id="atleta_id" v-model="form.atleta_id" :options="atletas" optionLabel="label" optionValue="value" placeholder="Selecione o atleta" required :class="{ 'p-invalid': submitted && !form.atleta_id }" />
          <small class="p-error" v-if="submitted && !form.atleta_id">Atleta é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="data">Data *</label>
          <Calendar id="data" v-model="form.data" dateFormat="yy-mm-dd" required :class="{ 'p-invalid': submitted && !form.data }" />
          <small class="p-error" v-if="submitted && !form.data">Data é obrigatória.</small>
        </div>
        
        <div class="field">
          <label for="descricao">Descrição *</label>
          <InputText id="descricao" v-model="form.descricao" required :class="{ 'p-invalid': submitted && !form.descricao }" />
          <small class="p-error" v-if="submitted && !form.descricao">Descrição é obrigatória.</small>
        </div>
        
        <div class="field">
          <label for="turno">Turno</label>
          <Dropdown id="turno" v-model="form.turno" :options="turnos" optionLabel="label" optionValue="value" placeholder="Selecione o turno" />
        </div>
        
        <div class="field">
          <label for="nota_pse">Nota PSE *</label>
          <InputNumber id="nota_pse" v-model="form.nota_pse" :min="0" :max="10" required :class="{ 'p-invalid': submitted && !form.nota_pse }" />
          <small class="p-error" v-if="submitted && !form.nota_pse">Nota PSE é obrigatória.</small>
        </div>
        
        <div class="field">
          <label for="tempo_treino">Tempo de Treino (min)</label>
          <InputNumber id="tempo_treino" v-model="form.tempo_treino" :min="0" :max="300" />
        </div>
        
        <div class="field">
          <label for="nivel_fadiga">Nível de Fadiga *</label>
          <InputNumber id="nivel_fadiga" v-model="form.nivel_fadiga" :min="0" :max="10" required :class="{ 'p-invalid': submitted && !form.nivel_fadiga }" />
          <small class="p-error" v-if="submitted && !form.nivel_fadiga">Nível de fadiga é obrigatório.</small>
        </div>
        
        <div class="field full-width">
          <label for="observacoes">Observações</label>
          <Textarea id="observacoes" v-model="form.observacoes" rows="3" />
        </div>
      </div>
      
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="closeDialog" />
        <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="savePFE" />
      </template>
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
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import api from '../services/api';

const confirm = useConfirm();
const toast = useToast();

// Estado
const pfeList = ref([]);
const atletas = ref([]);
const loading = ref(false);
const showCreateDialog = ref(false);
const submitted = ref(false);
const editingPFE = ref(null);

// Filtros
const filters = ref({
  search: '',
  atleta_id: null,
  dataInicio: null,
  dataFim: null
});

// Formulário
const form = ref({
  atleta_id: null,
  data: null,
  descricao: '',
  turno: null,
  nota_pse: null,
  tempo_treino: null,
  nivel_fadiga: null,
  observacoes: ''
});

// Opções de turno
const turnos = ref([
  { label: 'Manhã', value: 'Manhã' },
  { label: 'Tarde', value: 'Tarde' },
  { label: 'Noite', value: 'Noite' }
]);

// Carregar dados
async function loadPFE() {
  loading.value = true;
  try {
    const response = await api.get('/pfe');
    pfeList.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar PFE:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar avaliações PFE',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
}

async function loadAtletas() {
  try {
    const response = await api.get('/atletas');
    atletas.value = response.data.map((atleta: any) => ({
      label: atleta.nome,
      value: atleta.id
    }));
  } catch (error) {
    console.error('Erro ao carregar atletas:', error);
  }
}

// Formatação
function formatDate(date: string) {
  return new Date(date).toLocaleDateString('pt-BR');
}

function getPSEClass(nota: number) {
  if (nota <= 3) return 'pse-baixo';
  if (nota <= 6) return 'pse-medio';
  return 'pse-alto';
}

function getFadigaClass(nivel: number) {
  if (nivel <= 3) return 'fadiga-baixa';
  if (nivel <= 6) return 'fadiga-media';
  return 'fadiga-alta';
}

function getFadigaStatus(nivel: number) {
  if (nivel <= 3) return 'Baixa';
  if (nivel <= 6) return 'Média';
  return 'Alta';
}

// Filtros
function clearFilters() {
  filters.value = {
    search: '',
    atleta_id: null,
    dataInicio: null,
    dataFim: null
  };
}

// CRUD
function editPFE(pfe: any) {
  editingPFE.value = pfe;
  form.value = {
    atleta_id: pfe.atleta_id,
    data: new Date(pfe.data),
    descricao: pfe.descricao,
    turno: pfe.turno || null,
    nota_pse: pfe.nota_pse,
    tempo_treino: pfe.tempo_treino,
    nivel_fadiga: pfe.nivel_fadiga,
    observacoes: pfe.observacoes || ''
  };
  showCreateDialog.value = true;
}

function confirmDeletePFE(pfe: any) {
  confirm.require({
    message: `Tem certeza que deseja excluir a avaliação PFE do atleta "${pfe.atleta_nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => deletePFE(pfe.id)
  });
}

async function deletePFE(id: number) {
  try {
    await api.delete(`/pfe/${id}`);
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Avaliação PFE excluída com sucesso',
      life: 3000
    });
    loadPFE();
  } catch (error) {
    console.error('Erro ao excluir PFE:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir avaliação PFE',
      life: 3000
    });
  }
}

function closeDialog() {
  showCreateDialog.value = false;
  editingPFE.value = null;
  submitted.value = false;
  form.value = {
    atleta_id: null,
    data: null,
    descricao: '',
    turno: null,
    nota_pse: null,
    tempo_treino: null,
    nivel_fadiga: null,
    observacoes: ''
  };
}

async function savePFE() {
  submitted.value = true;
  
  if (!form.value.atleta_id || !form.value.data || !form.value.descricao || form.value.nota_pse === null || form.value.nivel_fadiga === null) {
    return;
  }

  try {
    const data = {
      ...form.value,
      data: form.value.data.toISOString().split('T')[0],
      nota_pse: Number(form.value.nota_pse),
      nivel_fadiga: Number(form.value.nivel_fadiga),
      tempo_treino: form.value.tempo_treino ? Number(form.value.tempo_treino) : null
    };

    if (editingPFE.value) {
      await api.put(`/pfe/${editingPFE.value.id}`, data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Avaliação PFE atualizada com sucesso',
        life: 3000
      });
    } else {
      await api.post('/pfe', data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Avaliação PFE criada com sucesso',
        life: 3000
      });
    }
    
    closeDialog();
    loadPFE();
  } catch (error) {
    console.error('Erro ao salvar PFE:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao salvar avaliação PFE',
      life: 3000
    });
  }
}

onMounted(() => {
  loadPFE();
  loadAtletas();
});
</script>

<style scoped>
.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.filters-container {
  background: white;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filters {
  display: flex;
  gap: 1rem;
  align-items: end;
  flex-wrap: wrap;
}

.filters > div {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filters label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.field.full-width {
  grid-column: 1 / -1;
}

.atleta-info {
  display: flex;
  flex-direction: column;
}

.atleta-name {
  font-weight: 500;
}

.atleta-idade {
  font-size: 0.875rem;
  color: #6b7280;
}

.descricao-info {
  display: flex;
  flex-direction: column;
}

.descricao-texto {
  font-weight: 500;
}

.descricao-turno {
  font-size: 0.875rem;
  color: #6b7280;
}

.pse-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.pse-baixo {
  background: #dcfce7;
  color: #166534;
}

.pse-medio {
  background: #fef3c7;
  color: #92400e;
}

.pse-alto {
  background: #fee2e2;
  color: #991b1b;
}

.fadiga-info {
  display: flex;
  flex-direction: column;
}

.fadiga-nivel {
  font-weight: 500;
}

.fadiga-status {
  font-size: 0.875rem;
  font-weight: 500;
}

.fadiga-baixa {
  color: #166534;
}

.fadiga-media {
  color: #92400e;
}

.fadiga-alta {
  color: #991b1b;
}
</style> 