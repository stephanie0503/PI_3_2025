<template>
  <div class="products-list-table container py-4 w-[100%]">
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
            v-model="searchQuery"
            type="search"
            class="form-input mx-2 border border-gray-300 rounded-md"
          />
        </label>
      </div>
    </div>

    <template v-if="loading">
      <el-skeleton :rows="5" animated />
    </template>
    <template v-else>
      <el-table :data="paginatedData" fit style="width: 100%">
        <el-table-column prop="image_url" label="Imagem" width="100">
          <template #default="{ row }">
            <img
              :src="getItemImage(row.image_url)"
              alt="Produto"
              class="w-16 h-16 object-cover"
            />
          </template>
        </el-table-column>
        <el-table-column label="Cod. Produto" width="150">
          <template v-slot="scope">
            {{ scope.row.product_code }}
            <el-tooltip content="Copiar Código do Produto" placement="top">
              <el-icon
                class="cursor-pointer"
                @click="copyToClipboard(scope.row.product_code)"
                ><CopyDocument
              /></el-icon>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="Nome"
          width="200"
          show-overflow-tooltip
        ></el-table-column>
        <el-table-column
          prop="description"
          label="Descrição"
          width="250"
          show-overflow-tooltip
        ></el-table-column>
        <el-table-column
          prop="suggested_retail_price"
          label="Valor"
          width="100"
          :sortable="true"
        ></el-table-column>
        <el-table-column label="Ações" width="150">
          <template #default="{ row }">
            <el-dropdown trigger="click">
              <span class="el-dropdown-link rotate-90">
                <el-icon><More /></el-icon>
              </span>
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item @click="editItem(row.id)">
                    <el-icon><Document /></el-icon> Detalhes
                  </el-dropdown-item>
                  <el-dropdown-item>
                    <el-icon><Edit /></el-icon> Editar
                  </el-dropdown-item>
                  <el-dropdown-item @click="deleteItem(row.id)">
                    <el-icon><Delete /></el-icon> Deletar
                  </el-dropdown-item>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="flex justify-end bg-[#F3F2F7] rounded-[25px] mt-4">
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
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

// Props
const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
});

// Reactive State
const entriesPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");
const pageSizes = [5, 10, 25, 50, 100];
const loading = ref(false);

// Computed Properties
const filteredData = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return props.items.filter(
    (item) =>
      item.name.toLowerCase().includes(query) ||
      item.description.toLowerCase().includes(query) ||
      item.product_code.includes(query)
  );
});

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * entriesPerPage.value;
  return filteredData.value.slice(start, start + entriesPerPage.value);
});

// Pagination Handlers
const handleSizeChange = (size) => {
  entriesPerPage.value = size;
  currentPage.value = 1; // Reset page to 1 when entries per page change
};

const handleCurrentChange = (page) => {
  currentPage.value = page;
};

const editItem = (id) => {
  console.log("Edit item with id:", id);
};

const deleteItem = (id) => {
  console.log("Delete item with id:", id);
};

const getItemImage = (itemUrl) => {
  const baseUrl = import.meta.env.VITE_VUE_APP_BACKEND_URL;
  return itemUrl && itemUrl.length > 0
    ? `${baseUrl}${itemUrl}`
    : "http://www.masterscene.com/ms/wp-content/uploads/2016/04/11/default-img-300x225.png";
};

const copyToClipboard = (productCode) => {
  navigator.clipboard.writeText(productCode);
};
</script>

<style>
.products-list-table .el-table,
.products-list-table .el-table__header-wrapper {
  width: 100% !important;
}
</style>
