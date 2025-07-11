<template>
  <div>
    <h2>PSE</h2>
    <Button label="Novo Registro de PSE" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="pse" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="atleta" header="Atleta" />
      <Column field="valor" header="Valor PSE" />
      <Column field="data" header="Data" />
      <Column header="Ações">
        <template #body="slotProps">
          <Button icon="pi pi-pencil" class="p-button-text p-button-sm" @click="editarPSE(slotProps.data)" />
          <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm" @click="confirmarExclusao(slotProps.data)" />
        </template>
      </Column>
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <!-- Modal de Cadastro/Edição -->
    <Dialog v-model:visible="showDialog" :header="editando ? 'Editar Registro de PSE' : 'Novo Registro de PSE'" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="salvarPSE">
        <div class="p-field">
          <label for="atleta">Atleta</label>
          <input id="atleta" v-model="pseForm.atleta" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="valor">Valor PSE (1-10)</label>
          <input id="valor" v-model="pseForm.valor" type="number" min="1" max="10" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="data">Data</label>
          <input id="data" v-model="pseForm.data" type="date" class="p-inputtext p-component" required />
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
import { useConfirm } from 'primevue/useconfirm';
import api from '../services/api';

const pse = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const editando = ref(false);
const pseForm = ref({ id: null, atleta: '', valor: '', data: '' });
const erroCadastro = ref('');
const confirm = useConfirm();

async function carregarPSE() {
  loading.value = true;
  try {
    const { data } = await api.get('/pse');
    pse.value = data;
  } catch (e: any) {
    erro.value = 'Erro ao carregar dados de PSE';
  } finally {
    loading.value = false;
  }
}

onMounted(carregarPSE);

function editarPSE(registro: any) {
  editando.value = true;
  pseForm.value = { ...registro };
  showDialog.value = true;
}

function confirmarExclusao(registro: any) {
  confirm.require({
    message: `Deseja realmente excluir o registro de PSE do atleta "${registro.atleta}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirPSE(registro.id),
  });
}

async function excluirPSE(id: number) {
  try {
    await api.delete(`/pse/${id}`);
    await carregarPSE();
  } catch (e: any) {
    erro.value = 'Erro ao excluir registro de PSE';
  }
}

async function salvarPSE() {
  erroCadastro.value = '';
  try {
    if (editando.value) {
      await api.put(`/pse/${pseForm.value.id}`, pseForm.value);
    } else {
      await api.post('/pse', pseForm.value);
    }
    showDialog.value = false;
    pseForm.value = { id: null, atleta: '', valor: '', data: '' };
    editando.value = false;
    await carregarPSE();
  } catch (e: any) {
    erroCadastro.value = editando.value ? 'Erro ao atualizar registro de PSE' : 'Erro ao cadastrar registro de PSE';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 