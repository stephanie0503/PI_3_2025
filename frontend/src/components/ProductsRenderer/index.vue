<template>
  <div class="products-list-page w-full">
    <div>
      <div class="flex justify-between items-center px-4">
        <div>
          <span>{{ items.length }} items encontrados</span>
        </div>
        <div class="flex items-center border border-indigo-600 rounded-[4px]">
          <el-radio-group v-model="viewMode">
            <el-radio-button label="grid" class="border-r border-indigo-600">
              <vue-feather
                type="grid"
                size="20px"
                stroke="#7367F0"
              ></vue-feather>
            </el-radio-button>
            <el-radio-button label="list">
              <vue-feather
                type="list"
                size="20px"
                stroke="#7367F0"
              ></vue-feather>
            </el-radio-button>
          </el-radio-group>
        </div>
      </div>
    </div>
    <div class="mt-4 h-full w-full">
      <div>
        <component
          :is="currentComponent"
          :items="items"
          @item-added="handleItemAdded"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import api from "@/services/api";
import ProductsCards from "@/components/ProductsCards/index.vue";
import ProductsListTable from "@/components/ProductsListTable/index.vue";

const items = ref([]);
const viewMode = ref("grid"); // Modo de visualização padrão
const emit = defineEmits();

const fetchItemsData = async () => {
  try {
    const response = await api.get("/products");
    items.value = response.data;
    console.log(items.value);
  } catch (error) {
    console.error("Erro ao buscar os dados dos produtos:", error);
  }
};

onMounted(() => {
  fetchItemsData();
});

// Computed property para determinar qual componente renderizar
const currentComponent = computed(() => {
  return viewMode.value === "grid" ? ProductsCards : ProductsListTable;
});

const handleItemAdded = (item) => {
  // Emitir o evento para a view pai
  emit("item-added", item);
};
</script>

<style>
.products-list-page
  .el-radio-button.is-active
  .el-radio-button__original-radio:not(:disabled)
  + .el-radio-button__inner {
  background-color: #dddaf6;
  border-color: transparent;
  box-shadow: none;
}
</style>
