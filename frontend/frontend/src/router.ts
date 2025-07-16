import { createRouter, createWebHistory } from 'vue-router';
import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('./views/LoginView.vue'),
    meta: { public: true },
  },
  {
    path: '/',
    name: 'DashboardHome',
    component: () => import('./views/DashboardView.vue'),
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('./views/DashboardView.vue'),
  },
  {
    path: '/atletas',
    name: 'Atletas',
    component: () => import('./views/AtletasView.vue'),
  },
  {
    path: '/usuarios',
    name: 'Usuários',
    component: () => import('./views/UsuariosView.vue'),
  },
  {
    path: '/sono',
    name: 'Sono',
    component: () => import('./views/SonoView.vue'),
  },
  {
    path: '/pse',
    name: 'PSE',
    component: () => import('./views/PSEView.vue'),
  },
  {
    path: '/relatorios',
    name: 'Relatórios',
    component: () => import('./views/RelatoriosView.vue'),
  },
  {
    path: '/treinadores',
    name: 'Treinadores',
    component: () => import('./views/TreinadoresView.vue'),
  },
  {
    path: '/jogos',
    name: 'Jogos',
    component: () => import('./views/JogosView.vue'),
  },
  {
    path: '/minutagem',
    name: 'Minutagem',
    component: () => import('./views/MinutagemView.vue'),
  },
  {
    path: '/pfe',
    name: 'PFE',
    component: () => import('./views/PFEView.vue'),
  },
  {
    path: '/percepcao-fadiga',
    name: 'Percepção de Fadiga',
    component: () => import('./views/PercepcaoFadigaView.vue'),
  },
  {
    path: '/carga-semanal',
    name: 'Carga Semanal',
    component: () => import('./views/CargaSemanalView.vue'),
  },
  {
    path: '/relatorio-presenca',
    name: 'Relatório de Presença',
    component: () => import('./views/RelatorioPresencaView.vue'),
  },
  {
    path: '/percepcao-grupo',
    name: 'Percepção por Grupo',
    component: () => import('./views/PercepcaoGrupoView.vue'),
  },
  {
    path: '/diferenca-semanas',
    name: 'Diferença entre Semanas',
    component: () => import('./views/DiferencaSemanasView.vue'),
  },
  {
    path: '/pre-treino',
    name: 'Pré-Treino',
    component: () => import('./views/PreTreinoView.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Guard de autenticação
router.beforeEach((to, from, next) => {
  const isPublic = to.meta.public;
  const isLoggedIn = !!localStorage.getItem('token');
  if (!isPublic && !isLoggedIn) {
    next('/login');
  } else if (isPublic && isLoggedIn && to.path === '/login') {
    next('/');
  } else {
    next();
  }
});

export default router; 