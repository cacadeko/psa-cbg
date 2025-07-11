<script setup lang="ts">
import { ref } from 'vue';
import { useRouter, RouterView } from 'vue-router';
// Importe componentes do PrimeVue
import Sidebar from 'primevue/sidebar';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';

// Simulação de usuário logado
const user = ref({ nome: 'Usuário Exemplo' });
const sidebarVisible = ref(false);
const router = useRouter();

const menuItems = [
  { label: 'Dashboard', icon: 'pi pi-chart-bar', route: '/' },
  { label: 'Atletas', icon: 'pi pi-users', route: '/atletas' },
  { label: 'Usuários', icon: 'pi pi-user', route: '/usuarios' },
  { label: 'Sono', icon: 'pi pi-moon', route: '/sono' },
  { label: 'PSE', icon: 'pi pi-heart', route: '/pse' },
  // Adicione mais menus conforme necessário
];

function goTo(route: string) {
  sidebarVisible.value = false;
  router.push(route);
}

function logout() {
  // Lógica de logout
  alert('Logout!');
}
</script>

<template>
  <div class="layout">
    <!-- Sidebar -->
    <Sidebar v-model:visible="sidebarVisible" :modal="false" class="sidebar">
      <div class="sidebar-logo">
        <img src="./assets/logo-atm.png" alt="Athletic Map" height="40" />
        <img src="./assets/logo-cbg.svg" alt="CBG" height="40" style="margin-left: 8px;" />
      </div>
      <ul class="sidebar-menu">
        <li v-for="item in menuItems" :key="item.label" @click="goTo(item.route)">
          <i :class="item.icon" style="margin-right:8px" /> {{ item.label }}
        </li>
      </ul>
    </Sidebar>

    <!-- Topbar -->
    <div class="topbar">
      <Button icon="pi pi-bars" class="p-button-text p-button-lg" @click="sidebarVisible = true" />
      <div class="topbar-title">PSA-CBG Dashboard</div>
      <div class="topbar-user">
        <span>{{ user.nome }}</span>
        <Avatar icon="pi pi-user" shape="circle" style="margin: 0 8px;" />
        <Button icon="pi pi-sign-out" class="p-button-text p-button-danger" @click="logout" />
      </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
      <RouterView />
    </div>
  </div>
</template>

<style scoped>
.layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #003366;
  color: #fff;
  padding: 0 1.5rem;
  height: 60px;
}
.topbar-title {
  font-size: 1.3rem;
  font-weight: bold;
  letter-spacing: 1px;
}
.topbar-user {
  display: flex;
  align-items: center;
}
.sidebar {
  width: 220px;
  background: #f4f4f4;
  padding: 1rem 0.5rem;
  height: 100vh;
}
.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}
.sidebar-menu {
  list-style: none;
  padding: 0;
}
.sidebar-menu li {
  cursor: pointer;
  padding: 0.7rem 1rem;
  border-radius: 4px;
  margin-bottom: 0.5rem;
  transition: background 0.2s;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
}
.sidebar-menu li:hover {
  background: #e0e7ff;
}
.main-content {
  flex: 1;
  padding: 2rem;
  background: #f9f9f9;
  overflow-y: auto;
}
</style>
