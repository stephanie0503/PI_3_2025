<template>
  <el-row class="tac">
    <el-col :span="10">
      <el-menu class="el-menu-vertical" @open="handleOpen" @close="handleClose">
        <div class="flex justify-center p-5">
          <el-button color="#7367F0">Escrever</el-button>
        </div>

        <el-menu-item>
          <el-icon size="18px">
            <Message />
          </el-icon>
          <span>Inbox</span>
          <span
            class="absolute right-5 rounded-full bg-[#EEEDFD] text-[#6E6B7B] text-xs px-2 py-1"
          >
            {{ emailStore.inboxCount }}
          </span>
        </el-menu-item>

        <el-menu-item>
          <el-icon size="18px">
            <Position />
          </el-icon>
          <span>Enviadas</span>
        </el-menu-item>

        <el-menu-item>
          <el-icon size="18px">
            <EditPen />
          </el-icon>
          <span>Rascunho</span>
          <span
            class="absolute right-5 rounded-full bg-[#FFF3E8] text-[#FF9F43] text-xs px-2 py-1"
          >
            {{ emailStore.draftCount }}
          </span>
        </el-menu-item>

        <el-menu-item>
          <el-icon size="18px">
            <Warning />
          </el-icon>
          <span>Spam</span>
          <span
            class="absolute right-5 rounded-full bg-red-400 text-white text-xs px-1.5 py-0.5"
          >
            {{ emailStore.spamCount }}
          </span>
        </el-menu-item>

        <el-menu-item>
          <el-icon size="18px">
            <Delete />
          </el-icon>
          <span>Lixeira</span>
        </el-menu-item>

        <span
          class="pl-[23px] text-[#B9B9C3] font-montserrat text-[12px] uppercase"
        >
          Marcadores
        </span>

        <el-menu-item class="flex gap-x-4">
          <span class="rounded-[25px] bg-[#28C76F] w-[10px] h-[10px]"></span>
          <span>Pessoal</span>
        </el-menu-item>

        <el-menu-item class="flex gap-x-4">
          <span class="rounded-[25px] bg-[#7367F0] w-[10px] h-[10px]"></span>
          <span>Empresa</span>
        </el-menu-item>

        <el-menu-item class="flex gap-x-4">
          <span class="rounded-[25px] bg-[#FF9F43] w-[10px] h-[10px]"></span>
          <span>Urgente</span>
        </el-menu-item>

        <el-menu-item class="flex gap-x-4">
          <span class="rounded-[25px] bg-[#EA5455] w-[10px] h-[10px]"></span>
          <span>Privado</span>
        </el-menu-item>
      </el-menu>
    </el-col>
  </el-row>
</template>

<script lang="ts" setup>
import { useEmailStore } from '@/stores/emailStore'; // ajuste o caminho conforme necessário
import { Document, Menu as IconMenu, Location, Setting } from "@element-plus/icons-vue";

const emailStore = useEmailStore();

const handleOpen = (key: string, keyPath: string[]) => {
  console.log(key, keyPath);
};

const handleClose = (key: string, keyPath: string[]) => {
  console.log(key, keyPath);
};
</script>

<style scoped>
.el-menu-vertical {
  width: 260px;
  height: inherit;
  background-color: #fff;
  border-top-left-radius: 0.428rem;
  border-bottom-left-radius: 0.428rem;
  height: 70vh;
  overflow-y: scroll;
}

.el-menu-vertical::-webkit-scrollbar {
  display: none;
}

.el-menu-vertical .el-menu-item {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
}

.el-menu-vertical .el-menu-item:hover {
  color: var(--primary-color-indigo);
  background: none;
  transition: all 0.3s ease;
}

.el-menu-vertical .el-button {
  width: 100%;
  padding: 18px 0;
  font-family: "Montserrat", sans-serif;
  font-weight: 600;
}
</style>
