<template>
  <div class="create-proposal">
    <el-tabs>
      <el-tab-pane label="Dados Cliente">
        <ClientForm @formUpdated="handleFormUpdate" />
      </el-tab-pane>
    
      <el-tab-pane label="Produtos">
        <ProductTable
          @update:itemsUpdate="handleItemsUpdate"
          />
    
      </el-tab-pane>
      <el-tab-pane
        label="Valores"
        :disabled="disableValueTab"
        >
        <ProposalCheckout
        :items="products"
        :clientData="clientFormData"
        />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>



<script setup>
import { ref, watch } from "vue";
import ClientForm from '@/components/ProposalClientForm/index.vue'
import ProductTable from '@/components/ProposalProductTable/index.vue'
import ProposalCheckout from '@/components/ProposalCheckout/index.vue'


const disableValueTab = ref(true);
const products = ref([])
const clientFormData = ref({})


const handleItemsUpdate = (items) => {
  products.value = items;
  validateTabState(); 
};


const handleFormUpdate = (newFormData) => {
  clientFormData.value = newFormData;
  validateTabState();
};

const validateTabState = () => {

if (products.value.length > 0 && products.value[0].unitPrice > 0 && clientFormData.value.clientId > 0 && clientFormData.value.clientName != "") {
  disableValueTab.value = false;
} else {
  disableValueTab.value = true;
}
};

watch([products, clientFormData], () => {
validateTabState(); 
});

</script>

<style>

.create-proposal .el-tabs__item.is-active, 
.create-proposal .el-tabs__item:hover{
    color: var(--primary-color-indigo);
}
.create-proposal .el-tabs__active-bar {
  background-color: var(--primary-color-indigo);
}


</style>