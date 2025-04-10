<template>
  <div class="dashboard">
    <div class="sidebar">
      <Sidebar />
    </div>
    <div class="main-content h-full" :class="{ 'sidebar-expanded': isExpanded, 'sidebar-collapsed': !isExpanded }">
      <Navbar :navbarWidth="isExpanded ? 'calc(100% - 290px)' : 'calc(100% - 140px)'" />
      <content class="content h-full pb-8">
        <router-view />
      </content>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useSidebarStore } from '@/stores/sidebar';
import Sidebar from '@/components/Sidebar/index.vue';
import Navbar from '@/components/Navbar/index.vue';

// Store
const sidebarStore = useSidebarStore();
const isExpanded = computed(() => sidebarStore.isExpanded);

const sidebarWidth = computed(() => (isExpanded.value ? '280px' : '100px'));
</script>

<style scoped>
.dashboard {
  display: flex;
}

.sidebar {
  transition: width 0.3s ease;
  z-index: 10;
}

.main-content {  
  position: relative;
  flex: 1;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.3s ease;
  padding: 0 20px;
  overflow-x: hidden
}

.content {
  margin-top: 100px;
}

.main-content.sidebar-expanded {
  margin-left: 250px;
}

.main-content.sidebar-collapsed {
  margin-left: 100px;
}

@media only screen and (max-width: 639px) {
  
  .main-content.sidebar-expanded {
    margin-left: 100px;
  }

}
</style>
