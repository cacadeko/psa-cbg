<template>
  <div class="treinadores-container">
    <div class="header">
      <h1>Gestão de Treinadores</h1>
      <Button 
        label="Novo Treinador" 
        icon="pi pi-plus" 
        @click="abrirModal()" 
        class="p-button-success"
      />
    </div>

    <!-- Tabela de Treinadores -->
    <DataTable 
      :value="treinadores" 
      :loading="loading"
      paginator 
      :rows="10"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      filterDisplay="menu"
      :globalFilterFields="['nome', 'email', 'especialidade']"
      emptyMessage="Nenhum treinador encontrado"
      class="p-datatable-sm"
    >
      <template #header>
        <div class="flex justify-content-between">
          <h5 class="m-0">Treinadores</h5>
          <span class="p-input-icon-left">
            <i class="pi pi-search" />
            <InputText v-model="filters.global.value" placeholder="Buscar..." />
          </span>
        </div>
      </template>

      <Column field="id" header="ID" sortable style="width: 80px"></Column>
      <Column field="nome" header="Nome" sortable filter></Column>
      <Column field="email" header="Email" sortable filter></Column>
      <Column field="telefone" header="Telefone" sortable></Column>
      <Column field="especialidade" header="Especialidade" sortable filter></Column>
      <Column field="data_contratacao" header="Data Contratação" sortable>
        <template #body="slotProps">
          {{ formatarData(slotProps.data.data_contratacao) }}
        </template>
      </Column>
      <Column field="nivel" header="Nível" sortable>
        <template #body="slotProps">
          <Tag :value="slotProps.data.nivel" :severity="getSeverityNivel(slotProps.data.nivel)" />
        </template>
      </Column>
      <Column field="ativo" header="Status" sortable>
        <template #body="slotProps">
          <Tag :value="slotProps.data.ativo ? 'Ativo' : 'Inativo'" :severity="slotProps.data.ativo ? 'success' : 'danger'" />
        </template>
      </Column>
      <Column header="Ações" style="width: 200px">
        <template #body="slotProps">
          <div class="flex gap-2">
            <Button 
              icon="pi pi-pencil" 
              class="p-button-sm p-button-outlined p-button-info"
              @click="editarTreinador(slotProps.data)"
              v-tooltip.top="'Editar'"
            />
            <Button 
              icon="pi pi-trash" 
              class="p-button-sm p-button-outlined p-button-danger"
              @click="confirmarExclusao(slotProps.data)"
              v-tooltip.top="'Excluir'"
            />
          </div>
        </template>
      </Column>
    </DataTable>

    <!-- Modal de Criação/Edição -->
    <Dialog 
      v-model:visible="modalVisible" 
      :header="editando ? 'Editar Treinador' : 'Novo Treinador'"
      :style="{ width: '600px' }"
      :modal="true"
      :closable="true"
      @hide="resetarFormulario"
    >
      <form @submit.prevent="salvarTreinador" class="form-treinador">
        <div class="grid">
          <!-- Informações Básicas -->
          <div class="col-12">
            <h4>Informações Básicas</h4>
          </div>
          
          <div class="col-6">
            <label for="nome" class="block text-900 font-medium mb-2">Nome *</label>
            <InputText 
              id="nome"
              v-model="form.nome" 
              :class="{ 'p-invalid': v$.form.nome.$error }"
              placeholder="Nome completo"
              class="w-full"
            />
            <small class="p-error" v-if="v$.form.nome.$error">
              {{ v$.form.nome.$errors[0].$message }}
            </small>
          </div>

          <div class="col-6">
            <label for="email" class="block text-900 font-medium mb-2">Email *</label>
            <InputText 
              id="email"
              v-model="form.email" 
              type="email"
              :class="{ 'p-invalid': v$.form.email.$error }"
              placeholder="email@exemplo.com"
              class="w-full"
            />
            <small class="p-error" v-if="v$.form.email.$error">
              {{ v$.form.email.$errors[0].$message }}
            </small>
          </div>

          <div class="col-6">
            <label for="telefone" class="block text-900 font-medium mb-2">Telefone</label>
            <InputText 
              id="telefone"
              v-model="form.telefone" 
              v-mask="'(99) 99999-9999'"
              placeholder="(99) 99999-9999"
              class="w-full"
            />
          </div>

          <div class="col-6">
            <label for="especialidade" class="block text-900 font-medium mb-2">Especialidade</label>
            <InputText 
              id="especialidade"
              v-model="form.especialidade" 
              placeholder="Ex: Futebol, Basquete, etc."
              class="w-full"
            />
          </div>

          <div class="col-6">
            <label for="data_contratacao" class="block text-900 font-medium mb-2">Data de Contratação</label>
            <Calendar 
              id="data_contratacao"
              v-model="form.data_contratacao" 
              dateFormat="dd/mm/yy"
              placeholder="Selecione a data"
              class="w-full"
            />
          </div>

          <div class="col-6">
            <label for="nivel" class="block text-900 font-medium mb-2">Nível *</label>
            <Dropdown 
              id="nivel"
              v-model="form.nivel" 
              :options="niveis"
              optionLabel="label"
              optionValue="value"
              placeholder="Selecione o nível"
              :class="{ 'p-invalid': v$.form.nivel.$error }"
              class="w-full"
            />
            <small class="p-error" v-if="v$.form.nivel.$error">
              {{ v$.form.nivel.$errors[0].$message }}
            </small>
          </div>

          <!-- Configurações de Acesso -->
          <div class="col-12">
            <h4>Configurações de Acesso</h4>
          </div>

          <div class="col-6">
            <label for="senha" class="block text-900 font-medium mb-2">
              {{ editando ? 'Nova Senha (deixe em branco para manter)' : 'Senha *' }}
            </label>
            <Password 
              id="senha"
              v-model="form.senha" 
              :feedback="false"
              :toggleMask="true"
              :class="{ 'p-invalid': v$.form.senha.$error }"
              placeholder="Digite a senha"
              class="w-full"
            />
            <small class="p-error" v-if="v$.form.senha.$error">
              {{ v$.form.senha.$errors[0].$message }}
            </small>
          </div>

          <div class="col-6">
            <label for="ativo" class="block text-900 font-medium mb-2">Status</label>
            <div class="flex align-items-center">
              <InputSwitch v-model="form.ativo" />
              <label class="ml-2">{{ form.ativo ? 'Ativo' : 'Inativo' }}</label>
            </div>
          </div>

          <!-- Observações -->
          <div class="col-12">
            <label for="observacoes" class="block text-900 font-medium mb-2">Observações</label>
            <Textarea 
              id="observacoes"
              v-model="form.observacoes" 
              rows="3"
              placeholder="Observações sobre o treinador..."
              class="w-full"
            />
          </div>
        </div>
      </form>

      <template #footer>
        <div class="flex justify-content-end gap-2">
          <Button 
            label="Cancelar" 
            icon="pi pi-times" 
            class="p-button-text"
            @click="modalVisible = false"
          />
          <Button 
            :label="editando ? 'Atualizar' : 'Salvar'" 
            icon="pi pi-check" 
            @click="salvarTreinador"
            :loading="salvando"
            class="p-button-success"
          />
        </div>
      </template>
    </Dialog>

    <!-- Confirmação de Exclusão -->
    <ConfirmDialog />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength } from '@vuelidate/validators'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoa'
import api from '../services/api'

// Composables
const confirm = useConfirm()
const toast = useToast()

// Estado
const treinadores = ref([])
const loading = ref(false)
const modalVisible = ref(false)
const editando = ref(false)
const salvando = ref(false)
const treinadorEditando = ref(null)

// Filtros
const filters = reactive({
  global: { value: null, matchMode: 'contains' }
})

// Formulário
const form = reactive({
  id: null,
  nome: '',
  email: '',
  telefone: '',
  especialidade: '',
  data_contratacao: null,
  nivel: '',
  senha: '',
  ativo: true,
  observacoes: '',
  usuario_id: null
})

// Opções
const niveis = [
  { label: 'Administrador', value: 'admin' },
  { label: 'Treinador', value: 'treinador' },
  { label: 'Auxiliar', value: 'auxiliar' },
  { label: 'Estagiário', value: 'estagiario' }
]

// Validação
const rules = {
  form: {
    nome: { required },
    email: { required, email },
    nivel: { required },
    senha: computed(() => {
      if (editando.value) {
        return {} // Senha opcional na edição
      }
      return { required, minLength: minLength(6) }
    })
  }
}

const v$ = useVuelidate(rules, { form })

// Métodos
const carregarTreinadores = async () => {
  loading.value = true
  try {
    const response = await api.get('/treinadores')
    treinadores.value = response.data
  } catch (error) {
    console.error('Erro ao carregar treinadores:', error)
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao carregar treinadores',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const abrirModal = () => {
  editando.value = false
  treinadorEditando.value = null
  modalVisible.value = true
}

const editarTreinador = (treinador) => {
  editando.value = true
  treinadorEditando.value = treinador
  
  // Preencher formulário
  Object.assign(form, {
    id: treinador.id,
    nome: treinador.nome,
    email: treinador.email,
    telefone: treinador.telefone || '',
    especialidade: treinador.especialidade || '',
    data_contratacao: treinador.data_contratacao ? new Date(treinador.data_contratacao) : null,
    nivel: treinador.nivel || 'treinador',
    senha: '', // Sempre vazio na edição
    ativo: treinador.ativo !== undefined ? treinador.ativo : true,
    observacoes: treinador.observacoes || '',
    usuario_id: treinador.usuario_id
  })
  
  modalVisible.value = true
}

const salvarTreinador = async () => {
  const isValid = await v$.value.$validate()
  if (!isValid) return

  salvando.value = true
  
  try {
    const dados = {
      ...form,
      data_contratacao: form.data_contratacao ? form.data_contratacao.toISOString().split('T')[0] : null
    }

    if (editando.value) {
      await api.put(`/treinadores/${form.id}`, dados)
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Treinador atualizado com sucesso!',
        life: 3000
      })
    } else {
      await api.post('/treinadores', dados)
      toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Treinador cadastrado com sucesso!',
        life: 3000
      })
    }

    modalVisible.value = false
    await carregarTreinadores()
  } catch (error) {
    console.error('Erro ao salvar treinador:', error)
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: error.response?.data?.message || 'Erro ao salvar treinador',
      life: 3000
    })
  } finally {
    salvando.value = false
  }
}

const confirmarExclusao = (treinador) => {
  confirm.require({
    message: `Tem certeza que deseja excluir o treinador "${treinador.nome}"?`,
    header: 'Confirmar Exclusão',
    icon: 'pi pi-exclamation-triangle',
    accept: () => excluirTreinador(treinador.id)
  })
}

const excluirTreinador = async (id) => {
  try {
    await api.delete(`/treinadores/${id}`)
    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: 'Treinador excluído com sucesso!',
      life: 3000
    })
    await carregarTreinadores()
  } catch (error) {
    console.error('Erro ao excluir treinador:', error)
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: 'Erro ao excluir treinador',
      life: 3000
    })
  }
}

const resetarFormulario = () => {
  Object.assign(form, {
    id: null,
    nome: '',
    email: '',
    telefone: '',
    especialidade: '',
    data_contratacao: null,
    nivel: '',
    senha: '',
    ativo: true,
    observacoes: '',
    usuario_id: null
  })
  v$.value.$reset()
}

const formatarData = (data) => {
  if (!data) return '-'
  return new Date(data).toLocaleDateString('pt-BR')
}

const getSeverityNivel = (nivel) => {
  const severities = {
    'admin': 'danger',
    'treinador': 'success',
    'auxiliar': 'warning',
    'estagiario': 'info'
  }
  return severities[nivel] || 'info'
}

// Lifecycle
onMounted(() => {
  carregarTreinadores()
})
</script>

<style scoped>
.treinadores-container {
  padding: 2rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.header h1 {
  margin: 0;
  color: var(--text-color);
}

.form-treinador {
  margin-top: 1rem;
}

.form-treinador h4 {
  margin: 1.5rem 0 1rem 0;
  color: var(--text-color);
  border-bottom: 1px solid var(--surface-border);
  padding-bottom: 0.5rem;
}

.form-treinador h4:first-child {
  margin-top: 0;
}

:deep(.p-password) {
  width: 100%;
}

:deep(.p-password input) {
  width: 100%;
}

:deep(.p-calendar) {
  width: 100%;
}

:deep(.p-dropdown) {
  width: 100%;
}
</style> 