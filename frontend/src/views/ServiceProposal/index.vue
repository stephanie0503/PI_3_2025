<template>
  <div class="proposals-page py-4 w-full">
    <!-- Filtros e actions -->
    <div
      class="flex flex-col md:flex-row md:justify-between bg-white py-3 px-3 rounded-[4px]"
    >
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
      </div>
    </div>

    <template v-if="loading">
      <el-skeleton :rows="5" animated />
    </template>

    <template v-else>
      <el-table
        v-loading="loading"
        class="proposal-list__table"
        :data="paginatedData"
        table-layout="fixed"
        :header-cell-style="{ background: '#F3F2F7' }"
        :header-row-style="{
          background: '#F3F2F7',
          textTransform: 'uppercase',
          fontSize: '11px',
          color: '#6E6B7B',
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

        <!-- Coluna ID -->
        <el-table-column
          prop="id"
          label="#"
          width="100"
          sortable
        ></el-table-column>
        <el-table-column
          prop="status"
          label="Status"
          width="100"
          sortable
        ></el-table-column>
        <el-table-column
          prop="vendorName"
          label="Vendedor"
          width="150"
          show-overflow-tooltip
          sortable
        ></el-table-column>
        <el-table-column
          prop="clientName"
          label="Cliente"
          width="150"
          show-overflow-tooltip
          sortable
        >
          <template #default="{ row }">
            <div class="flex flex-col">
              <div class="capitalize">{{ row.clientFantasyName }}</div>
              <div class="text-[#B9B9C3] text-[11px]">{{ row.clientName }}</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column
          prop="total"
          label="Valor"
          width="100"
          :formatter="formattedTotal"
          sortable
        ></el-table-column>
        <el-table-column
          prop="issuedDate"
          label="Data de emissão"
          :formatter="formatDate"
          width="130"
          sortable
        ></el-table-column>
        <el-table-column
          prop="balance"
          label="Balanço"
          width="100"
          sortable
        ></el-table-column>
        <el-table-column label="Ações" width="100">
          <template #default="{ row }">
            <div class="flex gap-[15px] items-center">
              <el-tooltip
                class="box-item"
                effect="dark"
                content="Enviar"
                placement="top"
              >
                <el-icon class="cursor-pointer"><Promotion /></el-icon>
              </el-tooltip>
              <el-tooltip
                class="box-item"
                effect="dark"
                content="Visualizar"
                placement="top"
              >
                <el-icon class="cursor-pointer"><View /></el-icon>
              </el-tooltip>

              <el-dropdown trigger="click">
                <span class="el-dropdown-link rotate-90">
                  <el-icon><More /></el-icon>
                </span>
                <template #dropdown>
                  <el-dropdown-menu>
                    <el-dropdown-item>
                      <el-icon><Download /></el-icon> Download
                    </el-dropdown-item>
                    <el-dropdown-item @click="editItem(row.id)">
                      <el-icon><Edit /></el-icon> Editar
                    </el-dropdown-item>
                    <el-dropdown-item>
                      <el-icon><Delete /></el-icon> Deletar
                    </el-dropdown-item>
                  </el-dropdown-menu>
                </template>
              </el-dropdown>
            </div>
          </template>
        </el-table-column>
      </el-table>

      <!-- Pagination -->
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
        />
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const entriesPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const pageSizes = [5, 10, 25, 50, 100];
const proposals = ref([]);
const loading = ref(true);
const router = useRouter();

const fetchProposals = async () => {
  try {
    const response = await api.get("/proposals");
    proposals.value = response.data.map((proposal) => ({
      id: "#" + proposal.id,
      status: "Paid",
      clientFantasyName: proposal.client?.fantasy_name || "",
      clientName: proposal.client?.name || "",
      vendorName: proposal.vendor?.name || proposal.vendor_name,
      total: proposal.total_value || "",
      issuedDate: proposal.created_at || "",
    }));
    loading.value = false;
  } catch (error) {
    console.error("Erro ao buscar propostas:", error);
  }
};

const formattedTotal = (row, column) => {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(row.total || 0);
};

const formatDate = (row, column) => {
  if (!row.issuedDate) return "";

  const options = { year: "numeric", month: "2-digit", day: "2-digit" };
  const date = new Date(row.issuedDate);
  return date.toLocaleDateString("pt-BR", options);
};

onMounted(() => {
  fetchProposals();
});

const filteredData = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return proposals.value.filter(
    (proposal) =>
      proposal.id.toString().includes(query) ||
      (proposal.clientName &&
        proposal.clientName.toLowerCase().includes(query)) ||
      (proposal.vendorName && proposal.vendorName.toLowerCase().includes(query))
  );
});

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * entriesPerPage.value;
  const end = start + entriesPerPage.value;
  return filteredData.value.slice(start, end);
});

const handleSizeChange = (size) => {
  entriesPerPage.value = size;
  currentPage.value = 1;
};

const handleCurrentChange = (page) => {
  currentPage.value = page;
};

const editItem = (id) => {
  const cleanedId = id.replace(/\D/g, '');
  router.push(`/show-sales-proposal/${cleanedId}`);
};


</script>
