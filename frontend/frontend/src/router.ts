import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
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
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router; 