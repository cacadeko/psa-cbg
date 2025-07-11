<template>
  <div>
    <h2>Sono</h2>
    <Button label="Novo Registro de Sono" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="sono" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="atleta" header="Atleta" />
      <Column field="qualidade" header="Qualidade" />
      <Column field="horas" header="Horas Dormidas" />
      <Column header="Ações">
        <template #body="slotProps">
          <Button icon="pi pi-pencil" class="p-button-text p-button-sm" @click="editarSono(slotProps.data)" />
          <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm" @click="confirmarExclusao(slotProps.data)" />
        </template>
      </Column>
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <!-- Modal de Cadastro/Edição -->
    <Dialog v-model:visible="showDialog" :header="editando ? 'Editar Registro de Sono' : 'Novo Registro de Sono'" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="salvarSono">
        <div class="p-field">
          <label for="atleta">Atleta</label>
          <input id="atleta" v-model="sonoForm.atleta" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="qualidade">Qualidade</label>
          <select id="qualidade" v-model="sonoForm.qualidade" class="p-inputtext p-component" required>
            <option value="">Selecione...</option>
            <option value="Excelente">Excelente</option>
            <option value="Boa">Boa</option>
            <option value="Regular">Regular</option>
            <option value="Ruim">Ruim</option>
          </select>
        </div>
        <div class="p-field">
          <label for="horas">Horas Dormidas</label>
          <input id="horas" v-model="sonoForm.horas" type="number" min="0" max="24" step="0.5" class="p-inputtext p-component" required />
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

const sono = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const editando = ref(false);
const sonoForm = ref({ id: null, atleta: '', qualidade: '', horas: '' });
const erroCadastro = ref('');
const confirm = useConfirm();

async function carregarSono() {
  loading.value = true;
  try {
    const { data } = await api.get('/sono');
    sono.value = data;
  } catch (e: any) {
    erro.value = 'Erro ao carregar dados de sono';
  } finally {
    loading.value = false;
  }
}

onMounted(carregarSono);

function editarSono(registro: any) {
  editando.value = true;
  sonoForm.value = { ...registro };
  showDialog.value = true;
}

function confirmarExclusao(registro: any) {
  confirm.require({
    message: `Deseja realmente excluir o registro de sono do atleta "${registro.atleta}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirSono(registro.id),
  });
}

async function excluirSono(id: number) {
  try {
    await api.delete(`/sono/${id}`);
    await carregarSono();
  } catch (e: any) {
    erro.value = 'Erro ao excluir registro de sono';
  }
}

async function salvarSono() {
  erroCadastro.value = '';
  try {
    if (editando.value) {
      await api.put(`/sono/${sonoForm.value.id}`, sonoForm.value);
    } else {
      await api.post('/sono', sonoForm.value);
    }
    showDialog.value = false;
    sonoForm.value = { id: null, atleta: '', qualidade: '', horas: '' };
    editando.value = false;
    await carregarSono();
  } catch (e: any) {
    erroCadastro.value = editando.value ? 'Erro ao atualizar registro de sono' : 'Erro ao cadastrar registro de sono';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 