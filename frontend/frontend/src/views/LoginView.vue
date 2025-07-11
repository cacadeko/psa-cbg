<template>
  <div class="login-container">
    <div class="login-box">
      <img src="./assets/logo-atm.png" alt="Athletic Map" height="50" style="margin-bottom: 1rem;" />
      <img src="./assets/logo-cbg.svg" alt="CBG" height="50" style="margin-bottom: 1rem; margin-left: 1rem;" />
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div class="p-field">
          <label for="usuario">Usuário</label>
          <input id="usuario" v-model="usuario" type="text" class="p-inputtext p-component" required />
        </div>
        <div class="p-field">
          <label for="senha">Senha</label>
          <input id="senha" v-model="senha" type="password" class="p-inputtext p-component" required />
        </div>
        <Button label="Entrar" type="submit" class="p-mt-2 p-button-primary" />
      </form>
      <div v-if="erro" class="erro-msg">{{ erro }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Button from 'primevue/button';
import api from '../services/api';

const usuario = ref('');
const senha = ref('');
const erro = ref('');
const router = useRouter();

async function login() {
  erro.value = '';
  try {
    const { data } = await api.post('/login', {
      usuario: usuario.value,
      senha: senha.value,
    });
    // Supondo que o backend retorna um token JWT
    localStorage.setItem('token', data.token);
    router.push('/');
  } catch (e: any) {
    erro.value = e.response?.data?.message || 'Usuário ou senha inválidos';
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #f4f4f4;
}
.login-box {
  background: #fff;
  padding: 2rem 2.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 16px #0001;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.p-field {
  margin-bottom: 1rem;
  width: 100%;
}
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
</style> 