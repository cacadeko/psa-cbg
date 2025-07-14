<template>
  <aside class="sidebar">
    <nav class="sidebar-menu">
      <router-link
        v-for="item in menu"
        :key="item.route"
        :to="item.route"
        class="sidebar-link"
        :class="{ active: isActive(item.route) }"
        :title="item.label"
      >
        <i :class="item.icon"></i>
      </router-link>
    </nav>
    <div class="sidebar-bottom">
      <img class="avatar" src="/src/assets/avatar-default.png" alt="Avatar" />
      <button class="logout-btn" @click="$emit('logout')">
        <i class="pi pi-sign-out"></i>
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'

const menu = [
  { label: 'Dashboard', icon: 'pi pi-home', route: '/' },
  { label: 'Atletas', icon: 'pi pi-users', route: '/atletas' },
  { label: 'Usuários', icon: 'pi pi-user', route: '/usuarios' },
  { label: 'Treinadores', icon: 'pi pi-user-plus', route: '/treinadores' },
  { label: 'Jogos', icon: 'pi pi-calendar', route: '/jogos' },
  { label: 'Minutagem', icon: 'pi pi-clock', route: '/minutagem' },
  { label: 'Sono', icon: 'pi pi-moon', route: '/sono' },
  { label: 'PSE', icon: 'pi pi-heart', route: '/pse' },
  { label: 'PFE', icon: 'pi pi-exclamation-triangle', route: '/pfe' },
  { label: 'Relatórios', icon: 'pi pi-chart-line', route: '/relatorios' },
]

const route = useRoute()
const isActive = (r: string) => route.path === r
</script>

<style scoped>
.sidebar {
  width: 80px;
  background: #fff;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 100;
  box-shadow: 2px 0 8px #0001;
}
.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 18px;
  margin-top: 32px;
}
.sidebar-link {
  color: #a3a3a3;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  border-radius: 12px;
  transition: background 0.2s, color 0.2s;
}
.sidebar-link.active,
.sidebar-link:hover {
  background: #f3f4f6;
  color: #3b82f6;
}
.sidebar-bottom {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  margin-bottom: 32px;
}
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid #e5e7eb;
}
.logout-btn {
  background: none;
  border: none;
  color: #a3a3a3;
  font-size: 1.5rem;
  cursor: pointer;
  transition: color 0.2s;
}
.logout-btn:hover {
  color: #ef4444;
}
</style> 