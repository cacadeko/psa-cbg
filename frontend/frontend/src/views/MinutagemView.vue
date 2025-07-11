<template>
  <div>
    <div class="header-actions">
      <h2>⏱️ Minutagem</h2>
      <Button label="Nova Minutagem" icon="pi pi-plus" @click="showCreateDialog = true" />
    </div>

    <!-- Filtros -->
    <div class="filters-container">
      <div class="filters">
        <div>
          <label for="search">Buscar</label>
          <InputText v-model="filters.search" placeholder="Atleta ou jogo..." />
        </div>
        <div>
          <label for="jogo">Jogo</label>
          <Dropdown v-model="filters.jogo_id" :options="jogos" optionLabel="label" optionValue="value" placeholder="Todos os jogos" showClear />
        </div>
        <div>
          <label for="atleta">Atleta</label>
          <Dropdown v-model="filters.atleta_id" :options="atletas" optionLabel="label" optionValue="value" placeholder="Todos os atletas" showClear />
        </div>
        <Button label="Limpar" icon="pi pi-refresh" @click="clearFilters" />
      </div>
    </div>

    <!-- Tabela -->
    <DataTable 
      :value="minutagens" 
      :loading="loading"
      :paginator="true" 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['atleta_nome', 'jogo_adversario']"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <Column field="jogo_data" header="Data do Jogo" sortable>
        <template #body="{ data }">
          {{ formatDate(data.jogo_data) }}
        </template>
      </Column>
      <Column field="atleta_nome" header="Atleta" sortable>
        <template #body="{ data }">
          <div class="atleta-info">
            <div class="atleta-name">{{ data.atleta_nome }}</div>
            <div class="atleta-posicao">{{ data.titular ? 'Titular' : 'Reserva' }}</div>
          </div>
        </template>
      </Column>
      <Column field="jogo_adversario" header="Jogo" sortable>
        <template #body="{ data }">
          <div class="jogo-info">
            <div class="jogo-adversario">{{ data.jogo_adversario }}</div>
            <div class="jogo-local">{{ data.jogo_local }}</div>
          </div>
        </template>
      </Column>
      <Column field="minutos_total" header="Minutos" sortable>
        <template #body="{ data }">
          <div class="minutos-info">
            <div class="minutos-total">{{ data.minutos_total }}'</div>
            <div class="minutos-detalhes">
              {{ data.minutos_primeiro_tempo }}' + {{ data.minutos_segundo_tempo }}'
            </div>
          </div>
        </template>
      </Column>
      <Column field="entrada_saida" header="Entrada/Saída" sortable>
        <template #body="{ data }">
          <div class="entrada-saida">
            <span v-if="data.minuto_entrou">{{ data.minuto_entrou }}'</span>
            <span v-else>Início</span>
            <span class="separator">→</span>
            <span v-if="data.minuto_saiu">{{ data.minuto_saiu }}'</span>
            <span v-else>Fim</span>
          </div>
        </template>
      </Column>
      <Column header="Ações" :exportable="false" style="min-width:8rem">
        <template #body="{ data }">
          <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editMinutagem(data)" />
          <Button icon="pi pi-trash" class="p-button-rounded p-button-warning" @click="confirmDeleteMinutagem(data)" />
        </template>
      </Column>
    </DataTable>

    <!-- Dialog Criar/Editar -->
    <Dialog v-model:visible="showCreateDialog" :header="editingMinutagem ? 'Editar Minutagem' : 'Nova Minutagem'" modal class="p-fluid">
      <div class="form-grid">
        <div class="field">
          <label for="jogo_id">Jogo *</label>
          <Dropdown id="jogo_id" v-model="form.jogo_id" :options="jogos" optionLabel="label" optionValue="value" placeholder="Selecione o jogo" required :class="{ 'p-invalid': submitted && !form.jogo_id }" />
          <small class="p-error" v-if="submitted && !form.jogo_id">Jogo é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="atleta_id">Atleta *</label>
          <Dropdown id="atleta_id" v-model="form.atleta_id" :options="atletas" optionLabel="label" optionValue="value" placeholder="Selecione o atleta" required :class="{ 'p-invalid': submitted && !form.atleta_id }" />
          <small class="p-error" v-if="submitted && !form.atleta_id">Atleta é obrigatório.</small>
        </div>
        
        <div class="field">
          <label for="titular">Titular</label>
          <div class="checkbox-field">
            <input type="checkbox" id="titular" v-model="form.titular" />
            <label for="titular">Atleta é titular</label>
          </div>
        </div>
        
        <div class="field">
          <label for="minuto_entrou">Minuto que Entrou</label>
          <InputNumber id="minuto_entrou" v-model="form.minuto_entrou" :min="0" :max="120" placeholder="0 = início do jogo" />
        </div>
        
        <div class="field">
          <label for="minuto_saiu">Minuto que Saiu</label>
          <InputNumber id="minuto_saiu" v-model="form.minuto_saiu" :min="0" :max="120" placeholder="0 = fim do jogo" />
        </div>
        
        <div class="field">
          <label for="minutos_primeiro_tempo">Minutos 1º Tempo *</label>
          <InputNumber id="minutos_primeiro_tempo" v-model="form.minutos_primeiro_tempo" :min="0" :max="60" required :class="{ 'p-invalid': submitted && !form.minutos_primeiro_tempo }" />
          <small class="p-error" v-if="submitted && !form.minutos_primeiro_tempo">Minutos do primeiro tempo são obrigatórios.</small>
        </div>
        
        <div class="field">
          <label for="minutos_segundo_tempo">Minutos 2º Tempo *</label>
          <InputNumber id="minutos_segundo_tempo" v-model="form.minutos_segundo_tempo" :min="0" :max="60" required :class="{ 'p-invalid': submitted && !form.minutos_segundo_tempo }" />
          <small class="p-error" v-if="submitted && !form.minutos_segundo_tempo">Minutos do segundo tempo são obrigatórios.</small>
        </div>
        
        <div class="field full-width">
          <label for="observacoes">Observações</label>
          <Textarea id="observacoes" v-model="form.observacoes" rows="3" />
        </div>
      </div>
      
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="closeDialog" />
        <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveMinutagem" />
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
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import api from '../services/api';

const confirm = useConfirm();
const toast = useToast();

// Estado
const minutagens = ref([]);
const jogos = ref([]);
const atletas = ref([]);
const loading = ref(false);
const showCreateDialog = ref(false);
const submitted = ref(false);
const editingMinutagem = ref(null);

// Filtros
const filters = ref({
  search: '',
  jogo_id: null,
  atleta_id: null
});

// Formulário
const form = ref({
  jogo_id: null,
  atleta_id: null,
  titular: true,
  minuto_entrou: null,
  minuto_saiu: null,
  minutos_primeiro_tempo: 0,
  minutos_segundo_tempo: 0,
  observacoes: ''
});

// Carregar dados
async function loadMinutagens() {
  loading.value = true;
  try {
    const response = await api.get('/minutagem');
    minutagens.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar minutagens:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar minutagens',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
}

async function loadJogos() {
  try {
    const response = await api.get('/jogos');
    jogos.value = response.data.map((jogo: any) => ({
      label: `${formatDate(jogo.data_jogo)} - ${jogo.adversario}`,
      value: jogo.id
    }));
  } catch (error) {
    console.error('Erro ao carregar jogos:', error);
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

// Filtros
function clearFilters() {
  filters.value = {
    search: '',
    jogo_id: null,
    atleta_id: null
  };
}

// CRUD
function editMinutagem(minutagem: any) {
  editingMinutagem.value = minutagem;
  form.value = {
    jogo_id: minutagem.jogo_id,
    atleta_id: minutagem.atleta_id,
    titular: minutagem.titular,
    minuto_entrou: minutagem.minuto_entrou,
    minuto_saiu: minutagem.minuto_saiu,
    minutos_primeiro_tempo: minutagem.minutos_primeiro_tempo,
    minutos_segundo_tempo: minutagem.minutos_segundo_tempo,
    observacoes: minutagem.observacoes || ''
  };
  showCreateDialog.value = true;
}

function confirmDeleteMinutagem(minutagem: any) {
  confirm.require({
    message: `Tem certeza que deseja excluir a minutagem do atleta "${minutagem.atleta_nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => deleteMinutagem(minutagem.id)
  });
}

async function deleteMinutagem(id: number) {
  try {
    await api.delete(`/minutagem/${id}`);
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Minutagem excluída com sucesso',
      life: 3000
    });
    loadMinutagens();
  } catch (error) {
    console.error('Erro ao excluir minutagem:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir minutagem',
      life: 3000
    });
  }
}

function closeDialog() {
  showCreateDialog.value = false;
  editingMinutagem.value = null;
  submitted.value = false;
  form.value = {
    jogo_id: null,
    atleta_id: null,
    titular: true,
    minuto_entrou: null,
    minuto_saiu: null,
    minutos_primeiro_tempo: 0,
    minutos_segundo_tempo: 0,
    observacoes: ''
  };
}

async function saveMinutagem() {
  submitted.value = true;
  
  if (!form.value.jogo_id || !form.value.atleta_id || form.value.minutos_primeiro_tempo === null || form.value.minutos_segundo_tempo === null) {
    return;
  }

  try {
    const data = {
      ...form.value,
      minutos_primeiro_tempo: Number(form.value.minutos_primeiro_tempo),
      minutos_segundo_tempo: Number(form.value.minutos_segundo_tempo),
      minuto_entrou: form.value.minuto_entrou ? Number(form.value.minuto_entrou) : null,
      minuto_saiu: form.value.minuto_saiu ? Number(form.value.minuto_saiu) : null
    };

    if (editingMinutagem.value) {
      await api.put(`/minutagem/${editingMinutagem.value.id}`, data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Minutagem atualizada com sucesso',
        life: 3000
      });
    } else {
      await api.post('/minutagem', data);
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Minutagem criada com sucesso',
        life: 3000
      });
    }
    
    closeDialog();
    loadMinutagens();
  } catch (error) {
    console.error('Erro ao salvar minutagem:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao salvar minutagem',
      life: 3000
    });
  }
}

onMounted(() => {
  loadMinutagens();
  loadJogos();
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

.checkbox-field {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.checkbox-field input[type="checkbox"] {
  width: auto;
}

.atleta-info {
  display: flex;
  flex-direction: column;
}

.atleta-name {
  font-weight: 500;
}

.atleta-posicao {
  font-size: 0.875rem;
  color: #6b7280;
}

.jogo-info {
  display: flex;
  flex-direction: column;
}

.jogo-adversario {
  font-weight: 500;
}

.jogo-local {
  font-size: 0.875rem;
  color: #6b7280;
}

.minutos-info {
  display: flex;
  flex-direction: column;
}

.minutos-total {
  font-weight: 500;
  font-size: 1.1rem;
}

.minutos-detalhes {
  font-size: 0.875rem;
  color: #6b7280;
}

.entrada-saida {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.875rem;
}

.separator {
  color: #6b7280;
  font-weight: bold;
}
</style> 