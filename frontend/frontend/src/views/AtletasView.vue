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
      <Button label="Novo Atleta" icon="pi pi-plus" class="custom-primary-btn" @click="abrirNovoAtleta" style="background-color: #2563eb; border-color: #2563eb; color: #fff;" />
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
      <Column field="nome" header="Nome" sortable />
      <Column field="data_nascimento" header="Nascimento" sortable />
      <Column field="posicao" header="Posição" sortable />
      <Column field="email" header="Email" sortable />
      <Column field="telefone" header="Telefone" sortable />
      <Column field="categoria" header="Grupo" sortable />
      <Column header="Treinador" sortable>
        <template #body="slotProps">
          {{ getTreinadorNome(slotProps.data.treinador_id) }}
        </template>
      </Column>
      <Column field="created_at" header="Criado em" sortable />
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
    <Dialog v-model:visible="showDialog" :header="editando ? 'Editar Atleta' : 'Novo Atleta'" :modal="true" :closable="true" :style="{ width: '800px' }">
      <form @submit.prevent="salvarAtleta" class="form-grid form-labels-top">
        <div class="form-col">
          <div class="p-field">
            <label for="nome">Nome</label>
            <input id="nome" v-model="atletaForm.nome" type="text" class="p-inputtext p-component" required />
          </div>
          <div class="p-field">
            <label for="data_nascimento">Data de Nascimento</label>
            <input id="data_nascimento" v-model="atletaForm.data_nascimento" type="date" class="p-inputtext p-component" required />
          </div>
          <div class="p-field">
            <label for="posicao">Posição</label>
            <select id="posicao" v-model="atletaForm.posicao" class="p-inputtext p-component" required>
              <option value="">Selecione a posição</option>
              <option v-for="p in posicoes" :key="p" :value="p">{{ p }}</option>
            </select>
          </div>
          <div class="p-field">
            <label for="email">Email</label>
            <input id="email" v-model="atletaForm.email" type="email" class="p-inputtext p-component" required />
          </div>
          <div class="p-field">
            <label for="telefone">Telefone</label>
            <input id="telefone" v-model="atletaForm.telefone" type="text" class="p-inputtext p-component" maxlength="15"
              @input="onTelefoneInput" placeholder="(99) 99999-9999" />
          </div>
          <div class="p-field">
            <label for="senha">Senha</label>
            <input id="senha" v-model="atletaForm.senha" type="password" class="p-inputtext p-component" required />
          </div>
        </div>
        <div class="form-col">
          <div class="p-field">
            <label for="categoria">Grupo</label>
            <select id="categoria" v-model="atletaForm.categoria" class="p-inputtext p-component" required>
              <option value="">Selecione o grupo</option>
              <option v-for="g in grupos" :key="g" :value="g">{{ g }}</option>
            </select>
          </div>
          <div class="p-field">
            <label for="treinador_id">Treinador</label>
            <select id="treinador_id" v-model="atletaForm.treinador_id" class="p-inputtext p-component" required>
              <option value="">Selecione o treinador</option>
              <option v-for="t in treinadores" :key="t.id" :value="t.id">{{ t.nome }}</option>
            </select>
          </div>
          <div style="margin-top: 2.5rem;"><Button :label="editando ? 'Atualizar' : 'Salvar'" type="submit" class="custom-primary-btn" style="width: 100%; background-color: #2563eb; border-color: #2563eb; color: #fff;" /></div>
        </div>
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
const atletaForm = ref<{ id: number | null, nome: string, data_nascimento: string, posicao: string, email: string, telefone: string, categoria: string, treinador_id: string | number, senha: string }>(
  {
    id: null,
    nome: '',
    data_nascimento: '',
    posicao: '',
    email: '',
    telefone: '',
    categoria: '',
    treinador_id: '',
    senha: ''
  }
);
const erroCadastro = ref('');
const confirm = useConfirm();

const grupos = ['G1', 'G2', 'G3', 'G4', 'G5'];
const posicoes = ['Atleta', 'Ginasta'];
const acessos = [
  { value: '1', label: 'Admin' },
  { value: '2', label: 'Atleta' },
  { value: '3', label: 'Treinador' }
];

const treinadores = ref<any[]>([]);

async function carregarTreinadores() {
  try {
    const { data } = await api.get('/treinadores');
    treinadores.value = data;
    console.log('Treinadores carregados:', data);
  } catch (e: any) {
    console.error('Erro ao carregar treinadores:', e);
    erro.value = 'Erro ao carregar treinadores: ' + (e.response?.data?.message || e.message);
  }
}

function maskTelefone(value: string) {
  value = value.replace(/\D/g, '');
  value = value.replace(/(\d{2})(\d)/, '($1) $2');
  value = value.replace(/(\d{5})(\d{1,4})$/, '$1-$2');
  return value;
}

const filters = ref<any>({
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

onMounted(() => {
  carregarAtletas();
  carregarTreinadores();
});

function editarAtleta(atleta: any) {
  editando.value = true;
  atletaForm.value = { ...atleta };
  showDialog.value = true;
}

function abrirNovoAtleta() {
  editando.value = false;
  atletaForm.value = {
    id: null,
    nome: '',
    data_nascimento: '',
    posicao: '',
    email: '',
    telefone: '',
    categoria: '',
    treinador_id: '',
    senha: ''
  };
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
  console.log('Dados enviados para salvarAtleta:', atletaForm.value, 'Editando:', editando.value);
  try {
    // Garante que treinador_id seja inteiro ou undefined
    let treinadorId = atletaForm.value.treinador_id;
    if (treinadorId === '' || treinadorId === undefined || treinadorId === null) {
      erroCadastro.value = 'Selecione um treinador.';
      return;
    }
    treinadorId = parseInt(treinadorId);
    if (isNaN(treinadorId)) {
      erroCadastro.value = 'Selecione um treinador válido.';
      return;
    }
    if (editando.value) {
      await api.put('/atletas', { ...atletaForm.value, treinador_id: treinadorId });
    } else {
      // Não envie o campo id no POST e envie campos opcionais como string vazia ou zero
      const { id, ...novoAtleta } = atletaForm.value;
      await api.post('/atletas', {
        ...novoAtleta,
        treinador_id: treinadorId,
        senha: atletaForm.value.senha, // Adicionado campo senha
        acesso: "",
        usuario_id: 0
      });
    }
    showDialog.value = false;
    atletaForm.value = {
      id: null,
      nome: '',
      data_nascimento: '',
      posicao: '',
      email: '',
      telefone: '',
      categoria: '',
      treinador_id: '',
      senha: ''
    };
    editando.value = false;
    await carregarAtletas();
  } catch (e: any) {
    console.error('Erro ao salvar atleta:', e);
    erroCadastro.value = e?.response?.data?.error || (editando.value ? 'Erro ao atualizar atleta' : 'Erro ao cadastrar atleta');
  }
}

function onTelefoneInput(event: Event) {
  const target = event.target as HTMLInputElement | null;
  if (target) {
    atletaForm.value.telefone = maskTelefone(target.value);
  }
}

function getTreinadorNome(treinadorId: number | null): string {
  if (!treinadorId) return '-';
  const treinador = treinadores.value.find(t => t.id === treinadorId);
  return treinador ? treinador.nome : '-';
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

.form-grid {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}
.form-col {
  flex: 1 1 250px;
  min-width: 220px;
}
@media (max-width: 700px) {
  .form-grid {
    flex-direction: column;
    gap: 0.5rem;
  }
}
.form-labels-top .p-field {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.form-labels-top label {
  margin-bottom: 0.3rem;
  font-weight: 500;
}

/* Paleta de cores baseada no azul do menu e verde da logo */
/* Botão primário azul */
.custom-primary-btn,
.custom-primary-btn:focus,
.custom-primary-btn:active {
  background-color: #2563eb !important;
  border-color: #2563eb !important;
  color: #fff !important;
  box-shadow: none !important;
}

.custom-primary-btn:hover {
  background-color: #1d4ed8 !important;
  border-color: #1d4ed8 !important;
  color: #fff !important;
}

/* Botão de sucesso verde */
.custom-success-btn {
  background-color: #10b981 !important;
  border-color: #10b981 !important;
  color: white !important;
}

.custom-success-btn:hover {
  background-color: #059669 !important;
  border-color: #059669 !important;
}

/* Botão de perigo */
.custom-danger-btn {
  background-color: #ef4444 !important;
  border-color: #ef4444 !important;
  color: white !important;
}

.custom-danger-btn:hover {
  background-color: #dc2626 !important;
  border-color: #dc2626 !important;
}

/* Estilo para inputs com borda azul */
.p-inputtext:focus {
  border-color: #2563eb !important;
  box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25) !important;
}

/* Estilo para selects com borda azul */
select:focus {
  border-color: #2563eb !important;
  box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25) !important;
}

/* Cabeçalho com gradiente azul */
h2 {
  color: #2563eb;
  font-weight: 600;
}

/* Badges e elementos de destaque */
.especialidade-badge {
  background-color: #10b981;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
}

/* Botões de ação na tabela */
.p-button-text.p-button-sm {
  color: #2563eb !important;
}

.p-button-text.p-button-sm:hover {
  background-color: rgba(37, 99, 235, 0.1) !important;
}

.p-button-text.p-button-danger.p-button-sm {
  color: #ef4444 !important;
}

.p-button-text.p-button-danger.p-button-sm:hover {
  background-color: rgba(239, 68, 68, 0.1) !important;
}

/* Força a cor azul no ícone do botão de fechar do Dialog */
.p-dialog .p-dialog-header-close,
.p-dialog .p-dialog-header-close:focus,
.p-dialog .p-dialog-header-close:active {
  color: #2563eb !important;
  background: #fff !important;
  border: 1.5px solid #2563eb !important;
  box-shadow: none !important;
}
.p-dialog .p-dialog-header-close .p-dialog-header-close-icon,
.p-dialog .p-dialog-header-close .pi,
.p-dialog .p-dialog-header-close svg {
  color: #2563eb !important;
  fill: #2563eb !important;
  font-size: 1.2rem !important;
}
.p-dialog .p-dialog-header-close:hover,
.p-dialog .p-dialog-header-close:focus-visible {
  color: #1d4ed8 !important;
  border-color: #1d4ed8 !important;
  background: #f3f4f6 !important;
}
.p-dialog .p-dialog-header-close:hover .p-dialog-header-close-icon,
.p-dialog .p-dialog-header-close:hover .pi,
.p-dialog .p-dialog-header-close:hover svg {
  color: #1d4ed8 !important;
  fill: #1d4ed8 !important;
}
</style> 