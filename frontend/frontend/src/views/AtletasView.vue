<template>
  <div>
    <h2>Atletas</h2>
    <Button label="Novo Atleta" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="atletas" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="nome" header="Nome" />
      <Column field="idade" header="Idade" />
      <!-- Adicione mais colunas conforme necessário -->
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <Dialog v-model:visible="showDialog" header="Novo Atleta" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="cadastrarAtleta">
        <div class="p-field">
          <label for="nome">Nome</label>
          <input id="nome" v-model="novoAtleta.nome" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="idade">Idade</label>
          <input id="idade" v-model="novoAtleta.idade" type="number" class="p-inputtext p-component" required />
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

const atletas = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const novoAtleta = ref({ nome: '', idade: '' });
const erroCadastro = ref('');

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

async function cadastrarAtleta() {
  erroCadastro.value = '';
  try {
    await api.post('/atletas', novoAtleta.value);
    showDialog.value = false;
    novoAtleta.value = { nome: '', idade: '' };
    await carregarAtletas();
  } catch (e: any) {
    erroCadastro.value = 'Erro ao cadastrar atleta';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 