<template>
  <div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
      <div class="header-left">
        <div class="search-container">
          <i class="pi pi-search search-icon"></i>
          <input type="text" placeholder="Buscar atletas, treinadores..." class="search-input" />
          <span class="search-shortcut">⌘F</span>
        </div>
      </div>
      <div class="header-right">
        <div class="header-icons">
          <i class="pi pi-envelope notification-icon"></i>
          <i class="pi pi-bell notification-icon"></i>
        </div>
        <div class="user-profile">
          <div class="user-avatar">
            <i class="pi pi-user"></i>
          </div>
          <div class="user-info">
            <div class="user-name">Administrador</div>
            <div class="user-email">admin@psa-cbg.com</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">
      <!-- Dashboard Title -->
      <div class="dashboard-title-section">
        <div class="title-content">
          <h1 class="dashboard-title">Dashboard</h1>
          <p class="dashboard-subtitle">Gerencie atletas, treinadores e acompanhe o desempenho esportivo</p>
        </div>
        <div class="title-actions">
          <button class="btn-primary" @click="navigateTo('/atletas')">
            <i class="pi pi-plus"></i>
            Novo Atleta
          </button>
          <button class="btn-secondary" @click="navigateTo('/relatorios')">
            <i class="pi pi-chart-line"></i>
            Ver Relatórios
          </button>
        </div>
      </div>

      <!-- Metrics Cards -->
      <div class="metrics-grid">
        <div class="metric-card primary">
          <div class="metric-content">
            <div class="metric-number">{{ totalAtletas }}</div>
            <div class="metric-label">Total de Atletas</div>
            <div class="metric-change positive">
              <i class="pi pi-arrow-up"></i>
              +3 este mês
            </div>
          </div>
          <div class="metric-icon">
            <i class="pi pi-users"></i>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-content">
            <div class="metric-number">{{ totalTreinadores }}</div>
            <div class="metric-label">Treinadores Ativos</div>
            <div class="metric-change positive">
              <i class="pi pi-arrow-up"></i>
              +1 este mês
            </div>
          </div>
          <div class="metric-icon">
            <i class="pi pi-user"></i>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-content">
            <div class="metric-number">{{ treinosAgendados }}</div>
            <div class="metric-label">Treinos Agendados</div>
            <div class="metric-change positive">
              <i class="pi pi-arrow-up"></i>
              +5 esta semana
            </div>
          </div>
          <div class="metric-icon">
            <i class="pi pi-calendar"></i>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-content">
            <div class="metric-number">{{ usuariosAtivos }}</div>
            <div class="metric-label">Usuários Ativos</div>
            <div class="metric-change neutral">
              <i class="pi pi-minus"></i>
              Estável
            </div>
          </div>
          <div class="metric-icon">
            <i class="pi pi-user-plus"></i>
          </div>
        </div>
      </div>

      <!-- Main Grid -->
      <div class="main-grid">
        <!-- Analytics Chart -->
        <div class="chart-card">
          <div class="card-header">
            <h3>Performance dos Atletas</h3>
            <div class="card-actions">
              <Button icon="pi pi-refresh" class="p-button-outlined p-button-sm" @click="refreshChart" />
              <Button icon="pi pi-ellipsis-v" class="p-button-outlined p-button-sm" />
            </div>
          </div>
          <div class="chart-content">
            <div class="chart-bars">
              <div class="chart-bar" v-for="(day, index) in weekDays" :key="index">
                <div class="bar-label">{{ day }}</div>
                <div class="bar-container">
                  <div 
                    class="bar-fill" 
                    :style="{ height: getBarHeight(index) + '%' }"
                    :class="getBarClass(index)"
                  ></div>
                </div>
                <div class="bar-value">{{ getBarValue(index) }}%</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="activities-card">
          <div class="card-header">
            <h3>Atividades Recentes</h3>
            <Button icon="pi pi-plus" class="p-button-outlined p-button-sm" />
          </div>
          <div class="activities-list">
            <div class="activity-item" v-for="activity in recentActivities" :key="activity.id">
              <div class="activity-icon" :class="activity.type">
                <i :class="activity.icon"></i>
              </div>
              <div class="activity-content">
                <div class="activity-title">{{ activity.title }}</div>
                <div class="activity-time">{{ activity.time }}</div>
              </div>
              <div class="activity-status" :class="activity.status">
                {{ activity.statusText }}
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-card">
          <div class="card-header">
            <h3>Ações Rápidas</h3>
          </div>
          <div class="actions-grid">
            <button class="action-btn" @click="navigateTo('/atletas')">
              <i class="pi pi-users"></i>
              <span>Gerenciar Atletas</span>
            </button>
            <button class="action-btn" @click="navigateTo('/treinadores')">
              <i class="pi pi-user"></i>
              <span>Gerenciar Treinadores</span>
            </button>
            <button class="action-btn" @click="navigateTo('/usuarios')">
              <i class="pi pi-user-plus"></i>
              <span>Gerenciar Usuários</span>
            </button>
            <button class="action-btn" @click="navigateTo('/pre-treino')">
              <i class="pi pi-calendar-plus"></i>
              <span>Agendar Treino</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Bottom Grid -->
      <div class="bottom-grid">
        <!-- Team Status -->
        <div class="team-card">
          <div class="card-header">
            <h3>Status da Equipe</h3>
            <button class="btn-primary-small">
              <i class="pi pi-plus"></i>
              Adicionar
            </button>
          </div>
          <div class="team-list">
            <div class="team-member" v-for="member in teamMembers" :key="member.id">
              <div class="member-avatar">
                <i class="pi pi-user"></i>
              </div>
              <div class="member-info">
                <div class="member-name">{{ member.name }}</div>
                <div class="member-task">{{ member.task }}</div>
              </div>
              <div class="member-status" :class="member.status">
                {{ member.statusText }}
              </div>
            </div>
          </div>
        </div>

        <!-- Progress Overview -->
        <div class="progress-card">
          <div class="card-header">
            <h3>Visão Geral</h3>
          </div>
          <div class="progress-content">
            <div class="progress-chart">
              <div class="progress-circle">
                <div class="progress-text">
                  <div class="progress-number">{{ overallProgress }}%</div>
                  <div class="progress-label">Meta Atingida</div>
                </div>
              </div>
            </div>
            <div class="progress-legend">
              <div class="legend-item">
                <div class="legend-color completed"></div>
                <span>Concluído</span>
              </div>
              <div class="legend-item">
                <div class="legend-color in-progress"></div>
                <span>Em Andamento</span>
              </div>
              <div class="legend-item">
                <div class="legend-color pending"></div>
                <span>Pendente</span>
              </div>
            </div>
          </div>
        </div>

        <!-- System Status -->
        <div class="status-card">
          <div class="card-header">
            <h3>Status do Sistema</h3>
          </div>
          <div class="status-content">
            <div class="status-timer">
              <div class="timer-display">{{ timerDisplay }}</div>
              <div class="timer-controls">
                <button class="timer-btn" @click="toggleTimer">
                  <i :class="timerRunning ? 'pi pi-pause' : 'pi pi-play'"></i>
                </button>
                <button class="timer-btn stop" @click="stopTimer">
                  <i class="pi pi-stop"></i>
                </button>
              </div>
            </div>
            <div class="status-info">
              <div class="status-item">
                <span class="status-label">Servidor:</span>
                <span class="status-value online">Online</span>
              </div>
              <div class="status-item">
                <span class="status-label">Banco de Dados:</span>
                <span class="status-value online">Conectado</span>
              </div>
              <div class="status-item">
                <span class="status-label">Última Atualização:</span>
                <span class="status-value">{{ lastUpdate }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import Button from 'primevue/button';
import api from '../services/api';

const router = useRouter();

// Metrics data
const totalAtletas = ref(24);
const totalTreinadores = ref(8);
const treinosAgendados = ref(12);
const usuariosAtivos = ref(15);
const overallProgress = ref(74);

// Timer state
const timerRunning = ref(false);
const timerDisplay = ref('01:24:08');
const lastUpdate = ref('2 min atrás');

// Week days for chart
const weekDays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

// Recent activities
const recentActivities = ref([
  {
    id: 1,
    title: 'Novo atleta cadastrado',
    time: '10:30 AM',
    type: 'success',
    icon: 'pi pi-user-plus',
    status: 'completed',
    statusText: 'Concluído'
  },
  {
    id: 2,
    title: 'Treino agendado',
    time: '09:15 AM',
    type: 'info',
    icon: 'pi pi-calendar-plus',
    status: 'in-progress',
    statusText: 'Em Andamento'
  },
  {
    id: 3,
    title: 'Relatório gerado',
    time: '08:45 AM',
    type: 'warning',
    icon: 'pi pi-file-pdf',
    status: 'pending',
    statusText: 'Pendente'
  }
]);

// Team members
const teamMembers = ref([
  {
    id: 1,
    name: 'João Silva',
    task: 'Treinamento de força',
    status: 'completed',
    statusText: 'Concluído'
  },
  {
    id: 2,
    name: 'Maria Santos',
    task: 'Avaliação física',
    status: 'in-progress',
    statusText: 'Em Andamento'
  },
  {
    id: 3,
    name: 'Pedro Costa',
    task: 'Recuperação muscular',
    status: 'pending',
    statusText: 'Pendente'
  }
]);

// Chart functions
const getBarHeight = (index: number) => {
  const heights = [65, 80, 74, 90, 85, 70, 60];
  return heights[index] || 50;
};

const getBarValue = (index: number) => {
  const values = [65, 80, 74, 90, 85, 70, 60];
  return values[index] || 50;
};

const getBarClass = (index: number) => {
  const height = getBarHeight(index);
  if (height >= 80) return 'high';
  if (height >= 60) return 'medium';
  return 'low';
};

// Navigation
const navigateTo = (path: string) => {
  router.push(path);
};

// Timer functions
const toggleTimer = () => {
  timerRunning.value = !timerRunning.value;
};

const stopTimer = () => {
  timerRunning.value = false;
  timerDisplay.value = '00:00:00';
};

// Refresh chart
const refreshChart = () => {
  // Simulate chart refresh
  console.log('Chart refreshed');
};

// Load dashboard data
const loadDashboardData = async () => {
  try {
    // Load metrics from API
    const [atletasRes, treinadoresRes, usuariosRes] = await Promise.all([
      api.get('/atletas'),
      api.get('/treinadores'),
      api.get('/usuarios')
    ]);

    totalAtletas.value = atletasRes.data.length;
    totalTreinadores.value = treinadoresRes.data.length;
    usuariosAtivos.value = usuariosRes.data.length;
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error);
  }
};

onMounted(() => {
  loadDashboardData();
});

onUnmounted(() => {
  timerRunning.value = false;
});
</script>

<style scoped>
.dashboard-container {
  min-height: 100vh;
  background: #f8f9fa;
  font-family: 'Inter', sans-serif;
}

/* Header Styles */
.dashboard-header {
  background: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e9ecef;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-left {
  flex: 1;
  max-width: 400px;
}

.search-container {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.875rem;
  background: #f8f9fa;
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  color: #6c757d;
  font-size: 0.875rem;
}

.search-shortcut {
  position: absolute;
  right: 0.75rem;
  background: #e9ecef;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  color: #6c757d;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.header-icons {
  display: flex;
  gap: 0.5rem;
}

.notification-icon {
  padding: 0.5rem;
  border-radius: 6px;
  color: #6c757d;
  cursor: pointer;
  transition: background-color 0.2s;
}

.notification-icon:hover {
  background: #f8f9fa;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.user-profile:hover {
  background: #f8f9fa;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: #198754;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  font-size: 0.875rem;
  color: #212529;
}

.user-email {
  font-size: 0.75rem;
  color: #6c757d;
}

/* Main Content */
.dashboard-content {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

/* Title Section */
.dashboard-title-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
}

.dashboard-title {
  font-size: 2rem;
  font-weight: 700;
  color: #212529;
  margin: 0 0 0.5rem 0;
}

.dashboard-subtitle {
  color: #6c757d;
  margin: 0;
  font-size: 1rem;
}

.title-actions {
  display: flex;
  gap: 0.75rem;
}

/* Buttons */
.btn-primary, .btn-secondary {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.btn-primary {
  background: #198754;
  color: white;
}

.btn-primary:hover {
  background: #157347;
  transform: translateY(-1px);
}

.btn-secondary {
  background: white;
  color: #198754;
  border: 1px solid #198754;
}

.btn-secondary:hover {
  background: #f8f9fa;
}

.btn-primary-small {
  padding: 0.5rem 1rem;
  background: #198754;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 0.75rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

/* Metrics Grid */
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.metric-card {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.2s;
}

.metric-card:hover {
  transform: translateY(-2px);
}

.metric-card.primary {
  background: linear-gradient(135deg, #198754, #157347);
  color: white;
}

.metric-content {
  flex: 1;
}

.metric-number {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.metric-label {
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
}

.metric-card.primary .metric-label {
  color: rgba(255,255,255,0.8);
}

.metric-change {
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.metric-change.positive {
  color: #198754;
}

.metric-change.neutral {
  color: #6c757d;
}

.metric-card.primary .metric-change {
  color: rgba(255,255,255,0.8);
}

.metric-icon {
  width: 48px;
  height: 48px;
  background: #f8f9fa;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #198754;
}

.metric-card.primary .metric-icon {
  background: rgba(255,255,255,0.2);
  color: white;
}

/* Main Grid */
.main-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* Cards */
.chart-card, .activities-card, .quick-actions-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  overflow: hidden;
}

.card-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #212529;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

/* Chart */
.chart-content {
  padding: 1.5rem;
}

.chart-bars {
  display: flex;
  justify-content: space-between;
  align-items: end;
  height: 200px;
  gap: 0.5rem;
}

.chart-bar {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.bar-label {
  font-size: 0.75rem;
  color: #6c757d;
  font-weight: 600;
}

.bar-container {
  width: 100%;
  height: 120px;
  background: #f8f9fa;
  border-radius: 6px;
  position: relative;
  overflow: hidden;
}

.bar-fill {
  position: absolute;
  bottom: 0;
  width: 100%;
  border-radius: 6px;
  transition: height 0.3s ease;
}

.bar-fill.high {
  background: #198754;
}

.bar-fill.medium {
  background: #ffc107;
}

.bar-fill.low {
  background: #dc3545;
}

.bar-value {
  font-size: 0.75rem;
  font-weight: 600;
  color: #212529;
}

/* Activities */
.activities-list {
  padding: 1.5rem;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
}

.activity-icon.success {
  background: #198754;
}

.activity-icon.info {
  background: #0dcaf0;
}

.activity-icon.warning {
  background: #ffc107;
}

.activity-content {
  flex: 1;
}

.activity-title {
  font-weight: 600;
  color: #212529;
  margin-bottom: 0.25rem;
}

.activity-time {
  font-size: 0.75rem;
  color: #6c757d;
}

.activity-status {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

.activity-status.completed {
  background: #d1e7dd;
  color: #0f5132;
}

.activity-status.in-progress {
  background: #fff3cd;
  color: #856404;
}

.activity-status.pending {
  background: #f8d7da;
  color: #721c24;
}

/* Quick Actions */
.actions-grid {
  padding: 1.5rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.action-btn {
  padding: 1rem;
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.action-btn:hover {
  background: #e9ecef;
  transform: translateY(-1px);
}

.action-btn i {
  font-size: 1.5rem;
  color: #198754;
}

.action-btn span {
  font-size: 0.875rem;
  font-weight: 600;
  color: #212529;
}

/* Bottom Grid */
.bottom-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 1.5rem;
}

.team-card, .progress-card, .status-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  overflow: hidden;
}

/* Team */
.team-list {
  padding: 1.5rem;
}

.team-member {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.team-member:last-child {
  border-bottom: none;
}

.member-avatar {
  width: 40px;
  height: 40px;
  background: #198754;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.member-info {
  flex: 1;
}

.member-name {
  font-weight: 600;
  color: #212529;
  margin-bottom: 0.25rem;
}

.member-task {
  font-size: 0.75rem;
  color: #6c757d;
}

.member-status {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

.member-status.completed {
  background: #d1e7dd;
  color: #0f5132;
}

.member-status.in-progress {
  background: #fff3cd;
  color: #856404;
}

.member-status.pending {
  background: #f8d7da;
  color: #721c24;
}

/* Progress */
.progress-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.progress-chart {
  position: relative;
  width: 120px;
  height: 120px;
}

.progress-circle {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: conic-gradient(#198754 0deg 266deg, #e9ecef 266deg 360deg);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.progress-circle::before {
  content: '';
  position: absolute;
  width: 80px;
  height: 80px;
  background: white;
  border-radius: 50%;
}

.progress-text {
  position: relative;
  z-index: 1;
  text-align: center;
}

.progress-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: #212529;
}

.progress-label {
  font-size: 0.75rem;
  color: #6c757d;
}

.progress-legend {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.legend-color {
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.legend-color.completed {
  background: #198754;
}

.legend-color.in-progress {
  background: #ffc107;
}

.legend-color.pending {
  background: #6c757d;
}

/* Status */
.status-content {
  padding: 1.5rem;
}

.status-timer {
  text-align: center;
  margin-bottom: 1.5rem;
}

.timer-display {
  font-size: 2rem;
  font-weight: 700;
  color: #198754;
  margin-bottom: 1rem;
}

.timer-controls {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}

.timer-btn {
  width: 40px;
  height: 40px;
  border: none;
  border-radius: 8px;
  background: #f8f9fa;
  color: #6c757d;
  cursor: pointer;
  transition: all 0.2s;
}

.timer-btn:hover {
  background: #e9ecef;
}

.timer-btn.stop {
  background: #dc3545;
  color: white;
}

.timer-btn.stop:hover {
  background: #c82333;
}

.status-info {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.status-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.status-item:last-child {
  border-bottom: none;
}

.status-label {
  font-size: 0.875rem;
  color: #6c757d;
}

.status-value {
  font-size: 0.875rem;
  font-weight: 600;
}

.status-value.online {
  color: #198754;
}

/* Responsive */
@media (max-width: 1200px) {
  .main-grid {
    grid-template-columns: 1fr;
  }
  
  .bottom-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-header {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-left {
    max-width: 100%;
  }
  
  .dashboard-content {
    padding: 1rem;
  }
  
  .dashboard-title-section {
    flex-direction: column;
    gap: 1rem;
  }
  
  .title-actions {
    width: 100%;
    justify-content: stretch;
  }
  
  .btn-primary, .btn-secondary {
    flex: 1;
    justify-content: center;
  }
  
  .metrics-grid {
    grid-template-columns: 1fr;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
}
</style> 