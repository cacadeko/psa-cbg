<template>
  <div class="card">
    <Toast />
    <ConfirmDialog />

    <!-- Header com título e botão de novo usuário -->
    <div class="flex justify-content-between align-items-center mb-4">
      <h2 class="m-0">Usuários</h2>
      <Button 
        label="Novo Usuário" 
        icon="pi pi-plus" 
        class="p-button-primary"
        @click="openNew"
      />
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
      <div class="flex flex-column md:flex-row gap-3">
        <div class="flex-1">
          <span class="p-input-icon-left w-full">
            <i class="pi pi-search" />
            <InputText 
              v-model="filters.global.value" 
              placeholder="Buscar usuários..." 
              class="w-full"
            />
          </span>
        </div>
        <div class="flex gap-2">
          <Button 
            icon="pi pi-refresh" 
            class="p-button-outlined"
            @click="loadUsuarios"
            :loading="loading"
          />
        </div>
      </div>
    </div>

    <!-- DataTable -->
    <DataTable 
      :value="filteredUsuarios" 
      :loading="loading"
      v-model:filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['nome', 'email', 'nivel']"
      dataKey="id"
      :paginator="true" 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 25, 50]"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
      currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} usuários"
      responsiveLayout="scroll"
      class="p-datatable-sm"
    >
      <Column field="id" header="ID" sortable style="width: 80px">
        <template #body="{ data }">
          <span class="font-bold text-primary">{{ data.id }}</span>
        </template>
      </Column>
      
      <Column field="nome" header="Nome" sortable>
        <template #body="{ data }">
          <div class="flex align-items-center gap-2">
            <i class="pi pi-user text-primary"></i>
            <span class="font-medium">{{ data.nome }}</span>
          </div>
        </template>
      </Column>
      
      <Column field="email" header="Email" sortable>
        <template #body="{ data }">
          <div class="flex align-items-center gap-2">
            <i class="pi pi-envelope text-blue-500"></i>
            <span>{{ data.email }}</span>
          </div>
        </template>
      </Column>
      
      <Column field="nivel" header="Nível" sortable style="width: 120px">
        <template #body="{ data }">
          <Tag 
            :value="data.nivel" 
            :severity="getNivelSeverity(data.nivel)"
            class="text-xs"
          />
        </template>
      </Column>
      
      <Column field="ativo" header="Status" sortable style="width: 100px">
        <template #body="{ data }">
          <Tag 
            :value="data.ativo ? 'Ativo' : 'Inativo'" 
            :severity="data.ativo ? 'success' : 'danger'"
            class="text-xs"
          />
        </template>
      </Column>
      
      <Column header="Ações" style="width: 150px">
        <template #body="{ data }">
          <div class="flex gap-1">
            <Button 
              icon="pi pi-pencil" 
              class="p-button-sm p-button-primary"
              @click="editUsuario(data)"
              v-tooltip.top="'Editar'"
            />
            <Button 
              icon="pi pi-trash" 
              class="p-button-sm p-button-danger"
              @click="confirmDeleteUsuario(data)"
              v-tooltip.top="'Excluir'"
            />
          </div>
        </template>
      </Column>
    </DataTable>

    <!-- Dialog para criar/editar usuário -->
    <Dialog 
      v-model:visible="usuarioDialog" 
      :style="{ width: '450px' }" 
      header="Detalhes do Usuário" 
      :modal="true" 
      class="p-fluid"
    >
      <div class="field">
        <label for="nome">Nome</label>
        <InputText 
          id="nome" 
          v-model.trim="usuario.nome" 
          required="true" 
          autofocus 
          :class="{ 'p-invalid': submitted && !usuario.nome }" 
        />
        <small class="p-error" v-if="submitted && !usuario.nome">Nome é obrigatório.</small>
      </div>
      
      <div class="field">
        <label for="email">Email</label>
        <InputText 
          id="email" 
          v-model.trim="usuario.email" 
          required="true" 
          type="email"
          :class="{ 'p-invalid': submitted && !usuario.email }" 
        />
        <small class="p-error" v-if="submitted && !usuario.email">Email é obrigatório.</small>
      </div>
      
      <div class="field">
        <label for="senha">Senha</label>
        <Password 
          id="senha" 
          v-model="usuario.senha" 
          :required="!usuario.id"
          :feedback="false"
          toggleMask
        />
        <small v-if="usuario.id" class="text-gray-500">Deixe em branco para manter a senha atual</small>
        <small class="p-error" v-if="submitted && !usuario.id && !usuario.senha">Senha é obrigatória.</small>
      </div>
      
      <div class="field">
        <label for="nivel">Nível</label>
        <Dropdown 
          id="nivel" 
          v-model="usuario.nivel" 
          :options="niveis" 
          optionLabel="label" 
          optionValue="value"
          placeholder="Selecione o nível"
          :class="{ 'p-invalid': submitted && !usuario.nivel }"
        />
        <small class="p-error" v-if="submitted && !usuario.nivel">Nível é obrigatório.</small>
      </div>
      
      <div class="field-checkbox">
        <Checkbox 
          id="ativo" 
          v-model="usuario.ativo" 
          :binary="true" 
        />
        <label for="ativo">Usuário ativo</label>
      </div>

      <template #footer>
        <Button 
          label="Cancelar" 
          icon="pi pi-times" 
          class="p-button-text" 
          @click="hideDialog" 
        />
        <Button 
          label="Salvar" 
          icon="pi pi-check" 
          class="p-button-primary" 
          @click="saveUsuario" 
        />
      </template>
    </Dialog>

    <!-- Dialog de confirmação de exclusão -->
    <Dialog 
      v-model:visible="deleteUsuarioDialog" 
      :style="{ width: '450px' }" 
      header="Confirmar" 
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="usuario">Tem certeza que deseja excluir <b>{{ usuario.nome }}</b>?</span>
      </div>
      <template #footer>
        <Button 
          label="Não" 
          icon="pi pi-times" 
          class="p-button-text" 
          @click="deleteUsuarioDialog = false" 
        />
        <Button 
          label="Sim" 
          icon="pi pi-check" 
          class="p-button-danger" 
          @click="deleteUsuario" 
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import api from '../services/api';

const confirm = useConfirm();
const toast = useToast();

const usuarios = ref<any[]>([]);
const usuario = ref<any>({});
const usuarioDialog = ref(false);
const deleteUsuarioDialog = ref(false);
const submitted = ref(false);
const loading = ref(false);

const niveis = ref([
  { label: 'Administrador', value: 'admin' },
  { label: 'Treinador', value: 'treinador' },
  { label: 'Usuário', value: 'usuario' }
]);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nome: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
  email: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
  nivel: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] }
});

const filteredUsuarios = computed(() => {
  return usuarios.value;
});

const getNivelSeverity = (nivel: string) => {
  switch (nivel) {
    case 'admin': return 'danger';
    case 'treinador': return 'warning';
    case 'usuario': return 'info';
    default: return 'secondary';
  }
};

const loadUsuarios = async () => {
  loading.value = true;
  try {
    const response = await api.get('/usuarios');
    usuarios.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar usuários:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar usuários',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
};

const openNew = () => {
  usuario.value = {
    nome: '',
    email: '',
    senha: '',
    nivel: 'treinador',
    ativo: true
  };
  submitted.value = false;
  usuarioDialog.value = true;
};

const hideDialog = () => {
  usuarioDialog.value = false;
  submitted.value = false;
};

const editUsuario = (editUsuario: any) => {
  usuario.value = { ...editUsuario };
  usuario.value.senha = ''; // Não mostrar senha atual
  usuarioDialog.value = true;
};

const confirmDeleteUsuario = (editUsuario: any) => {
  usuario.value = editUsuario;
  deleteUsuarioDialog.value = true;
};

const deleteUsuario = async () => {
  try {
    await api.delete(`/usuarios/${usuario.value.id}`);
    deleteUsuarioDialog.value = false;
    usuario.value = {};
    await loadUsuarios();
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Usuário excluído com sucesso',
      life: 3000
    });
  } catch (error) {
    console.error('Erro ao excluir usuário:', error);
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir usuário',
      life: 3000
    });
  }
};

const saveUsuario = async () => {
  submitted.value = true;

  if (usuario.value.nome?.trim() && usuario.value.email?.trim() && 
      (usuario.value.id || usuario.value.senha?.trim())) {
    
    try {
      const dados = { ...usuario.value };
      
      if (usuario.value.id) {
        // Edição
        if (!dados.senha) {
          delete dados.senha; // Não enviar senha se estiver vazia
        }
        await api.put(`/usuarios/${usuario.value.id}`, dados);
        toast.add({
          severity: 'success',
          summary: 'Sucesso',
          detail: 'Usuário atualizado com sucesso',
          life: 3000
        });
      } else {
        // Criação
        await api.post('/usuarios', dados);
        toast.add({
          severity: 'success',
          summary: 'Sucesso',
          detail: 'Usuário criado com sucesso',
          life: 3000
        });
      }
      
      usuarioDialog.value = false;
      usuario.value = {};
      await loadUsuarios();
    } catch (error) {
      console.error('Erro ao salvar usuário:', error);
      toast.add({
        severity: 'error',
        summary: 'Erro',
        detail: 'Erro ao salvar usuário',
        life: 3000
      });
    }
  }
};

onMounted(() => {
  loadUsuarios();
});
</script>

<style scoped>
.card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 2px 1px -1px rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 1px 3px 0 rgba(0,0,0,.12);
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}

.p-datatable .p-datatable-header {
  background: transparent;
  border: none;
  padding: 1rem 0;
}

.p-datatable .p-datatable-thead > tr > th {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  font-weight: 600;
  color: #495057;
}

.p-datatable .p-datatable-tbody > tr > td {
  border: 1px solid #dee2e6;
  padding: 0.75rem;
}

.p-datatable .p-datatable-tbody > tr:nth-child(even) {
  background: #f8f9fa;
}

.p-datatable .p-datatable-tbody > tr:hover {
  background: #e9ecef;
}

.p-button-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.p-button-sm .p-button-icon {
  font-size: 0.875rem;
}

.p-tag {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
}

.field-checkbox {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.field-checkbox label {
  margin: 0;
  cursor: pointer;
}
</style> 