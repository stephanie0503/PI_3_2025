<template>
  <div
    :class="['sidebar', !isExpanded ? 'sidebar-collapsed' : 'sidebar-expanded']"
  >
    <div class="sidebar__logo px-4 py-1 flex items-center gap-[10px] relative">
      <img src="@/assets/images/logo-svg.svg" alt="Logo" />
      <span class="text-[#7367F0] font-montserrat font-bold">Connecta</span>
      <div v-if="isExpanded" class="absolute right-0 md:hidden cursor-pointer">
        <el-icon @click="handleSidebarState()"><Close /></el-icon>
      </div>
    </div>

    <el-menu
      default-active="2"
      router="true"
      class="el-menu-vertical-demo"
      :collapse="!isExpanded"
      @open="handleOpen"
      @close="handleClose"
    >
      <el-menu-item index="/">
        <el-icon>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="feather feather-home"
          >
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
        </el-icon>
        <span>Dashboard</span>
      </el-menu-item>

      <!-- Gestão Comercial -->
      <span class="category-title">Gestão Comercial</span>

      <el-sub-menu index="8">
        <template #title>
          <el-icon><document /></el-icon>
          <span>Clientes</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Clientes</span></template>
          <el-menu-item index="/clients-list">Listar Clientes</el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>

      <el-sub-menu index="7">
        <template #title>
          <el-icon><Goods /></el-icon>
          <span>Produtos</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Produtos</span></template>
          <el-menu-item index="/products-list">Listar Produtos</el-menu-item>
          <el-menu-item index="#">Cadastrar Produto</el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>

      <el-sub-menu index="6">
        <template #title>
          <el-icon><Collection /></el-icon>
          <span>Propostas</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Proposta de Venda</span></template>
          <el-menu-item index="/list-sales-proposal">Lista</el-menu-item>
          <el-menu-item index="/create-sales-proposal">Criar</el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>

     
      <!-- Financeiro -->

      <span class="category-title">Financeiro</span>
      <el-sub-menu index="2">
        <template #title>
          <el-icon><Money /></el-icon>
          <span>Contas</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Contas a Pagar</span></template>
          <el-menu-item index="#">Listar Contas</el-menu-item>
          <el-menu-item index="#">Cadastrar Conta</el-menu-item>
        </el-menu-item-group>
        <el-menu-item-group title="Contas a Receber">
          <el-menu-item index="#">Listar Contas</el-menu-item>
          <el-menu-item index="#">Cadastrar Conta</el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>

      <!-- Relatórios -->

      <span class="category-title">Relatórios</span>
      <el-menu-item index="#">
        <el-icon><DataAnalysis /></el-icon>
        <template #title>Relatórios de Vendas</template>
      </el-menu-item>
      <el-menu-item index="#">
        <el-icon><DataLine /></el-icon>
        <template #title>Relatórios Financeiros</template>
      </el-menu-item>

      <!-- Configurações -->

      <span class="category-title">Configurações</span>

      <el-sub-menu index="5">
        <template #title>
          <el-icon><User /></el-icon>
          <span>Usuários</span>
        </template>
        <el-menu-item-group>
          <el-menu-item index="/user-list">Listar Usuários</el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
    </el-menu>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useSidebarStore } from "@/stores/sidebar";
import {
  Document,
  Menu as IconMenu,
  Location,
  Setting,
} from "@element-plus/icons-vue";

const sidebarStore = useSidebarStore();

const isExpanded = computed(() => sidebarStore.isExpanded);

const handleOpen = (key, keyPath) => {
  console.log(key, keyPath);
};

const handleClose = (key, keyPath) => {
  console.log(key, keyPath);
};

const handleSidebarState = () => {
  sidebarStore.toggleSidebar();
};
</script>

<style>


.sidebar .el-menu {
  border-right: none;
}

.sidebar .el-menu-item.is-active,
.sidebar .el-sub-menu.is-active {
    color: var(--primary-color-indigo);
    --el-menu-active-color: var(--primary-color-indigo); 
}
.sidebar .el-menu--collapse {
    width: 100%;
}


</style>

<style scoped>
.sidebar {
  position: fixed;
  height: 100vh;
  background-color: white;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  overflow: hidden;
  width: 240px;
  transition: width 0.3s;
  z-index: 99;
}

.sidebar-collapsed {
  width: 80px;
}

.sidebar-expanded {
  width: 240px;
}

.el-menu-vertical-demo {
  height: 100vh;
  overflow-y: auto;
  padding-bottom: 4rem;
  scrollbar-width: thin;
  scrollbar-color: transparent transparent; 
}

.el-menu-vertical-demo::-webkit-scrollbar {
  width: 0px;
  background: transparent;
}

.el-menu-vertical-demo::-webkit-scrollbar-thumb {
  background-color: transparent;
}

.category-title {
  padding-left: 24px;
  color: #a6a4b0;
  font-family: "Montserrat", sans-serif;
  text-transform: uppercase;
  font-size: 12px;
  display: block;
  opacity: 0;
  transform: translateX(-20px);
  transition: opacity 5s ease-in-out, transform 5s ease-in-out;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-expanded .category-title {
  opacity: 1;
  transform: translateX(0);
}

.sidebar-collapsed .category-title {
  display: none;
}

.sidebar .sidebar__logo img {
  height: 40px;
  width: 40px;
  transform: rotate(90deg);
}

.sidebar-collapsed .sidebar__logo span {
  display: none;
}
</style>
