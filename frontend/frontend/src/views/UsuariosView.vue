<template>
  <div>
    <h2>Usuários</h2>
    <Button label="Novo Usuário" icon="pi pi-plus" class="p-mb-3" @click="showDialog = true" />
    <DataTable :value="usuarios" v-if="!loading">
      <Column field="id" header="ID" />
      <Column field="nome" header="Nome" />
      <Column field="email" header="Email" />
      <Column header="Ações">
        <template #body="slotProps">
          <Button icon="pi pi-pencil" class="p-button-text p-button-sm" @click="editarUsuario(slotProps.data)" />
          <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm" @click="confirmarExclusao(slotProps.data)" />
        </template>
      </Column>
    </DataTable>
    <div v-else>Carregando...</div>
    <div v-if="erro" class="erro-msg">{{ erro }}</div>

    <!-- Modal de Cadastro/Edição -->
    <Dialog v-model:visible="showDialog" :header="editando ? 'Editar Usuário' : 'Novo Usuário'" :modal="true" :closable="true" :style="{ width: '400px' }">
      <form @submit.prevent="salvarUsuario">
        <div class="p-field">
          <label for="nome">Nome</label>
          <input id="nome" v-model="usuarioForm.nome" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="email">Email</label>
          <input id="email" v-model="usuarioForm.email" type="email" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="senha">Senha</label>
          <input id="senha" v-model="usuarioForm.senha" type="password" class="p-inputtext p-component" :required="!editando" />
          <small v-if="editando">Deixe em branco para manter a senha atual</small>
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

const usuarios = ref<any[]>([]);
const loading = ref(true);
const erro = ref('');
const showDialog = ref(false);
const editando = ref(false);
const usuarioForm = ref({ id: null, nome: '', email: '', senha: '' });
const erroCadastro = ref('');
const confirm = useConfirm();

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

function editarUsuario(usuario: any) {
  editando.value = true;
  usuarioForm.value = { ...usuario, senha: '' };
  showDialog.value = true;
}

function confirmarExclusao(usuario: any) {
  confirm.require({
    message: `Deseja realmente excluir o usuário "${usuario.nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirUsuario(usuario.id),
  });
}

async function excluirUsuario(id: number) {
  try {
    await api.delete(`/usuarios/${id}`);
    await carregarUsuarios();
  } catch (e: any) {
    erro.value = 'Erro ao excluir usuário';
  }
}

async function salvarUsuario() {
  erroCadastro.value = '';
  try {
    const dados = { ...usuarioForm.value };
    if (editando.value && !dados.senha) {
      delete dados.senha;
    }
    
    if (editando.value) {
      await api.put(`/usuarios/${dados.id}`, dados);
    } else {
      await api.post('/usuarios', dados);
    }
    showDialog.value = false;
    usuarioForm.value = { id: null, nome: '', email: '', senha: '' };
    editando.value = false;
    await carregarUsuarios();
  } catch (e: any) {
    erroCadastro.value = editando.value ? 'Erro ao atualizar usuário' : 'Erro ao cadastrar usuário';
  }
}
</script>

<style scoped>
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 