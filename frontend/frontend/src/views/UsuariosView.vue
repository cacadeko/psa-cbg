<template>
  <div>
    <h2>Usuários</h2>
    <Button label="Novo Usuário" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="usuarios" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="nome" header="Nome" />
      <Column field="email" header="Email" />
      <!-- Adicione mais colunas conforme necessário -->
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <Dialog v-model:visible="showDialog" header="Novo Usuário" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="cadastrarUsuario">
        <div class="p-field">
          <label for="nome">Nome</label>
          <input id="nome" v-model="novoUsuario.nome" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="email">Email</label>
          <input id="email" v-model="novoUsuario.email" type="email" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="senha">Senha</label>
          <input id="senha" v-model="novoUsuario.senha" type="password" class="p-inputtext p-component" required />
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

const usuarios = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const novoUsuario = ref({ nome: '', email: '', senha: '' });
const erroCadastro = ref('');

async function carregarUsuarios() {
  loading.value = true;
  try {
    const { data } = await api.get('/usuarios');
    usuarios.value = data;
  } catch (e: any) {
    erro.value = 'Erro ao carregar usuários';
  } finally {
    loading.value = false;
  }
}

onMounted(carregarUsuarios);

async function cadastrarUsuario() {
  erroCadastro.value = '';
  try {
    await api.post('/usuarios', novoUsuario.value);
    showDialog.value = false;
    novoUsuario.value = { nome: '', email: '', senha: '' };
    await carregarUsuarios();
  } catch (e: any) {
    erroCadastro.value = 'Erro ao cadastrar usuário';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 