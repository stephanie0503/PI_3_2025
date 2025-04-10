<template>
  <div class="flex gap-4 bg-[#EEEEEE] p-4 rounded-lg proposal-checkout">
    <div class="p-6 w-[40%] bg-white shadow-lg rounded-lg options">
      <h2 class="text-[#5E5873] text-[17px] font-montserrat font-medium mb-4">
        Taxas
      </h2>
      <el-form
        :model="form"
        ref="formRef"
        label-width="200px"
        label-position="top"
      >
        <div class="grid grid-cols-2">
          <el-form-item label="Comissão do Consultor">
            <el-input-number
              v-model="form.consultantCommission"
              :min="0"
              :max="10"
              placeholder="Insira a comissão do consultor"
            />
          </el-form-item>
          <el-form-item label="Comissão Projetista">
            <el-input-number
              v-model="form.designerCommission"
              :min="0"
              :max="3"
              placeholder="Insira a comissão do projetista"
            />
          </el-form-item>
          <el-form-item label="Comissão Técnico">
            <el-input-number
              v-model="form.technicalCommission"
              :min="0"
              :max="3"
              placeholder="Insira a comissão do técnico"
            />
          </el-form-item>
          <el-form-item label="Comissão Terceiros">
            <el-input-number
              v-model="form.thirdPartyCommission"
              :min="0"
              :max="3"
              placeholder="Insira a comissão de terceiros"
            />
          </el-form-item>
          <el-form-item label="Descontos">
            <el-input-number
              v-model="form.discounts"
              :min="0"
              placeholder="Insira o desconto"
            />
          </el-form-item>
          <el-form-item label="Subtotal">
            <el-input :value="totalValue" disabled />
          </el-form-item>
        </div>
        <el-divider />
        <div>
          <h2
            class="text-[#5E5873] text-[17px] font-montserrat font-medium mb-4"
          >
            Opções de Pagamento
          </h2>
          <el-form-item
            v-for="(paymentMethod, index) in form.paymentMethods"
            :key="index"
            :label="'Forma de Pagamento'"
          >
            <el-input
              v-model="form.paymentMethods[index]"
              placeholder="Digite a forma de pagamento"
            />
          </el-form-item>
          <div v-if="form.paymentMethods.length <= 0">
            <el-button @click="addPaymentMethod" :icon="Plus"
              >Adicionar</el-button
            >
          </div>
        </div>
      </el-form>
    </div>

    <!--Details-->
    <div class="flex flex-col gap-6 sticky">
      <div class="bg-white shadow-lg rounded-[6px] pt-5 px-6 gap-2 relative">
        <h2 class="text-[#5E5873] text-[17px] font-montserrat font-medium mb-4">
          Detalhes
        </h2>
        <div class="mb-[80px] proposal-sumary flex flex-col w-full gap-2">
          <div>
            <span>Produtos</span>
            <span
              >R$
              {{ parseFloat(totalValue - servicesTotalValue).toFixed(2) }}</span
            >
          </div>
          <div>
            <span>Serviços</span>
            <span>R$ {{ parseFloat(servicesTotalValue).toFixed(2) }}</span>
          </div>
          <div>
            <span> Comissão do Consultor </span>
            <span class="!text-red-400"
              >R$ {{ parseFloat(consultantProfit).toFixed(2) }}</span
            >
          </div>
          <div>
            <span> Comissão do Projetista </span>
            <span class="!text-red-400"
              >R$ {{ parseFloat(getDesignerCommissionProfit).toFixed(2) }}</span
            >
          </div>
          <div>
            <span> Impostos </span>
            <span class="!text-red-400"
              >R$ {{ parseFloat(stateTaxValue).toFixed(2) }}</span
            >
          </div>
          <div class="flex items-center">
            <el-icon size="40px"><Money /></el-icon>
            <div class="text-green-400 font-bold">
              R$ {{ parseFloat(finalProfit).toFixed(2) }}
              <span>de Lucro</span>
            </div>
          </div>
        </div>

        <div
          class="bg-[#222222] absolute left-0 bottom-0 w-full text-[white] py-4 rounded-br-[6px] rounded-bl-[6px] flex justify-between px-4"
        >
          <span>Total</span>
          <span
            >R$
            {{ parseFloat(totalValue - getDiscountsValue).toFixed(2) }}</span
          >
        </div>
      </div>

      <div class="rounded-[4px] shadow-lg bg-white p-4">
        <div
          class="w-full px-6 py-4 flex items-center justify-center gap-1"
          :class="{
            'border-2 border-[#00A000]': approvalStatus,
            'border-2 border-[#DC2626]': !approvalStatus,
          }"
        >
          <el-icon v-if="approvalStatus" color="#00A000"><Select /></el-icon>
          <el-icon v-else color="#DC2626"><Warning /></el-icon>
          <div v-if="approvalStatus !== null">
            <p
              :class="{
                'text-green-600': approvalStatus,
                'text-red-600': !approvalStatus,
              }"
            >
              {{ approvalStatus ? "Aprovada!" : "Rejeitada!" }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div
      class="checkout-buttons rounded-[4px] shadow-lg bg-white p-4 h-fit flex flex-col items-center gap-[16px]"
    >
      <el-button
        :disabled="!approvalStatus || !isEditing"
        style="width: 200px"
        size="large"
        color="#7367F0"
        @click="handleSendProposal"
        >Enviar</el-button
      >
      <el-button style="width: 100%" size="large" @click="openDialog"
        >Visualizar</el-button
      >
      <el-button
        style="width: 100%"
        size="large"
        @click="handleSaveOrUpdateData"
        >Salvar</el-button
      >
    </div>

    <el-dialog
      title=""
      v-model="dialogVisible"
      width="80%"
      @close="handleClose"
    >
      <Invoice
        :clientData="clientData"
        :items="items"
        :checkoutFormData="checkoutFormData"
        ref="invoiceComponent"
      />

      <!-- Botão de gerar PDF dentro do Dialog -->
      <template #footer v-if="isEditing">
        <el-button @click="generatePDF" type="primary">Gerar PDF</el-button>
      </template>
    </el-dialog>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import api from "@/services/api";
import { ElNotification } from "element-plus";
import { Plus } from "@element-plus/icons-vue";
import Invoice from "@/components/ProposalPdf/index.vue";

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  clientData: {
    type: Object,
  },
  checkoutFormData: {
    type: Object,
    default: () => ({}),
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const form = ref({
  consultantCommission: 10,
  designerCommission: 0,
  technicalCommission: 0,
  thirdPartyCommission: 0,
  taxes: 13,
  discounts: 0,
  total: 0,
  approvalStatus: 0,
  paymentMethods: [],
});

const formRef = ref(null);

const approvalStatus = ref(null);

const servicesTotalValue = ref(0);

const dialogVisible = ref(false);

const openDialog = () => {
  dialogVisible.value = true;
};

const handleClose = () => {
  dialogVisible.value = false;
};

const invoiceComponent = ref(null);
const generatePDF = () => {
  invoiceComponent.value.generatePDF();
};

const totalValue = computed(() => {
  return props.items.reduce((sum, item) => {
    return sum + parseFloat(item.unitPrice) * item.quantity;
  }, 0);
});

// para remover os  79%
const totalValueCost = computed(() => {
  let serviceCost = 0;

  const total = props.items.reduce((sum, item) => {
    if (item.code && item.code > 0) {
      const retailPrice = parseFloat(item.unitPrice) / 1.79;
      return sum + retailPrice * item.quantity;
    } else {
      serviceCost += parseFloat(item.unitPrice) * item.quantity;
      return sum + parseFloat(item.unitPrice) * item.quantity;
    }
  }, 0);

  servicesTotalValue.value = serviceCost;
  return total;
});

const consultantProfit = computed(() => {
  const commissionPercentage = form.value.consultantCommission / 100;
  const profit = totalValue.value * commissionPercentage;
  return Number(parseFloat(profit).toFixed(2));
});

const stateTaxValue = computed(() => {
  let stateTaxValue = form.value.taxes / 100;
  return Number(
    parseFloat(
      (totalValue.value - servicesTotalValue.value) * stateTaxValue
    ).toFixed(2)
  );
});

const getDesignerCommissionProfit = computed(() => {
  let designerCommissionValue = form.value.designerCommission / 100;

  return Number(
    parseFloat(totalValue.value * designerCommissionValue).toFixed(2)
  );
});

const getTechnicalCommissionProfit = computed(() => {
  let technicalCommissionValue = form.value.technicalCommission / 100;

  return Number(
    parseFloat(totalValue.value * technicalCommissionValue).toFixed(2)
  );
});

const getthirdPartyCommission = computed(() => {
  let thirdPartyCommissionValue = form.value.thirdPartyCommission / 100;

  return Number(
    parseFloat(totalValue.value * thirdPartyCommissionValue).toFixed(2)
  );
});

const finalProfit = computed(() => {
  const cost =
    Number(totalValueCost.value - servicesTotalValue.value) +
    consultantProfit.value +
    getDesignerCommissionProfit.value +
    getTechnicalCommissionProfit.value +
    getthirdPartyCommission.value +
    stateTaxValue.value;

  const finalProfitValue = totalValue.value - cost - getDiscountsValue.value;

  return Number(finalProfitValue.toFixed(2));
});

const getDiscountsValue = computed(() => {
  const discountsPercent = form.value.discounts / 100;

  return Number(parseFloat(totalValue.value * discountsPercent).toFixed(2));
});

const proposalApprovalStatus = () => {
  const profit = finalProfit.value;
  const tenPercentOfTotalValue = totalValue.value * 0.1;
  approvalStatus.value = profit >= tenPercentOfTotalValue;
  form.value.approvalStatus = approvalStatus.value;
};

const handleSaveOrUpdateData = async () => {
  if (props.isEditing) {
    await handleUpdate();
  } else {
    await handleCreate();
  }
};

const handleCreate = async () => {
  try {
    form.value.total = totalValue.value;

    const payload = {
      formData: form.value,
      items: props.items,
      clientData: props.clientData,
    };
    const response = await api.post("/proposals", payload);
    handleResponse(response);
  } catch (error) {
    console.error("Error saving data:", error);
  }
};

const handleUpdate = async () => {
  try {
    form.value.total = totalValue.value;

    const payload = {
      formData: form.value,
      items: props.items,
      clientData: props.clientData,
    };

    const proposalId = props.clientData.proposalNumber;
    const response = await api.patch(`/proposals/${proposalId}`, payload);
    handleResponse(response);
    console.log("dados para atualizar: ", payload);
  } catch (error) {
    console.error("Error updating data:", error);
  }
};

const handleResponse = (response) => {
  switch (response.status) {
    case 201: 
      ElNotification({
        title: "Sucesso",
        message: props.isEditing
          ? "Proposta atualizada com sucesso!"
          : "Proposta criada com sucesso!",
        type: "success",
      });
      break;

    case 404: 
      ElNotification({
        title: "Erro",
        message: "Cliente não encontrado ou recurso solicitado não existe.",
        type: "error",
      });
      break;

    case 500: 
      ElNotification({
        title: "Erro",
        message: "Ocorreu um erro ao criar ou atualizar a proposta. Tente novamente.",
        type: "error",
      });
      break;

    default:
      ElNotification({
        title: "Aviso",
        message: "Ocorreu um erro inesperado. Tente novamente mais tarde.",
        type: "warning",
      });
      break;
  }
};


const addPaymentMethod = () => {
  if (form.value.paymentMethods.length > 0) return;

  form.value.paymentMethods.push("");
};

const removePaymentMethod = (index) => {
  if (form.value.paymentMethods.length > 0) {
    form.value.paymentMethods.splice(index, 1);
  } else {
    form.value.paymentMethods[0] = "";
  }
};

const handleSendProposal = async () => {
  try {
    const proposalId = props.clientData.proposalNumber;
    const response = await api.post(
      `/proposals/${proposalId}/send-signature-link`
    );
  } catch (error) {}
};

watch(
  finalProfit,
  () => {
    proposalApprovalStatus();
  },
  { immediate: true }
);

watch(
  () => totalValue,
  () => {
    proposalApprovalStatus();
  }
);

watch(
  () => props.items,
  (newItems) => {},
  { immediate: true }
);

watch(
  () => props.checkoutFormData,
  (newData) => {
    if (props.isEditing) {
      form.value = {
        consultantCommission: newData.consultantCommission || 10,
        designerCommission: newData.designerCommission || 0,
        technicalCommission: newData.technicalCommission || 0,
        thirdPartyCommission: newData.thirdPartyCommission || 0,
        taxes: newData.taxes || 13,
        discounts: newData.discounts || 0,
        total: newData.total || 0,
        approvalStatus: newData.approvalStatus || 0,
        paymentMethods: newData.paymentMethods || [],
      };
    }
  },
  { immediate: true }
);
</script>

<style scoped>
.proposal-sumary > div {
  display: flex;
  justify-content: space-between;
  column-gap: 25px;
}

.proposal-sumary > div > span:first-child {
  font-size: 14px;
  color: #6e6b7b;
  font-family: "Montserrat", sans-serif;
}

.proposal-sumary > div > span:nth-child(2) {
  font-family: "Montserrat", sans-serif;
  font-weight: 600;
  font-size: 14px;
  color: #6e6b7b;
}

.checkout-buttons :deep(.el-button:not(:first-child)) {
  --el-button-text-color: var(--primary-color-indigo) !important;
}

.checkout-buttons :deep(.el-button:hover) {
  background-color: none;
  border-color: var(--primary-color-indigo);
  color: var(--primary-color-indigo);
}

:deep(.el-dialog) {
  border-radius: 8px;
}

@media only screen and (max-width: 991px) {
  .proposal-checkout {
    flex-direction: column;
  }
  .proposal-checkout .options {
    width: 100%;
  }
}
</style>
