<template>
  <div class="relatorio-presenca">
    <div class="header">
      <div class="header-content">
        <img src="https://upload.wikimedia.org/wikipedia/pt/thumb/0/00/CBG_logo.svg/1200px-CBG_logo.svg.png" alt="CBG" class="logo">
        <strong>CONFEDERAÇÃO BRASILEIRA DE GINÁSTICA</strong>
      </div>
    </div>

    <div class="container">
      <h3 class="titulo">Relatório de Presença</h3>

      <!-- Filtros -->
      <div class="filtros">
        <div class="filtro-row">
          <div class="filtro-item">
            <label class="form-label">Filtrar por Mês:</label>
            <Calendar v-model="filtros.mes" view="month" dateFormat="yy-mm" />
          </div>
          <div class="filtro-item">
            <label class="form-label">Filtrar por Semana:</label>
            <Calendar v-model="filtros.semana" view="week" dateFormat="yy-mm-dd" />
          </div>
        </div>
        <Button @click="filtrar" label="Filtrar" class="btn-filtrar" />
      </div>

      <!-- Tabela -->
      <div class="table-container">
        <DataTable :value="presencas" :loading="loading" stripedRows class="table-presenca">
          <Column field="atleta" header="Atleta" sortable></Column>
          <Column field="segunda" header="Segunda" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.segunda" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="terca" header="Terça" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.terca" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="quarta" header="Quarta" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.quarta" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="quinta" header="Quinta" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.quinta" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="sexta" header="Sexta" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.sexta" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="sabado" header="Sábado" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.sabado" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="domingo" header="Domingo" class="text-center">
            <template #body="slotProps">
              <span v-if="slotProps.data.domingo" class="presenca">✔️</span>
              <span v-else class="falta">-</span>
            </template>
          </Column>
          <Column field="faltas" header="Faltas no Mês" class="text-center">
            <template #body="slotProps">
              <span class="faltas-count">{{ slotProps.data.faltas }}</span>
            </template>
          </Column>
        </DataTable>
      </div>

      <div class="actions">
        <Button @click="voltar" label="Voltar" class="btn-voltar" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const loading = ref(false)
const presencas = ref([])

const filtros = ref({
  mes: null,
  semana: null
})

const carregarPresencas = async () => {
  loading.value = true
  try {
    // Aqui você faria a chamada para a API
    // const response = await apiService.getRelatorioPresenca(filtros.value)
    
    // Dados mockados para exemplo
    presencas.value = [
      {
        atleta: 'Sofia Nunes',
        segunda: true,
        terca: true,
        quarta: false,
        quinta: true,
        sexta: true,
        sabado: true,
        domingo: false,
        faltas: 2
      },
      {
        atleta: 'Laura Rocha',
        segunda: true,
        terca: true,
        quarta: true,
        quinta: true,
        sexta: false,
        sabado: true,
        domingo: true,
        faltas: 1
      },
      {
        atleta: 'Isabela Moura',
        segunda: true,
        terca: false,
        quarta: true,
        quinta: true,
        sexta: true,
        sabado: false,
        domingo: true,
        faltas: 2
      }
    ]
  } catch (error) {
    console.error('Erro ao carregar presenças:', error)
  } finally {
    loading.value = false
  }
}

const filtrar = () => {
  carregarPresencas()
}

const voltar = () => {
  router.push('/')
}

onMounted(() => {
  carregarPresencas()
})
</script>

<style scoped>
.relatorio-presenca {
  background: #07272D;
  color: #FFF;
  min-height: 100vh;
  padding: 10px;
}

.header {
  background-color: #001f7f;
  color: white;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  border-radius: 8px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  height: 40px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.titulo {
  text-align: center;
  margin-bottom: 30px;
  color: #FFF;
}

.filtros {
  background-color: rgba(255, 255, 255, 0.1);
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
}

.filtro-row {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.filtro-item {
  flex: 1;
}

.form-label {
  font-weight: 500;
  color: #dddddd;
  display: block;
  margin-bottom: 8px;
}

.btn-filtrar {
  width: 100%;
  background-color: #007bff;
  border: none;
  padding: 10px;
  border-radius: 4px;
  color: white;
  font-weight: bold;
  cursor: pointer;
}

.btn-filtrar:hover {
  background-color: #0056b3;
}

.table-container {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 20px;
}

.table-presenca {
  background-color: transparent;
}

.table-presenca :deep(.p-datatable-thead > tr > th) {
  background-color: #343a40;
  color: white;
  border-color: #495057;
  font-weight: bold;
}

.table-presenca :deep(.p-datatable-tbody > tr) {
  background-color: rgba(255, 255, 255, 0.05);
  color: white;
}

.table-presenca :deep(.p-datatable-tbody > tr:nth-child(even)) {
  background-color: rgba(255, 255, 255, 0.1);
}

.table-presenca :deep(.p-datatable-tbody > tr:hover) {
  background-color: rgba(255, 255, 255, 0.15);
}

.text-center {
  text-align: center;
}

.presenca {
  color: #28a745;
  font-size: 18px;
}

.falta {
  color: #dc3545;
  font-weight: bold;
}

.faltas-count {
  background-color: #dc3545;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: bold;
  font-size: 12px;
}

.actions {
  display: flex;
  justify-content: flex-end;
}

.btn-voltar {
  background-color: #6c757d;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  color: white;
  font-weight: bold;
  cursor: pointer;
}

.btn-voltar:hover {
  background-color: #545b62;
}

@media (max-width: 768px) {
  .filtro-row {
    flex-direction: column;
  }
  
  .container {
    padding: 10px;
  }
  
  .table-container {
    overflow-x: auto;
  }
}
</style> 