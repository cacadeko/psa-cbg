<template>
  <div class="dashboard-page">
    <div class="dashboard-cards">
      <ResumoCard icon="pi pi-users" :value="120" label="Atletas" bg="#F6F8FB" iconBg="#3B82F6" />
      <ResumoCard icon="pi pi-user" :value="18" label="Usuários" bg="#F6F8FB" iconBg="#8B5CF6" />
      <ResumoCard icon="pi pi-user-plus" :value="6" label="Treinadores" bg="#F6F8FB" iconBg="#10B981" />
      <ResumoCard icon="pi pi-calendar" :value="14" label="Jogos" bg="#F6F8FB" iconBg="#F59E42" />
    </div>
    <div class="dashboard-main-grid">
      <div class="card dashboard-graph">
        <div class="card-title">Evolução de Carga Semanal</div>
        <div class="card-content">
          <!-- Aqui vai o gráfico Chart.js -->
          <div class="chart-placeholder">[Gráfico]</div>
        </div>
      </div>
      <div class="card dashboard-list">
        <div class="card-title">Atividades Recentes</div>
        <ul class="activity-list">
          <li v-for="item in atividades" :key="item.id">
            <span class="activity-label">{{ item.label }}</span>
            <span class="activity-status" :class="item.status">{{ item.status }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ResumoCard from '../components/ResumoCard.vue'
const atividades = [
  { id: 1, label: 'Atleta cadastrado', status: 'concluída' },
  { id: 2, label: 'Treino registrado', status: 'em andamento' },
  { id: 3, label: 'Relatório gerado', status: 'concluída' },
  { id: 4, label: 'Minutagem atualizada', status: 'pendente' },
]
</script>

<style scoped>
.dashboard-page {
  padding: 96px 32px 32px 120px;
  background: #F6F8FB;
  min-height: 100vh;
}
.dashboard-cards {
  display: flex;
  gap: 32px;
  margin-bottom: 32px;
}
.dashboard-main-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 32px;
}
.card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 8px #0001;
  padding: 2rem;
  min-height: 320px;
  display: flex;
  flex-direction: column;
}
.card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 18px;
  color: #3b3b3b;
}
.chart-placeholder {
  width: 100%;
  height: 220px;
  background: #f3f4f6;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #a3a3a3;
  font-size: 1.1rem;
}
.activity-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.activity-label {
  font-weight: 500;
  color: #222;
}
.activity-status {
  margin-left: 18px;
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  text-transform: capitalize;
}
.activity-status.concluída {
  background: #d1fae5;
  color: #10b981;
}
.activity-status['em andamento'], .activity-status.em\ andamento {
  background: #fef9c3;
  color: #f59e42;
}
.activity-status.pendente {
  background: #fee2e2;
  color: #ef4444;
}
@media (max-width: 1100px) {
  .dashboard-main-grid {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 700px) {
  .dashboard-cards {
    flex-direction: column;
    gap: 18px;
  }
  .dashboard-page {
    padding: 96px 8px 8px 88px;
  }
}
</style> 