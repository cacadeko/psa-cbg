<template>
  <div>
    <div class="header-actions">
      <h2>👨‍💼 Treinadores</h2>
      <Button label="Novo Treinador" icon="pi pi-plus" @click="showCreateDialog = true" />
    </div>

    <!-- Filtros -->
    <div class="filters-container">
      <div class="filters">
        <div>
          <label for="search">Buscar</label>
          <InputText v-model="filters.search" placeholder="Nome, email ou especialidade..." />
        </div>
        <div>
          <label for="especialidade">Especialidade</label>
          <Dropdown v-model="filters.especialidade" :options="especialidades" optionLabel="label" optionValue="value" placeholder="Todas" showClear />
        </div>
        <Button label="Limpar" icon="pi pi-refresh" @click="clearFilters" />
      </div>
    </div>

    <!-- Tabela -->
    <DataTable 
      :value="treinadores" 
      :loading="loading"
      :paginator="true" 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['nome', 'email', 'especialidade']"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <Column field="nome" header="Nome" sortable>
        <template #body="{ data }">
          <div class="atleta-info">
            <div class="atleta-name">{{ data.nome }}</div>
            <div class="atleta-email">{{ data.email }}</div>
          </div>
        </template>
      </Column>
      <Column field="telefone" header="Telefone" sortable>
        <template #body="{ data }">
          {{ data.telefone || '-' }}
        </template>
      </Column>
      <Column field="especialidade" header="Especialidade" sortable>
        <template #body="{ data }">
          <span class="especialidade-badge">{{ data.especialidade || 'Não informada' }}</span>
        </template>
      </Column>
      <Column field="data_contratacao" header="Data Contratação" sortable>
        <template #body="{ data }">
          {{ data.data_contratacao ? formatDate(data.data_contratacao) : '-' }}
        </template>
      </Column>
      <Column header="Ações" :exportable="false" style="min-width:8rem">
        <template #body="{ data }">
          <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editTreinador(data)" />
          <Button icon="pi pi-trash" class="p-button-rounded p-button-warning" @click="confirmDeleteTreinador(data)" />
        </template>
      </Column>
    </DataTable>

    <!-- Dialog Criar/Editar -->
    <Dialog v-model:visible="showCreateDialog" :header="editingTreinador ? 'Editar Treinador' : 'Novo Treinador'" modal class="p-fluid">
      <div class="form-grid">
        <div class="field">
          <label for="nome">Nome *</label>
          <InputText id="nome" v-model="form.nome" required autofocus :class="{ 'p-invalid': submitted && !form.nome }" />
          <small class="p-error" v-if="submitted && !form.nome">Nome é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="email">E-mail *</label>
          <InputText id="email" v-model="form.email" type="email" required :class="{ 'p-invalid': submitted && !form.email }" />
          <small class="p-error" v-if="submitted && !form.email">E-mail é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="telefone">Telefone</label>
          <InputText id="telefone" v-model="form.telefone" />
        </div>
        
        <div class="field">
          <label for="especialidade">Especialidade</label>
          <InputText id="especialidade" v-model="form.especialidade" />
        </div>
        
        <div class="field">
          <label for="data_contratacao">Data de Contratação</label>
          <Calendar id="data_contratacao" v-model="form.data_contratacao" dateFormat="yy-mm-dd" />
        </div>
        
        <div class="field">
          <label for="observacoes">Observações</label>
          <Textarea id="observacoes" v-model="form.observacoes" rows="3" />
        </div>
      </div>
      
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="closeDialog" />
        <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveTreinador" />
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
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import api from '../services/api';

const confirm = useConfirm();
const toast = useToast();

// Estado
const treinadores = ref([]);
const loading = ref(false);
const showCreateDialog = ref(false);
const submitted = ref(false);
const editingTreinador = ref(null);

// Filtros
const filters = ref({
  search: '',
  especialidade: null
});

// Formulário
const form = ref({
  nome: '',
  email: '',
  telefone: '',
  especialidade: '',
  data_contratacao: null,
  observacoes: ''
});

// Opções de especialidade
const especialidades = ref([
  { label: 'Fisioterapia', value: 'Fisioterapia' },
  { label: 'Preparação Física', value: 'Preparação Física' },
  { label: 'Técnica', value: 'Técnica' },
  { label: 'Psicologia', value: 'Psicologia' },
  { label: 'Nutrição', value: 'Nutrição' },
  { label: 'Médico', value: 'Médico' }
]);

// Carregar dados
async function loadTreinadores() {
  loading.value = true;
  try {
    const response = await api.get('/treinadores');
    treinadores.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar treinadores:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar treinadores',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
}

// Formatação
function formatDate(date: string) {
  return new Date(date).toLocaleDateString('pt-BR');
}

// Filtros
function clearFilters() {
  filters.value = {
    search: '',
    especialidade: null
  };
}

// CRUD
function editTreinador(treinador: any) {
  editingTreinador.value = treinador;
  form.value = {
    nome: treinador.nome,
    email: treinador.email,
    telefone: treinador.telefone || '',
    especialidade: treinador.especialidade || '',
    data_contratacao: treinador.data_contratacao ? new Date(treinador.data_contratacao) : null,
    observacoes: treinador.observacoes || ''
  };
  showCreateDialog.value = true;
}

function confirmDeleteTreinador(treinador: any) {
  confirm.require({
    message: `Tem certeza que deseja excluir o treinador "${treinador.nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => deleteTreinador(treinador.id)
  });
}

async function deleteTreinador(id: number) {
  try {
    await api.delete(`/treinadores/${id}`);
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Treinador excluído com sucesso',
      life: 3000
    });
    loadTreinadores();
  } catch (error) {
    console.error('Erro ao excluir treinador:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir treinador',
      life: 3000
    });
  }
}

function closeDialog() {
  showCreateDialog.value = false;
  editingTreinador.value = null;
  submitted.value = false;
  form.value = {
    nome: '',
    email: '',
    telefone: '',
    especialidade: '',
    data_contratacao: null,
    observacoes: ''
  };
}

async function saveTreinador() {
  submitted.value = true;
  
  if (!form.value.nome || !form.value.email) {
    return;
  }

  try {
    const data = {
      ...form.value,
      data_contratacao: form.value.data_contratacao ? form.value.data_contratacao.toISOString().split('T')[0] : null
    };

    if (editingTreinador.value) {
      await api.put(`/treinadores/${editingTreinador.value.id}`, data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Treinador atualizado com sucesso',
        life: 3000
      });
    } else {
      await api.post('/treinadores', data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Treinador criado com sucesso',
        life: 3000
      });
    }
    
    closeDialog();
    loadTreinadores();
  } catch (error) {
    console.error('Erro ao salvar treinador:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao salvar treinador',
      life: 3000
    });
  }
}

onMounted(() => {
  loadTreinadores();
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

.atleta-info {
  display: flex;
  flex-direction: column;
}

.atleta-name {
  font-weight: 500;
}

.atleta-email {
  font-size: 0.875rem;
  color: #6b7280;
}

.especialidade-badge {
  background: #e0e7ff;
  color: #3730a3;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}
</style> 