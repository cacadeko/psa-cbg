<template>
  <div>
    <h2>Atletas</h2>
    
    <!-- Filtros e Busca -->
    <div class="filters-container">
      <div class="search-box">
        <span class="p-input-icon-left">
          <i class="pi pi-search" />
          <InputText v-model="filters.global" placeholder="Buscar atletas..." class="p-inputtext-sm" />
        </span>
      </div>
      <Button label="Novo Atleta" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    </div>
    
    <DataTable 
      :value="atletas" 
      v-if="!loading"
      :paginator="true"
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      :filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['nome', 'idade']"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
      currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} atletas"
    >
      <Column field="id" header="ID" sortable />
      <Column field="nome" header="Nome" sortable>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" class="p-column-filter" placeholder="Buscar por nome" />
        </template>
      </Column>
      <Column field="idade" header="Idade" sortable>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()" class="p-column-filter" placeholder="Buscar por idade" />
        </template>
      </Column>
      <Column header="Ações" :exportable="false" style="min-width:8rem">
        <template #body="slotProps">
          <Button icon="pi pi-pencil" class="p-button-text p-button-sm" @click="editarAtleta(slotProps.data)" />
          <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm" @click="confirmarExclusao(slotProps.data)" />
        </template>
      </Column>
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <!-- Modal de Cadastro/Edição -->
    <Dialog v-model:visible="showDialog" :header="editando ? 'Editar Atleta' : 'Novo Atleta'" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="salvarAtleta">
        <div class="p-field">
          <label for="nome">Nome</label>
          <input id="nome" v-model="atletaForm.nome" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="idade">Idade</label>
          <input id="idade" v-model="atletaForm.idade" type="number" class="p-inputtext p-component" required />
        </div>
        <Button :label="editando ? 'Atualizar' : 'Salvar'" type="submit" class="p-mt-2 p-button-success" />
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
import { useConfirm } from 'primevue/useconfirm';
import { FilterMatchMode } from 'primevue/api';
import api from '../services/api';

const atletas = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const editando = ref(false);
const atletaForm = ref({ id: null, nome: '', idade: '' });
const erroCadastro = ref('');
const confirm = useConfirm();

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nome: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  idade: { value: null, matchMode: FilterMatchMode.EQUALS }
});

async function carregarAtletas() {
  loading.value = true;
  try {
    const { data } = await api.get('/atletas');
    atletas.value = data;
  } catch (e: any) {
    erro.value = 'Erro ao carregar atletas';
  } finally {
    loading.value = false;
  }
}

onMounted(carregarAtletas);

function editarAtleta(atleta: any) {
  editando.value = true;
  atletaForm.value = { ...atleta };
  showDialog.value = true;
}

function confirmarExclusao(atleta: any) {
  confirm.require({
    message: `Deseja realmente excluir o atleta "${atleta.nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirAtleta(atleta.id),
  });
}

async function excluirAtleta(id: number) {
  try {
    await api.delete(`/atletas/${id}`);
    await carregarAtletas();
  } catch (e: any) {
    erro.value = 'Erro ao excluir atleta';
  }
}

async function salvarAtleta() {
  erroCadastro.value = '';
  try {
    if (editando.value) {
      await api.put(`/atletas/${atletaForm.value.id}`, atletaForm.value);
    } else {
      await api.post('/atletas', atletaForm.value);
    }
    showDialog.value = false;
    atletaForm.value = { id: null, nome: '', idade: '' };
    editando.value = false;
    await carregarAtletas();
  } catch (e: any) {
    erroCadastro.value = editando.value ? 'Erro ao atualizar atleta' : 'Erro ao cadastrar atleta';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}

.filters-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.search-box {
  flex: 1;
  max-width: 300px;
}
</style> 