import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import Login from '../views/Login/index.vue';
import Dashboard from '../views/Dashboard/index.vue';
import Home from '../views/Dashboard/Home.vue'; 
import Profile from '../views/Dashboard/Profile.vue';
import UsersList from '@/views/Users/index.vue';
import UserEdit from '@/views/User/index.vue';
import Inbox from '@/views/Inbox/index.vue';


import ProductsList from '@/views/Products/index.vue';

import CreateServiceProposal from '@/views/ServiceProposal/CreateProposal.vue'
import ListAllProposal from '@/views/ServiceProposal/index.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/verify-email/:token',
    name: 'EmailVerify',
    component: () => import('@/views/EmailVerification/index.vue')

  },

  {
    path: '/proposals/signature/:token',
    name: 'SignProposal',
    component: () => import('@/views/SignProposal/index.vue')
  },

  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
    children: [
      {
        path: '', // Página inicial da dashboard
        name: 'Home',
        component: Home
      },
      {
        path: 'users', // Caminho relativo ao Dashboard
        name: 'Users',
        component: "", // Componente vazio para renderizar rotas filhas
        children: [
          {
            path: '/user-list', // Caminho relativo a 'users'
            name: 'UserList',
            component: UsersList,
          },
          {
            path: '/user-edit:id', // Caminho relativo a 'users'
            name: 'UserEdit',
            component: UserEdit,
          },
        ]
      },
      {
        path: 'email', // Caminho relativo ao Dashboard
        name: 'Email',
        component: "", // Componente vazio para renderizar rotas filhas
        children: [
          {
            path: 'inbox', // Caminho relativo a 'email'
            name: 'Inbox',
            component: Inbox,
          }
        ],
      },
      
      {

        path: 'products',
        name: 'Products',
        componente: "",
        children: [
          {
            path: '/products-list',
            name: 'ProductsList',
            component: ProductsList
          }
        ],
      },

      {

        path: 'proposal',
        name: 'Proposal',
        componente: "",
        children: [
          {
            path: '/create-sales-proposal',
            name: 'CreateServiceProposal',
            component: CreateServiceProposal
          }, 
          {
            path: '/list-sales-proposal',
            name: 'ListAllProposal',
            component: ListAllProposal
          },
          {
            path: '/show-sales-proposal/:id',
            name: 'ShowServiceProposal',
            component: () => import('@/views/ServiceProposal/ShowProposal.vue')
          },
         
        ],
      },

      {

        path: 'clients',
        name: 'Clients',
        componenent: '',
        children: [
          {
            path: '/clients-list',
            name: 'ClientsList',
            component: () => import('@/views/Clients/index.vue'), 
          },
        ] 

      },

    ]
  },
  {
    path: '/:catchAll(.*)',
    name: "NotFound",
    component: () => import('@/views/404/index.vue'), 
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next('/login');
    } else {

      if (!authStore.user) {
        await authStore.fetchUser();
      }
      next();
    }
  } else {
    next();
  }
});

export default router;
