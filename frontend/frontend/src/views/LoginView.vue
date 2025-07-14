<template>
  <div class="login-container">
    <div class="login-box">
      <h2>Login PSA-CBG</h2>
      <form @submit.prevent="login">
        <div class="field">
          <label for="usuario">Usuário</label>
          <input id="usuario" v-model="usuario" type="text" required />
        </div>
        <div class="field">
          <label for="senha">Senha</label>
          <input id="senha" v-model="senha" type="password" required />
        </div>
        <button type="submit">Entrar</button>
      </form>
      <div v-if="erro" class="erro-msg">{{ erro }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const usuario = ref('');
const senha = ref('');
const erro = ref('');
const router = useRouter();

function login() {
  erro.value = '';
  if (usuario.value === 'admin' && senha.value === 'admin') {
    localStorage.setItem('token', 'fake-token');
    router.push('/dashboard');
  } else {
    erro.value = 'Usuário ou senha inválidos';
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
  box-shadow: 0 2px 16px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 300px;
}
.field {
  margin-bottom: 1rem;
  width: 100%;
}
.field label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}
.field input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
button {
  background: #003366;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  width: 100%;
}
button:hover {
  background: #004488;
}
.erro-msg {
  color: #d32f2f;
  margin-top: 1rem;
}
h2 {
  color: #003366;
  margin-bottom: 1.5rem;
}
</style> 