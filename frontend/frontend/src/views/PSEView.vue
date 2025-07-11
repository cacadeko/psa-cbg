<template>
  <div>
    <h2>PSE</h2>
    <Button label="Novo Registro de PSE" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="pse" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="atleta" header="Atleta" />
      <Column field="valor" header="Valor PSE" />
      <Column field="data" header="Data" />
      <!-- Adicione mais colunas conforme necessário -->
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <Dialog v-model:visible="showDialog" header="Novo Registro de PSE" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="cadastrarPSE">
        <div class="p-field">
          <label for="atleta">Atleta</label>
          <input id="atleta" v-model="novoPSE.atleta" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="valor">Valor PSE</label>
          <input id="valor" v-model="novoPSE.valor" type="number" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="data">Data</label>
          <input id="data" v-model="novoPSE.data" type="date" class="p-inputtext p-component" required />
        </div>
        <Button label="Salvar" type="submit" class="p-mt-2 p-button-success" />
      </form>
      <div v-if="erroCadastro" class="erro-msg">{{ erroCadastro }}</div>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import api from '../services/api';

const pse = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const novoPSE = ref({ atleta: '', valor: '', data: '' });
const erroCadastro = ref('');

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

async function cadastrarPSE() {
  erroCadastro.value = '';
  try {
    await api.post('/pse', novoPSE.value);
    showDialog.value = false;
    novoPSE.value = { atleta: '', valor: '', data: '' };
    await carregarPSE();
  } catch (e: any) {
    erroCadastro.value = 'Erro ao cadastrar registro de PSE';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 