<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRoute, useRouter, RouterView } from 'vue-router';
// PrimeVue
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Toast from 'primevue/toast';

const user = ref({ nome: 'Administrador', email: 'admin@psa-cbg.com' });
const router = useRouter();
const route = useRoute();

const menuItems = [
  { 
    label: 'Dashboard', 
    icon: 'pi pi-chart-bar', 
    route: '/',
    badge: null
  },
  { 
    label: 'Atletas', 
    icon: 'pi pi-users', 
    route: '/atletas',
    badge: null
  },
  { 
    label: 'Treinadores', 
    icon: 'pi pi-user-plus', 
    route: '/treinadores',
    badge: null
  },
  { 
    label: 'Usuários', 
    icon: 'pi pi-user', 
    route: '/usuarios',
    badge: null
  },
  { 
    label: 'Pré-Treino', 
    icon: 'pi pi-dumbbell', 
    route: '/pre-treino',
    badge: null
  },
  { 
    label: 'Jogos', 
    icon: 'pi pi-calendar', 
    route: '/jogos',
    badge: null
  },
  { 
    label: 'Minutagem', 
    icon: 'pi pi-clock', 
    route: '/minutagem',
    badge: null
  },
  { 
    label: 'Qualidade do Sono', 
    icon: 'pi pi-moon', 
    route: '/sono',
    badge: null
  },
  { 
    label: 'PSE', 
    icon: 'pi pi-heart', 
    route: '/pse',
    badge: null
  },
  { 
    label: 'PFE', 
    icon: 'pi pi-exclamation-triangle', 
    route: '/pfe',
    badge: null
  }
];

const generalItems = [
  { 
    label: 'Relatórios', 
    icon: 'pi pi-chart-line', 
    route: '/relatorios',
    badge: null
  },
  { 
    label: 'Configurações', 
    icon: 'pi pi-cog', 
    route: '/configuracoes',
    badge: null
  },
  { 
    label: 'Ajuda', 
    icon: 'pi pi-question-circle', 
    route: '/ajuda',
    badge: null
  }
];

function goTo(routePath: string) {
  router.push(routePath);
}

function logout() {
  localStorage.removeItem('token');
  router.push('/login');
}

const isLoginPage = computed(() => route.path === '/login');
const isActiveRoute = (routePath: string) => route.path === routePath;
</script>

<template>
  <div class="layout">
    <template v-if="!isLoginPage">
      <!-- Sidebar Fixa -->
      <div class="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
          <div class="logo-container">
            <div class="logo-icon">
              <i class="pi pi-users"></i>
            </div>
            <div class="logo-text">
              <div class="logo-title">PSA-CBG</div>
              <div class="logo-subtitle">Gestão Esportiva</div>
            </div>
          </div>
        </div>

        <!-- Menu Section -->
        <div class="sidebar-section">
          <div class="section-title">MENU</div>
          <ul class="sidebar-menu">
            <li 
              v-for="item in menuItems" 
              :key="item.label" 
              @click="goTo(item.route)"
              :class="{ 'active': isActiveRoute(item.route) }"
            >
              <div class="menu-item-content">
                <i :class="item.icon"></i>
                <span>{{ item.label }}</span>
              </div>
              <span v-if="item.badge" class="menu-badge">{{ item.badge }}</span>
            </li>
          </ul>
        </div>

        <!-- General Section -->
        <div class="sidebar-section">
          <div class="section-title">GERAL</div>
          <ul class="sidebar-menu">
            <li 
              v-for="item in generalItems" 
              :key="item.label" 
              @click="goTo(item.route)"
              :class="{ 'active': isActiveRoute(item.route) }"
            >
              <div class="menu-item-content">
                <i :class="item.icon"></i>
                <span>{{ item.label }}</span>
              </div>
              <span v-if="item.badge" class="menu-badge">{{ item.badge }}</span>
            </li>
          </ul>
        </div>

        <!-- Mobile App Download Section -->
        <div class="mobile-app-section">
          <div class="mobile-app-card">
            <div class="mobile-app-icon">
              <i class="pi pi-mobile"></i>
            </div>
            <div class="mobile-app-content">
              <div class="mobile-app-title">App Mobile</div>
              <div class="mobile-app-subtitle">Acesse de qualquer lugar</div>
              <button class="mobile-app-btn">
                <i class="pi pi-download"></i>
                Download
              </button>
            </div>
          </div>
        </div>

        <!-- Logout -->
        <div class="sidebar-footer">
          <button class="logout-btn" @click="logout">
            <i class="pi pi-sign-out"></i>
            <span>Sair</span>
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="main-wrapper">
        <RouterView />
      </div>
    </template>
    
    <!-- Login Content -->
    <div v-else class="login-content">
      <RouterView />
    </div>
    
    <!-- Toast para notificações -->
    <Toast />
  </div>
</template>

<style scoped>
.layout {
  display: flex;
  min-height: 100vh;
  background: #f8f9fa;
  font-family: 'Inter', sans-serif;
}

/* Sidebar Styles */
.sidebar {
  width: 280px;
  background: white;
  border-right: 1px solid #e9ecef;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
  overflow-y: auto;
}

.sidebar-logo {
  padding: 2rem 1.5rem 1.5rem;
  border-bottom: 1px solid #f8f9fa;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  width: 40px;
  height: 40px;
  background: #198754;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.25rem;
}

.logo-text {
  display: flex;
  flex-direction: column;
}

.logo-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #212529;
  line-height: 1.2;
}

.logo-subtitle {
  font-size: 0.75rem;
  color: #6c757d;
  font-weight: 500;
}

/* Sidebar Sections */
.sidebar-section {
  padding: 1.5rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.section-title {
  padding: 0 1.5rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-menu li {
  cursor: pointer;
  padding: 0.75rem 1.5rem;
  margin: 0.125rem 0;
  border-radius: 0;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
}

.sidebar-menu li:hover {
  background: #f8f9fa;
}

.sidebar-menu li.active {
  background: #198754;
  color: white;
}

.sidebar-menu li.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: #157347;
}

.menu-item-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.menu-item-content i {
  font-size: 1rem;
  width: 20px;
  text-align: center;
}

.menu-badge {
  background: #dc3545;
  color: white;
  padding: 0.125rem 0.5rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  min-width: 20px;
  text-align: center;
}

/* Mobile App Section */
.mobile-app-section {
  padding: 1.5rem;
  margin-top: auto;
}

.mobile-app-card {
  background: #198754;
  border-radius: 12px;
  padding: 1.25rem;
  color: white;
  text-align: center;
}

.mobile-app-icon {
  width: 48px;
  height: 48px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  font-size: 1.5rem;
}

.mobile-app-title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.mobile-app-subtitle {
  font-size: 0.75rem;
  opacity: 0.8;
  margin-bottom: 1rem;
}

.mobile-app-btn {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin: 0 auto;
  transition: all 0.2s;
}

.mobile-app-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Sidebar Footer */
.sidebar-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #f8f9fa;
}

.logout-btn {
  width: 100%;
  background: none;
  border: none;
  color: #6c757d;
  padding: 0.75rem;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s;
}

.logout-btn:hover {
  background: #f8f9fa;
  color: #dc3545;
}

/* Main Content */
.main-wrapper {
  flex: 1;
  margin-left: 280px;
  min-height: 100vh;
}

.login-content {
  width: 100%;
  background: #f4f4f4;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  .sidebar.show {
    transform: translateX(0);
  }
  
  .main-wrapper {
    margin-left: 0;
  }
}

/* Scrollbar personalizado para a sidebar */
.sidebar::-webkit-scrollbar {
  width: 6px;
}

.sidebar::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.sidebar::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
