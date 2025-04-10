import { defineStore } from 'pinia';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('jwt_token') || '',
    user: null,
    isAuthenticated: !!localStorage.getItem('jwt_token'),
  }),
  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/login', { email, password });
        const { token } = response.data;
        this.token = token;
        localStorage.setItem('jwt_token', token);
        this.isAuthenticated = true;

        const userResponse = await api.get('/me', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.user = userResponse.data;

      } catch (error) {
        throw error;
      }
    },
    logout() {
      this.token = '';
      localStorage.removeItem('jwt_token');
      this.isAuthenticated = false;
      this.user = null;
    },
    async fetchUser() {
      try {
        const response = await api.get('/me');
        this.user = response.data;
      } catch (error) {
        this.logout(); 
      }
    },
  },
});
