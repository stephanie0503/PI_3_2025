<template>
  <div class="clients-page py-4 w-full">
    <!-- Filtros e ações -->
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
            class="form-input mx-2 border border-gray-300 rounded-md"
            placeholder=""
          />
        </label>
        <button
          @click="drawer = true"
          class="btn btn-primary flex items-center space-x-2 bg-[#7367F0] text-[white] rounded-[4px] py-2 px-4"
        >
          <span class="hidden sm:inline-block">Nova Empresa</span>
        </button>
      </div>
    </div>

    <!-- Tabela -->
    <template v-if="loading">
      <el-skeleton :rows="5" animated />
    </template>
    <template v-else>
      <el-table
        v-loading="loading"
        class="company-list__table"
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
          prop="fantasy_name"
          label="Empresa"
          width="200"
          :sortable="true"
          show-overflow-tooltip
        >
          <template #default="{ row }">
            <div class="flex items-center gap-6">
              <div class="avatar rounded">
                <el-avatar :size="20" src="https://empty" @error="errorHandler">
                  <img src="@/assets/images/toolbox.svg" />
                </el-avatar>
              </div>
              <div>
                <div class="font-weight-bolder text-[#6E6B7B] text-[14px]">
                  {{ row?.fantasy_name }}
                </div>
                <div class="text-[#B9B9C3] text-[11px]">
                  {{ row?.email }}
                </div>
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column
          prop="segment"
          label="Categoria"
          width="150"
          sortable
          show-overflow-tooltip
        ></el-table-column>
        <el-table-column
          label="Visualizações"
          width="150"
          sortable
        ></el-table-column>
        <el-table-column label="Receita" width="150" sortable></el-table-column>
        <el-table-column label="Vendas" width="150" sortable></el-table-column>
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
                  <el-dropdown-item> </el-dropdown-item>
                  <el-dropdown-item @click="deleteCompany(row.id)">
                    <el-icon><Delete /></el-icon> Deletar
                  </el-dropdown-item>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>

      <!-- Paginação -->
      <div class="flex justify-end bg-white py-2 px-2">
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
    </template>

    <el-drawer
      v-model="drawer"
      title="Cadastrar Cliente"
      direction="ttb"
      :before-close="handleClose"
      size="90%"
    >
      <RegisterClientForm @clientCreated="handleClientCreated" />
    </el-drawer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";
import RegisterClientForm from "@/components/RegisterClientForm/index.vue";
import { ElMessage } from "element-plus";

const entriesPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const pageSizes = [5, 10, 25, 50, 100];
const clients = ref([]);
const loading = ref(true);
const errorHandler = () => true;

const drawer = ref(false);

const fetchClients = async () => {
  try {
    const response = await api.get("\clients");
    clients.value = response.data.map((client) => ({
      id: client.id,
      fantasy_name: client.fantasy_name || "",
      email: client.email || "",
      segment: client.segment || "",
    }));
  } catch (error) {
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchClients();
});

const filteredData = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return clients.value.filter(
    (row) =>
      row.fantasy_name.toLowerCase().includes(query) ||
      (row.email ? row.email.toLowerCase().includes(query) : "")
  );
});

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * entriesPerPage.value;
  const end = start + entriesPerPage.value;
  return filteredData.value.slice(start, end);
});

const viewDetails = (id) => {
 
};

const deleteCompany = async (id) => {
  try {
    // Envia o pedido DELETE para a API
    const response = await api.delete(`/clients/${id}`);

    ElMessage.success(response.data.message);
    fetchClients();  
  } catch (error) {
    console.error(error);

    const errorMessage = error.response?.data?.error || 'Ocorreu um erro. Tente novamente mais tarde.';
    ElMessage.error(errorMessage);
  }
};


const handleSizeChange = (size) => {
  entriesPerPage.value = size;
  currentPage.value = 1; 
};

const handleCurrentChange = (page) => {
  currentPage.value = page;
};

const handleClientCreated = (newClient) => {
  try {
    const formattedClient = {
      fantasy_name: newClient.fantasy_name || "",
      email: newClient.email || "",
      segment: newClient.segment || "",
      id: newClient.id,
      phone: newClient.ccTelCli || "",
      mobile: newClient.ccCelCli || "",
    };

    clients.value.push(formattedClient);

    drawer.value = false;
  } catch (error) {}
};
</script>

<style scoped>
.clients-page :deep(.el-table__header) {
  background-color: #f3f2f7;
  border-left: 1px solid rgba(226, 214, 214, 0.267);
  border-right: 1px solid rgba(226, 214, 214, 0.267);
  width: 100% !important;
}

.clients-page :deep(.el-table__body) {
  width: 100% !important;
}
</style>
