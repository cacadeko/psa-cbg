<template>
  <div>
    <h2>Sono</h2>
    <Button label="Novo Registro de Sono" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="sono" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="atleta" header="Atleta" />
      <Column field="qualidade" header="Qualidade" />
      <Column field="horas" header="Horas Dormidas" />
      <!-- Adicione mais colunas conforme necessário -->
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <Dialog v-model:visible="showDialog" header="Novo Registro de Sono" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="cadastrarSono">
        <div class="p-field">
          <label for="atleta">Atleta</label>
          <input id="atleta" v-model="novoSono.atleta" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="qualidade">Qualidade</label>
          <input id="qualidade" v-model="novoSono.qualidade" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="horas">Horas Dormidas</label>
          <input id="horas" v-model="novoSono.horas" type="number" class="p-inputtext p-component" required />
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

const sono = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const novoSono = ref({ atleta: '', qualidade: '', horas: '' });
const erroCadastro = ref('');

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

async function cadastrarSono() {
  erroCadastro.value = '';
  try {
    await api.post('/sono', novoSono.value);
    showDialog.value = false;
    novoSono.value = { atleta: '', qualidade: '', horas: '' };
    await carregarSono();
  } catch (e: any) {
    erroCadastro.value = 'Erro ao cadastrar registro de sono';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 