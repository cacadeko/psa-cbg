<template>
  <header class="topbar">
    <div class="topbar-left">
      <span class="topbar-title">Dashboard PSA-CBG</span>
    </div>
    <div class="topbar-center">
      <input type="text" class="search-input" placeholder="Buscar..." v-model="search" />
    </div>
    <div class="topbar-right">
      <button class="icon-btn">
        <i class="pi pi-bell"></i>
      </button>
      <button class="icon-btn">
        <i class="pi pi-cog"></i>
      </button>
      <img class="avatar" src="/src/assets/avatar-default.png" alt="Avatar" />
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const search = ref('')
</script>

<style scoped>
.topbar {
  width: 100%;
  height: 64px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 8px #0001;
  padding: 0 32px 0 96px;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 90;
}
.topbar-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #3b3b3b;
  letter-spacing: 1px;
}
.topbar-center {
  flex: 1;
  display: flex;
  justify-content: center;
}
.search-input {
  width: 320px;
  padding: 10px 18px;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  background: #f6f8fb;
  font-size: 1rem;
  outline: none;
  transition: border 0.2s;
}
.search-input:focus {
  border: 1.5px solid #3b82f6;
}
.topbar-right {
  display: flex;
  align-items: center;
  gap: 18px;
}
.icon-btn {
  background: none;
  border: none;
  color: #a3a3a3;
  font-size: 1.5rem;
  cursor: pointer;
  transition: color 0.2s;
  padding: 6px;
  border-radius: 8px;
}
.icon-btn:hover {
  color: #3b82f6;
  background: #f3f4f6;
}
.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  border: 2px solid #e5e7eb;
}
@media (max-width: 900px) {
  .search-input { width: 180px; }
  .topbar { padding: 0 8px 0 88px; }
}
</style> 