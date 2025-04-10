<template>
  <div class="container mx-auto px-4 products-cards w-full">
    <!-- Barra de Pesquisa -->
    <div class="mb-4">
      <el-input
        v-model="searchQuery"
        placeholder="Buscar..."
        :suffix-icon="Search"
        class="w-full"
      />
    </div>

    <!-- Cards -->
    <div class="cards grid lg:grid-cols-3 gap-4 sm:grid-cols-2">
      <div v-for="item in paginatedItems" :key="item.id" class="card">
        <div class="card-container flex flex-col p-3 gap-4">
          <div class="card__product-image flex justify-center">
            <img :src="getItemImage(item.image_url)" alt="Product Image" />
          </div>
          <div class="card__product-info">
            <div
              class="flex justify-between text-[#5E5873] text-[14px] font-semibold py-2"
            >
              <div class="flex gap-1">
                <el-rate v-model="item.rate" allow-half />
              </div>
              <span>{{ `R$: ${item.suggested_retail_price}` }}</span>
            </div>
            <div
              class="product-name text-[14px] text-[#6E6B7B] font-semibold block w-220 truncate"
            >
              <span>{{ item.name }}</span>
            </div>
            <div
              class="product-des text-[12px] text-[#6E6B7B] block w-220 truncate"
            >
              <span>{{ item.description }}</span>
            </div>
          </div>
        </div>
        <div class="text-center text-[#2A2E30] text-[14px] py-2 relative">
          <span>Detalhes</span>
          <div
            class="absolute inset-0 text-center w-full flex items-center justify-end pr-2"
          >
            <el-tooltip content="Copiar Código do Produto" placement="top">
              <el-icon @click="copyToClipboard(item.product_code)"
                ><CopyDocument
              /></el-icon>
            </el-tooltip>
          </div>
        </div>
        <div>
          <el-button
            type="primary"
            class="w-full"
            color="#7367F0"
            @click="addItem(item)"
          >
            <el-icon><ShoppingCart /></el-icon>
            Adicionar
          </el-button>
        </div>
      </div>
    </div>

    <!-- Paginação -->
    <div class="flex justify-end py-2 px-2">
      <div class="px-2 py-1 bg-[#F3F2F7] rounded-[25px]">
        <el-pagination
          background
          @current-change="handlePageChange"
          :current-page="currentPage"
          :page-size="pageSize"
          :total="filteredItems.length"
          layout="prev, pager, next"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Star, ShoppingCart, Search } from "@element-plus/icons-vue";
const emit = defineEmits(["item-added"]);

// Props
const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
});

// Reactive State
const searchQuery = ref("");
const currentPage = ref(1);
const pageSize = 9;

// Computed Properties
const filteredItems = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return props.items.filter(
    (item) =>
      item.name.toLowerCase().includes(query) ||
      item.description.toLowerCase().includes(query) ||
      item.product_code.includes(query)
  );
});

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredItems.value.slice(start, start + pageSize);
});

const handlePageChange = (page) => {
  currentPage.value = page;
};

const getItemImage = (itemUrl) => {
  const baseUrl = import.meta.env.VITE_VUE_APP_BACKEND_URL;
  if (itemUrl && itemUrl.length > 0) {
    const fullUrl = baseUrl + itemUrl;
    return fullUrl;
  } else {
    return "http://www.masterscene.com/ms/wp-content/uploads/2016/04/11/default-img-300x225.png";
  }
};

const copyToClipboard = async (productCode) => {
  console.log("codigo do produto: ", productCode)
  try {
    await navigator.clipboard.writeText(productCode);
  }catch(error) {
    console.log(error);
  }
  
};

const addItem = (item) => {
  emit("item-added", item);
};
</script>

<style>
.products-cards .el-pagination {
  --el-pagination-bg-color: #3300ff;
  --el-pagination-hover-color: #7367f0;
}

.el-pager li {
  --el-pagination-button-bg-color: none;
  --el-color-primary: #7367f0;
  --el-pagination-border-radius: 25px;
}

.products-cards .el-input__inner {
  --el-input-inner-height: 45px;
}
</style>

<style scoped>
.card .el-button {
  border-radius: 0;
  border-bottom-left-radius: 6px;
  border-bottom-right-radius: 6px;
}

.card {
  border: none;
  margin-bottom: 2rem;
  box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
  transition: all 0.3s ease-in-out;
  width: 229px;
  position: relative;
  font-family: "Montserrat", sans-serif;
}

.card:hover {
  box-shadow: 0 8px 32px 0 rgba(34, 41, 47, 0.2);
  transform: translateY(-3px);
  cursor: pointer;
}

.card-container {
  background-color: white;
  border-radius: 6px;
}

.card__product-image img {
  width: 150px;
  height: 150px;
}
</style>
