<template>
  <div class="dashboard">
    <h1>Dashboard PSA-CBG</h1>
    <p>Bem-vindo ao sistema de gestão de atletas!</p>
    <button @click="logout">Sair</button>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

function logout() {
  localStorage.removeItem('token');
  router.push('/login');
}
</script>

<style scoped>
.dashboard {
  padding: 2rem;
  text-align: center;
}

h1 {
  color: #003366;
  font-size: 2rem;
  margin-bottom: 1rem;
}

p {
  color: #666;
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

button {
  background: #dc3545;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

button:hover {
  background: #c82333;
}
</style> 