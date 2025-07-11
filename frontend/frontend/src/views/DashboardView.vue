<template>
  <div>
    <h2>Dashboard PSA-CBG</h2>
    
    <!-- Cards de Estatísticas -->
    <div class="grid">
      <div class="col-12 md:col-3">
        <div class="card">
          <div class="card-content">
            <div class="card-title">Total de Atletas</div>
            <div class="card-value">{{ stats.atletas }}</div>
            <i class="pi pi-users card-icon"></i>
          </div>
        </div>
      </div>
      <div class="col-12 md:col-3">
        <div class="card">
          <div class="card-content">
            <div class="card-title">Total de Usuários</div>
            <div class="card-value">{{ stats.usuarios }}</div>
            <i class="pi pi-user card-icon"></i>
          </div>
        </div>
      </div>
      <div class="col-12 md:col-3">
        <div class="card">
          <div class="card-content">
            <div class="card-title">Registros de Sono</div>
            <div class="card-value">{{ stats.sono }}</div>
            <i class="pi pi-moon card-icon"></i>
          </div>
        </div>
      </div>
      <div class="col-12 md:col-3">
        <div class="card">
          <div class="card-content">
            <div class="card-title">Registros de PSE</div>
            <div class="card-value">{{ stats.pse }}</div>
            <i class="pi pi-heart card-icon"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfico de Atividade Recente -->
    <div class="grid">
      <div class="col-12">
        <div class="card">
          <h3>Atividade Recente</h3>
          <div class="activity-list">
            <div v-for="activity in recentActivity" :key="activity.id" class="activity-item">
              <i :class="activity.icon" class="activity-icon"></i>
              <div class="activity-content">
                <div class="activity-title">{{ activity.title }}</div>
                <div class="activity-time">{{ activity.time }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../services/api';

const stats = ref({
  atletas: 0,
  usuarios: 0,
  sono: 0,
  pse: 0
});

const recentActivity = ref([
  { id: 1, title: 'Novo atleta cadastrado', time: '2 horas atrás', icon: 'pi pi-user-plus' },
  { id: 2, title: 'Registro de PSE atualizado', time: '4 horas atrás', icon: 'pi pi-heart' },
  { id: 3, title: 'Dados de sono registrados', time: '6 horas atrás', icon: 'pi pi-moon' },
  { id: 4, title: 'Usuário fez login', time: '8 horas atrás', icon: 'pi pi-sign-in' }
]);

async function carregarEstatisticas() {
  try {
    const [atletas, usuarios, sono, pse] = await Promise.all([
      api.get('/atletas'),
      api.get('/usuarios'),
      api.get('/sono'),
      api.get('/pse')
    ]);
    
    stats.value = {
      atletas: atletas.data.length,
      usuarios: usuarios.data.length,
      sono: sono.data.length,
      pse: pse.data.length
    };
  } catch (e) {
    console.error('Erro ao carregar estatísticas:', e);
  }
}

onMounted(carregarEstatisticas);
</script>

<style scoped>
.card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  margin-bottom: 1rem;
}

.card-content {
  position: relative;
}

.card-title {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.card-value {
  font-size: 2rem;
  font-weight: bold;
  color: #003366;
}

.card-icon {
  position: absolute;
  top: 0;
  right: 0;
  font-size: 2rem;
  color: #e0e7ff;
}

.activity-list {
  margin-top: 1rem;
}

.activity-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  font-size: 1.2rem;
  color: #003366;
  margin-right: 1rem;
}

.activity-title {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.activity-time {
  font-size: 0.8rem;
  color: #666;
}
</style> 