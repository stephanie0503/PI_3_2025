import { defineStore } from 'pinia';

export const useSidebarStore = defineStore('sidebar', {
  state: () => ({
    isExpanded: false, // Estado inicial da sidebar
  }),
  actions: {
    toggleSidebar() {
      this.isExpanded = !this.isExpanded;
    },
  },
});
