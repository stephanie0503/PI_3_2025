<template>
  <el-table
    :data="products"
    style="width: 100%"
    max-height="500"
    border
    :show-summary="true"
    :summary-method="summaryMethod"
  >
    <el-table-column prop="image_url" label="Imagem" width="100">
      <template #default="{ row }">
        <el-avatar
          :src="getItemImage(row.image_url)"
          shape="square"
          size="10px"
        />
      </template>
    </el-table-column>
    <el-table-column prop="code" label="Cod. Produto" width="150">
      <template #default="{ row, $index }">
        <el-input
          v-model="row.code"
          @blur="fetchProductByCode(row, $index)"
          size="small"
        >
          <template #suffix>
            <el-icon
              class="cursor-pointer"
              @click="handleItemSearch(row, $index)"
            >
              <Search />
            </el-icon>
          </template>
        </el-input>
      </template>
    </el-table-column>

    <el-table-column prop="quantity" label="Quantidade" width="120">
      <template #default="{ row }">
        <el-input
          v-model.number="row.quantity"
          type="number"
          min="1"
          size="small"
        />
      </template>
    </el-table-column>

    <el-table-column prop="description" label="Descrição" width="280">
      <template #default="{ row }">
        <el-input v-model="row.description" size="small" />
      </template>
    </el-table-column>

    <el-table-column prop="unitPrice" label="Valor Unitário" width="150">
      <template #default="{ row }">
        <el-input
          v-model.number="row.unitPrice"
          size="small"
          :formatter="formatCurrency"
          :parser="parseCurrency"
        />
      </template>
    </el-table-column>

    <el-table-column label="SubTotal" width="150">
      <template #default="{ row }">
        {{ (row.quantity * row.unitPrice).toFixed(2) }}
      </template>
    </el-table-column>

    <el-table-column fixed="right" label="Ações" min-width="120">
      <template #default="{ $index }">
        <el-button
          type="danger"
          size="small"
          @click.prevent="removeProduct($index)"
        >
          Remover
        </el-button>
      </template>
    </el-table-column>
  </el-table>
  <el-button
    class="mt-4"
    style="width: 100%"
    @click="addProduct"
    color="#7367F0"
  >
    Adicionar Item
  </el-button>

  <!--Dialog de busca de produtos-->

  <el-dialog
    v-model="dialogVisible"
    align-center
    title="Produtos"
    width="fit-content"
    draggable
  >
    <ProductsRenderer @item-added="handleItemAdded" />
  </el-dialog>
</template>

<script setup>
import { ref, h, watch } from "vue";
import api from "@/services/api";
import {
  ElTable,
  ElTableColumn,
  ElInput,
  ElButton,
  ElAvatar,
  ElMessage,
} from "element-plus";
import { Search } from "@element-plus/icons-vue";
import ProductsRenderer from "@/components/ProductsRenderer/index.vue";



const props = defineProps({
  itemsData: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits();
const dialogVisible = ref(false);
const currentRowIndex = ref(null);

const products = ref([
  {
    code: "",
    quantity: 1,
    description: "",
    unitPrice: 0.0,
    image_url: "", // URL da imagem
  },
]);

const fetchProductByCode = async (item, index) => {
  if (!item.code) return;

  try {
    const response = await api.get(`/products/${item.code}`);
    const product = response.data;

    const existingIndex = products.value.findIndex(p => p.code === product.product_code);

    if (existingIndex !== -1) {
      products.value[existingIndex] = {
        ...products.value[existingIndex],
        description: product.description || products.value[existingIndex].description,
        unitPrice:
          parseFloat(product.suggested_retail_price * (1 + 0.79)).toFixed(2) ||
          products.value[existingIndex].unitPrice,
        image_url: product.image_url || products.value[existingIndex].image_url,
      };
    } else {
      products.value[index] = {
        ...products.value[index],
        description: product.description || products.value[index].description,
        unitPrice:
          parseFloat(product.suggested_retail_price * (1 + 0.79)).toFixed(2) ||
          products.value[index].unitPrice,
        image_url: product.image_url || products.value[index].image_url,
      };
    }
  } catch (error) {
    console.error("Erro ao buscar produto:", error);
  }
};


const addProduct = () => {
  products.value.push({
    code: "",
    quantity: 1,
    description: "",
    unitPrice: 0.0,
    image_url: "", // URL da imagem
  });
};

const removeProduct = (index) => {
  products.value.splice(index, 1);
};

const handleItemSearch = (row, index) => {
  dialogVisible.value = true;
  currentRowIndex.value = index;
};

// Quando um item e adicionado via o Component ProductsRenderer
const handleItemAdded = (item) => {
  const index = products.value.findIndex(product => product.code === item.product_code);

  if (index !== -1) {
    // Se o produto ja existir, apenas incrementa a quantidade
    products.value[index].quantity += 1;
    ElMessage({
      message: "Quantidade do produto aumentada.",
      type: "success",
    });
  } else if (currentRowIndex.value !== null) {
    // Caso nao exista, adicionamos um novo produto
    const newIndex = currentRowIndex.value;
    products.value[newIndex] = {
      ...products.value[newIndex],
      description: item.description || products.value[newIndex].description,
      unitPrice: parseFloat(item.suggested_retail_price * (1 + 0.79)).toFixed(2) || products.value[newIndex].unitPrice,
      image_url: item.image_url || products.value[newIndex].image_url,
      code: item.product_code,
      quantity: 1, // quantidade inicial como 1
    };

    ElMessage({
      message: "Produto adicionado.",
      type: "success",
    });
    dialogVisible.value = false;
  } else {
    ElMessage.error("Oops, ocorreu um erro ao adicionar esse produto.");
  }
};


const getItemImage = (itemUrl) => {
  const baseUrl = import.meta.env.VITE_VUE_APP_BACKEND_URL || "";
  return itemUrl
    ? `${baseUrl}${itemUrl}`
    : "http://www.masterscene.com/ms/wp-content/uploads/2016/04/11/default-img-300x225.png";
};

const summaryMethod = (param) => {
  const { columns, data } = param;
  const sums = [];

  columns.forEach((column, index) => {
    if (index === 0) {
      sums[index] = h("div", {}, ["Total"]);
    }
    if (index === 4) {
 
      const subtotalWithAdjustment = data.map(
        (item) => item.quantity * item.unitPrice
      );
      const sumWithAdjustment = subtotalWithAdjustment.reduce(
        (prev, curr) => prev + curr,
        0
      );
      sums.push(sumWithAdjustment.toFixed(2));

      emit("update:itemsUpdate",  products.value);

    } else {
      sums.push("");
    }
  });

  return sums;
};


// Atualiza os produtos quando itemsData muda
watch(() => props.itemsData, (newItems) => {
  if (newItems.length > 0) {
    products.value = newItems.map(item => ({
      code: item.code,
      quantity: item.quantity,
      description: item.description,
      unitPrice: item.unitPrice,
      image_url: "", // TO DO
      id: item.id
    }));
  } else {
    // Reseta para o estado padrao se nao houver dados
    products.value = [{
      code: "",
      quantity: 1,
      description: "",
      unitPrice: 0.0,
      image_url: "",
    }];
  }
}, { immediate: true });

const getProductsData = () => {
  return products.value;
};

defineExpose({ getProductsData });
</script>

<style scoped></style>
