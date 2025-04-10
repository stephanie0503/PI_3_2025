<template>
  <nav
    :style="{ width: navbarWidth }"
    class="bg-white p-2.5 flex items-center justify-between rounded-[6.8px] navbar"
  >
    <div class="flex space-x-4">
      <el-icon class="cursor-pointer" @click="toggleSidebar"><Fold /></el-icon>
      <button @click="openEmail" class="text-[#5E5873] hover:text-[#7367F0]">
        <el-icon size="22px"><Message /></el-icon>
      </button>
      <button @click="openChat" class="text-[#5E5873] hover:text-[#7367F0]">
        <el-icon size="22px"><ChatSquare /></el-icon>
      </button>
      <button @click="openCalendar" class="text-[#5E5873] hover:text-[#7367F0]">
        <el-icon size="22px"><Calendar /></el-icon>
      </button>
    </div>

    <div class="flex items-center space-x-4">
      <div>
        <el-badge :value="6" class="item" color="#EA5455" :offset="[0, -2]" >
          <el-icon class="cursor-pointer" color="#6e6b7b" :size="20"><Bell /></el-icon>
        </el-badge>
      </div>

      <el-dropdown @command="handleCommand" trigger="click">
        <span class="el-dropdown-link">
          <div class="flex items-center gap-x-2">
            <div class="flex flex-col gap-y-1">
              <span class="font-montserrat font-semibold">{{ userName }}</span>
              <small class="self-end"> {{ userRole }}</small>
            </div>
            <el-avatar :src="getUserProfilePicture()"></el-avatar>
          </div>
        </span>
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item
              ><el-icon size="18px"><User /></el-icon>Perfil</el-dropdown-item
            >
            <el-dropdown-item
              ><el-icon size="18px"><Message /></el-icon>Inbox</el-dropdown-item
            >
            <el-dropdown-item divided
              ><el-icon size="18px"><Setting /></el-icon
              >Configurações</el-dropdown-item
            >
            <el-dropdown-item command="logout"
              ><el-icon size="18px"><SwitchButton /></el-icon
              >Sair</el-dropdown-item
            >
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useSidebarStore } from "@/stores/sidebar";
import { ref } from "vue";
import { useAuthStore } from "@/stores/authStore";

defineProps(["navbarWidth"]);

const sidebarStore = useSidebarStore();
const router = useRouter();
const authStore = useAuthStore();

const userName = authStore.user?.name || "";

//@@TO DO
const userRole = authStore.user?.role || "Admin";

const getUserProfilePicture = () => {
  const imagePath = authStore.user?.profilepicture;
  if (imagePath) {
    const pathUrl = import.meta.env.VITE_VUE_APP_BACKEND_URL + imagePath;
    return pathUrl;
  } else {
    return "User";
  }
};

const handleCommand = (command) => {
  switch (command) {
    case "logout":
      authStore.logout();
      router.push("/login");
      break;
  }
};

function openEmail() {
  router.push({ name: "Inbox" });
}

function openChat() {}

function openCalendar() {}

function showNotifications() {}

function toggleSidebar() {
  sidebarStore.toggleSidebar();
}
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 20px;
  z-index: 5;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  transition: width 0.3s;
}

@media only screen and (max-width: 639px) {
  .navbar {
    width: calc(100% - 140px) !important;
  }
}
</style>
