<template>
  <div class="create-proposal">
    <el-tabs>
      <el-tab-pane label="Dados Cliente">
        <ClientForm
          @formUpdated="handleFormUpdate"
          :data="formData"
          :isEditing="true"
        />
      </el-tab-pane>

      <el-tab-pane label="Produtos">
        <ProductTable
          @update:itemsUpdate="handleItemsUpdate"
          :itemsData="itemsData"
        />
      </el-tab-pane>
      <el-tab-pane label="Valores" :disabled="disableValueTab">
        <ProposalCheckout
          :items="products"
          :clientData="clientFormData"
          :checkoutFormData="checkoutFormData"
          :isEditing="true"
        />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { useRoute } from "vue-router";
import ClientForm from "@/components/ProposalClientForm/index.vue";
import ProductTable from "@/components/ProposalProductTable/index.vue";
import ProposalCheckout from "@/components/ProposalCheckout/index.vue";
import api from "@/services/api";

const route = useRoute();
const id = ref(route.params.id);

const disableValueTab = ref(true);
const products = ref([]);
const clientFormData = ref({});

const itemsData = ref([]);
const formData = ref({});
const checkoutFormData = ref({});

const fetchData = async () => {
  try {
    const response = await api.get(`/proposals/${id.value}`);

    itemsData.value = response.data.data.items.map((item) => ({
      id: item.id,
      code: item.product_code,
      quantity: item.quantity,
      description: item.description,
      unitPrice: item.unit_price,
    }));

    formData.value = {
      proposalNumber: response.data.data.id,
      consultantName: response.data.data?.vendor?.name,
      consultantEmail: response.data.data?.vendor?.email,
      clientId: response.data.data.client.id,
      clientName: response.data.data.client.name,
      clientFantasyName: response.data.data.client.fantasy_name,
      address: response.data.data.client.address,
      addressNumber: response.data.data.client.number,
      neighborhood: response.data.data.client.neighborhood,
      city: response.data.data.client.city,
      state: response.data.data.client.state,
      zipCode: response.data.data.client.zip_code,
      clientEmail: response.data.data.client.email.trim(),
      phone: response.data.data.client.ccTelCli,
      mobile: response.data.data.client.ccCelCli,
      cnpj: response.data.data.client.cnpj,
      insEst: response.data.data.client.ins_est,
      contact: response.data.data.client.contact_name,
      creationDate: new Date(response.data.data.created_at),
    };

    checkoutFormData.value = {
      consultantCommission: response.data.data?.consultant_percentage,
      designerCommission: response.data.data?.designer_percentage,
      technicalCommission: response.data.data?.technical_percentage,
      thirdPartyCommission: response.data.data?.third_party_percentage,
      taxes: response.data.data?.taxes_percentage,
      discounts: response.data.data?.discount_percentage,
      paymentMethods: [response.data.data?.payment_method] || [],
      total: response.data.data?.total_value,
      approvalStatus: response.data.data?.approval_status,
    };
  } catch (e) {
    console.error("Erro ao buscar dados:", e);
  }
};

const handleItemsUpdate = (items) => {
  products.value = items;
  validateTabState();
};

const handleFormUpdate = (newFormData) => {
  clientFormData.value = newFormData;
  validateTabState();
};

const validateTabState = () => {
  if (
    products.value.length > 0 &&
    products.value[0].unitPrice > 0 &&
    clientFormData.value.clientId > 0 &&
    clientFormData.value.clientName !== ""
  ) {
    disableValueTab.value = false;
  } else {
    disableValueTab.value = true;
  }
};

onMounted(() => {
  fetchData();
});

watch([products, clientFormData], () => {
  validateTabState();
});

watch(
  () => route.params.id,
  async (newId) => {
    id.value = newId;
    await fetchData();
  }
);
</script>

<style>
.create-proposal .el-tabs__item.is-active,
.create-proposal .el-tabs__item:hover {
  color: var(--primary-color-indigo);
}
.create-proposal .el-tabs__active-bar {
  background-color: var(--primary-color-indigo);
}
</style>
