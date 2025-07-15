<template>
  <div>
    <div class="header-actions">
      <h2>⚽ Jogos</h2>
      <Button label="Novo Jogo" icon="pi pi-plus" @click="showCreateDialog = true" />
    </div>

    <!-- Filtros -->
    <div class="filters-container">
      <div class="filters">
        <div>
          <label for="search">Buscar</label>
          <InputText v-model="filters.global.value" placeholder="Adversário, local ou campeonato..." />
        </div>
        <div>
          <label for="data_inicio">Data Início</label>
          <Calendar v-model="filters.data_jogo.value" dateFormat="yy-mm-dd" />
        </div>
        <div>
          <label for="data_fim">Data Fim</label>
          <Calendar v-model="filters.data_jogo.value" dateFormat="yy-mm-dd" />
        </div>
        <Button label="Limpar" icon="pi pi-refresh" @click="clearFilters" />
      </div>
    </div>

    <!-- Tabela -->
    <DataTable 
      :value="jogos" 
      :loading="loading"
      :paginator="true" 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['adversario', 'local_jogo', 'campeonato']"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <Column field="data_jogo" header="Data" sortable>
        <template #body="{ data }">
          {{ formatDate(data.data_jogo) }}
        </template>
      </Column>
      <Column field="adversario" header="Adversário" sortable>
        <template #body="{ data }">
          <div class="jogo-info">
            <div class="jogo-adversario">{{ data.adversario }}</div>
            <div class="jogo-campeonato">{{ data.campeonato || 'Amistoso' }}</div>
          </div>
        </template>
      </Column>
      <Column field="local_jogo" header="Local" sortable>
        <template #body="{ data }">
          <span class="local-badge">{{ data.local_jogo }}</span>
        </template>
      </Column>
      <Column field="resultado" header="Resultado" sortable>
        <template #body="{ data }">
          <span class="resultado-badge" :class="getResultadoClass(data.resultado)">
            {{ data.resultado || 'Não jogado' }}
          </span>
        </template>
      </Column>
      <Column header="Ações" :exportable="false" style="min-width:8rem">
        <template #body="{ data }">
          <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editJogo(data)" />
          <Button icon="pi pi-trash" class="p-button-rounded p-button-warning" @click="confirmDeleteJogo(data)" />
        </template>
      </Column>
      <template #empty>
        Nenhum jogo cadastrado.
      </template>
    </DataTable>

    <!-- Dialog Criar/Editar -->
    <Dialog v-model:visible="showCreateDialog" :header="editingJogo ? 'Editar Jogo' : 'Novo Jogo'" modal class="p-fluid">
      <div class="form-grid">
        <div class="field">
          <label for="data_jogo">Data do Jogo *</label>
          <Calendar id="data_jogo" v-model="form.data_jogo" dateFormat="yy-mm-dd" required :class="{ 'p-invalid': submitted && !form.data_jogo }" />
          <small class="p-error" v-if="submitted && !form.data_jogo">Data do jogo é obrigatória.</small>
        </div>
        
        <div class="field">
          <label for="adversario">Adversário *</label>
          <InputText id="adversario" v-model="form.adversario" required :class="{ 'p-invalid': submitted && !form.adversario }" />
          <small class="p-error" v-if="submitted && !form.adversario">Adversário é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="local_jogo">Local *</label>
          <InputText id="local_jogo" v-model="form.local_jogo" required :class="{ 'p-invalid': submitted && !form.local_jogo }" />
          <small class="p-error" v-if="submitted && !form.local_jogo">Local é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="campeonato">Campeonato</label>
          <InputText id="campeonato" v-model="form.campeonato" />
        </div>
        
        <div class="field">
          <label for="resultado">Resultado</label>
          <InputText id="resultado" v-model="form.resultado" placeholder="Ex: 2x1, 0x0" />
        </div>
        
        <div class="field">
          <label for="observacoes">Observações</label>
          <Textarea id="observacoes" v-model="form.observacoes" rows="3" />
        </div>
      </div>
      
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="closeDialog" />
        <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveJogo" />
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
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import api from '../services/api';
import { FilterMatchMode } from 'primevue/api';

const confirm = useConfirm();
const toast = useToast();

// Estado
interface Jogo {
  id?: number;
  data_jogo: string | Date | null;
  adversario: string;
  local_jogo: string;
  campeonato?: string;
  resultado?: string;
  observacoes?: string;
}
const editingJogo = ref<Jogo | null>(null);
const jogos = ref<Jogo[]>([]);
const loading = ref(false);
const showCreateDialog = ref(false);
const submitted = ref(false);

// Filtros
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  adversario: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  local_jogo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  campeonato: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  data_jogo: { value: null, matchMode: FilterMatchMode.EQUALS },
  resultado: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

// Formulário
const form = ref<{
  data_jogo: Date | null,
  adversario: string,
  local_jogo: string,
  campeonato: string,
  resultado: string,
  observacoes: string
}>({
  data_jogo: null,
  adversario: '',
  local_jogo: '',
  campeonato: '',
  resultado: '',
  observacoes: ''
});

// Carregar dados
async function loadJogos() {
  loading.value = true;
  try {
    const response = await api.get('/jogos');
    jogos.value = Array.isArray(response.data)
      ? response.data.filter(jogo => jogo && jogo.id)
      : [];
  } catch (error) {
    console.error('Erro ao carregar jogos:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar jogos',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
}

// Formatação
function formatDate(date: string | Date | null) {
  if (!date) return '';
  const d = new Date(date);
  return isNaN(d.getTime()) ? '' : d.toLocaleDateString('pt-BR');
}

function getResultadoClass(resultado: string) {
  if (!resultado) return 'resultado-pendente';
  const [golsCasa, golsAdversario] = resultado.split('x').map(Number);
  if (golsCasa > golsAdversario) return 'resultado-vitoria';
  if (golsCasa < golsAdversario) return 'resultado-derrota';
  return 'resultado-empate';
}

// Filtros
function clearFilters() {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    adversario: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    local_jogo: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    campeonato: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    data_jogo: { value: null, matchMode: FilterMatchMode.EQUALS },
    resultado: { value: null, matchMode: FilterMatchMode.CONTAINS }
  };
}

// CRUD
function editJogo(jogo: any) {
  editingJogo.value = jogo;
  form.value = {
    data_jogo: jogo.data_jogo ? new Date(jogo.data_jogo) : null,
    adversario: jogo.adversario,
    local_jogo: jogo.local_jogo,
    campeonato: jogo.campeonato || '',
    resultado: jogo.resultado || '',
    observacoes: jogo.observacoes || ''
  };
  showCreateDialog.value = true;
}

function confirmDeleteJogo(jogo: any) {
  confirm.require({
    message: `Tem certeza que deseja excluir o jogo contra "${jogo.adversario}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => deleteJogo(jogo.id)
  });
}

async function deleteJogo(id: number) {
  try {
    await api.delete(`/jogos/${id}`);
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Jogo excluído com sucesso',
      life: 3000
    });
    loadJogos();
  } catch (error) {
    console.error('Erro ao excluir jogo:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir jogo',
      life: 3000
    });
  }
}

function closeDialog() {
  showCreateDialog.value = false;
  editingJogo.value = null;
  submitted.value = false;
  form.value = {
    data_jogo: null,
    adversario: '',
    local_jogo: '',
    campeonato: '',
    resultado: '',
    observacoes: ''
  };
}

async function saveJogo() {
  submitted.value = true;
  if (!form.value.data_jogo || !form.value.adversario || !form.value.local_jogo) {
    return;
  }
  try {
    const data = {
      ...form.value,
      data_jogo: form.value.data_jogo instanceof Date ? form.value.data_jogo.toISOString().split('T')[0] : form.value.data_jogo
    };
    if (editingJogo.value && editingJogo.value.id) {
      await api.put(`/jogos/${editingJogo.value.id}`, data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Jogo atualizado com sucesso',
        life: 3000
      });
    } else {
      await api.post('/jogos', data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Jogo criado com sucesso',
        life: 3000
      });
    }
    closeDialog();
    loadJogos();
  } catch (error) {
    console.error('Erro ao salvar jogo:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao salvar jogo',
      life: 3000
    });
  }
}

onMounted(() => {
  loadJogos();
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

.jogo-info {
  display: flex;
  flex-direction: column;
}

.jogo-adversario {
  font-weight: 500;
}

.jogo-campeonato {
  font-size: 0.875rem;
  color: #6b7280;
}

.local-badge {
  background: #f3f4f6;
  color: #374151;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.resultado-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.resultado-vitoria {
  background: #dcfce7;
  color: #166534;
}

.resultado-derrota {
  background: #fee2e2;
  color: #991b1b;
}

.resultado-empate {
  background: #fef3c7;
  color: #92400e;
}

.resultado-pendente {
  background: #f3f4f6;
  color: #6b7280;
}
</style> 