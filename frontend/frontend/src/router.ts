import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('./views/LoginView.vue'),
    meta: { public: true },
  },
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