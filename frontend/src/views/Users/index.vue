<template>
  <div class="users-page container py-4 w-full">
    <!-- Filtros e actions -->
    <div
      class="flex flex-col md:flex-row md:justify-between bg-white py-3 px-3 rounded-[4px]"
    >
      <!-- Seletor de Entradas -->
      <div class="flex items-center md:mb-0">
        <label class="flex items-center text-[12px] font-medium text-[#6E6B7B]">
          Exibir
          <select v-model="entriesPerPage" class="mx-2 p-2 rounded-md">
            <option v-for="size in pageSizes" :key="size" :value="size">
              {{ size }}
            </option>
          </select>
          resultados
        </label>
      </div>

      <!-- Campo de Pesquisa e Botão de Ação -->
      <div class="flex items-center space-x-2">
        <label class="flex items-center text-[12px] font-medium text-[#6E6B7B]">
          Pesquisar:
          <el-input
            style="width: 240px"
            v-model="searchQuery"
            type="search"
            class="form-input mx-2 border border-gray-300 rounded-md shadow-sm"
            placeholder=""
          />
        </label>
        <button
          @click="dialogVisible = true"
          class="btn btn-primary flex items-center space-x-2 bg-[#7367F0] text-[white] rounded-[4px] py-2 px-4"
        >
          <span class="hidden sm:inline-block">Novo Usuário</span>
        </button>
      </div>
    </div>

    <template v-if="loading">
    <el-skeleton :rows="5" animated />
  </template>
    <!-- Tabela -->
    <template v-else>
      <div>
        <el-table
          v-loading="loading"
          class="user-list__table"
          :data="paginatedData"
          :default-sort="{ prop: 'name', order: 'ascending' }"
          :header-cell-style="{ background: '#F3F2F7' }"
          :header-row-style="{
            background: '#F3F2F7',
            textTransform: 'uppercase',
            fontSize: '11px',
            color: '#6E6B7B',
            fontFamily: 'Montserrat',
          }"
          fit
          style="width: 100%"
          :stripe="false"
        >
          <template #empty>
            <div class="flex items-center justify-center h-100%">
              <el-empty />
            </div>
          </template>
          <el-table-column
            prop="avatarUrl"
            label="Usário"
            width="250"
            :sortable="true"
            :formatter="avatarFormatter"
          >
            <template #default="{ row }">
              <div class="flex items-center space-x-2">
                <img
                  v-if="row.profilepicture"
                  :src="avatarFormatter(row.profilepicture)"
                  alt="avatar"
                  class="rounded-full w-8 h-8"
                />
                <div
                  v-else
                  class="bg-gray-400 text-white rounded-full flex items-center justify-center w-8 h-8"
                >
                  {{ getInitials(row.name) }}
                </div>
                <span class="text-[#7367F0] font-montserrat font-medium">{{
                  row.name
                }}</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="email"
            label="Email"
            width="300"
            sortable
          ></el-table-column>
          <el-table-column
            prop="role"
            label="Role"
            width="150"
            sortable
          ></el-table-column>
          <el-table-column
            prop="status"
            label="Status"
            width="150"
            sortable
            :formatter="statusFormatter"
          >
            <template #default="{ row }">
              <span
                :class="statusClass(row.status)"
                class="px-2 py-0.5 text-[12px] rounded-[10px] font-bold"
              >
                {{  translateStatus(row.status) }}
              </span>
            </template>
          </el-table-column>
          <el-table-column label="Ações" width="200">
            <template #default="{ row }">
              <el-dropdown trigger="click">
                <span class="el-dropdown-link rotate-90">
                  <el-icon><More /></el-icon>
                </span>
                <template #dropdown>
                  <el-dropdown-menu>
                    <el-dropdown-item @click="viewDetails(row.id)">
                      <el-icon><Document /></el-icon> Detalhes
                    </el-dropdown-item>
                    <el-dropdown-item>
                      <router-link
                        :to="{ name: 'UserEdit', params: { id: row.id } }"
                        ><el-icon><Edit /></el-icon> Editar</router-link
                      >
                    </el-dropdown-item>
                    <el-dropdown-item @click="deleteUser(row.id)">
                      <el-icon><Delete /></el-icon> Deletar
                    </el-dropdown-item>
                  </el-dropdown-menu>
                </template>
              </el-dropdown>
            </template>
          </el-table-column>
        </el-table>
      </div>
       <!-- Paginação -->
    <div class="flex justify-end bg-white py-2 px-2">
      <div class="px-2 py-1 user-pagination bg-[#F3F2F7] rounded-[25px]">
        <el-pagination
          background
          v-model:current-page="currentPage"
          v-model:page-size="entriesPerPage"
          :page-sizes="pageSizes"
          layout="prev, pager, next"
          :total="filteredData.length"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          pager-count="4"
        />
      </div>
    </div>
    </template>

   

    <!-- Modal para registrar um user -->
    <div>
      <UserModal v-model="dialogVisible" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";
import UserModal from "@/components/UserRegisterModal/index.vue";

interface User {
  id: number;
  avatarUrl?: string;
  name: string;
  email: string;
  role: string;
  status: string;
}

const entriesPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const pageSizes = [5, 10, 25, 50, 100];
const isModalVisible = ref(false);
const users = ref<User[]>([]);
const loading = ref(true);

const fetchUsers = async () => {
  try {
    const response = await api.get("/users");
    users.value = response.data;
    loading.value = false;
  } catch (error) {
    console.error("Erro ao buscar usuários:", error);
  }
};

onMounted(() => {
  fetchUsers();
});

const filteredData = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return users.value.filter(
    (row) =>
      row.name.toLowerCase().includes(query) ||
      row.email.toLowerCase().includes(query)
  );
});

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * entriesPerPage.value;
  const end = start + entriesPerPage.value;
  return filteredData.value.slice(start, end);
});

const avatarFormatter = (avatarUrl) => {
  const baseUrl = import.meta.env.VITE_VUE_APP_BACKEND_URL;
  const fullUrl = baseUrl + avatarUrl;
  return fullUrl;
};

const statusFormatter = (row: User) => {
  return row.status;
};

const getInitials = (name: string) => {
  const names = name.split(" ");
  const initials = names.map((n) => n[0]).join("");
  return initials.toUpperCase();
};

const viewDetails = (id: number) => {
  isModalVisible.value = true;
};

const deleteUser = (id: number) => {
  console.log("Delete user with id:", id);
  // Adicione lógica para deletar o usuário aqui
};

const statusClass = (status: string) => {
  return {
    "bg-[#FFF3E8] text-yellow-600": status === "pending",
    "bg-[#E5F8ED] text-[#35CA77]": status === "active",
  };
};

const translateStatus = (status : string) => {

  switch(status) {

    case "pending":
      return "Aguardando";
    case "active":
      return "Ativo"; 
    case "inactive":
      return "Inativo"; 
  }
}

const handleSizeChange = (size: number) => {
  entriesPerPage.value = size;
  currentPage.value = 1; // Reset page to 1 when entries per page change
};

const handleCurrentChange = (page: number) => {
  currentPage.value = page;
};

const dialogVisible = ref(false);
</script>

<style>
.user-list__table .el-table__header-wrapper {
  background-color: #f3f2f7;
  border-left: 1px solid rgba(226, 214, 214, 0.267);
  border-right: 1px solid rgba(226, 214, 214, 0.267);
  width: 100%;
  display: flex;
}

.users-page .el-table__body {
  width: 100% !important;
}

.users-page .el-input {
  --el-input-focus-border-color: var(--primary-color-indigo);
  --el-input-hover-border-color: var(--primary-color-indigo);
}

.users-page select:focus {
  outline: 0;
  background-color: transparent;
  box-shadow: 0 0 0 1px var(--primary-color-indigo) inset;
}

.user-list__table {
  display: flex;
  flex-direction: column;
}

.user-pagination .el-pagination {
  --el-pagination-bg-color: #3300ff;
  --el-pagination-hover-color: #7367f0;
}

.user-pagination .el-pager li {
  --el-pagination-button-bg-color: none;
  --el-color-primary: #7367f0;
  --el-pagination-border-radius: 25px;
}

.users-page .el-dropdown-menu__item:not(.is-disabled):focus,
.el-dropdown-menu__item:not(.is-disabled):hover {
  background-color: #eeecfe !important;
  color: var(--primary-color-indigo) !important;
}

.users-page .el-table__header {
  width: 100% !important;
}
</style>
