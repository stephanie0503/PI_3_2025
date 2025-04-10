<template>
  <div ref="invoiceContent" class="px-6 py-1 bg-white printRef">
    <!-- Cabeçalho -->
    <div class="flex justify-between mb-6">
      <div>
        <div class="flex items-center">
          <img
            class="w-[240px]"
            src="@/assets/images/dinamica-logo.png"
            alt=""
            srcset=""
          />
        </div>
      </div>
      <div class="text-right">
        <h3 class="text-primary ml-2 font-bold">
          Dinâmica Informática e Telecomunicações Ltda
        </h3>

        <div class="mt-4">
          <p class="text-sm">Rua Dr. Alarico Silveira, 378 - São Paulo - SP</p>
          <p class="text-sm">Fone/Whatsapp: +55 (11) 2225-8800</p>
          <p class="text-sm">Site: www.dinamicasp.com.br</p>
        </div>
      </div>
    </div>

    <!-- Detalhes do cliente -->
    <div class="flex flex-col gap-y-[20px]">
      <div>
        <h6 class="text-center bg-[#b5b1b1] text-black font-bold py-1">
          Pedido para Fornecimento de Equipamento/Serviço:
        </h6>
        <div
          class="bg-[#dbd7d7] text-black font-bold p-1 flex justify-between items-center"
        >
          <h6>Dados para Faturamento e Entrega:</h6>
          <span>Data: {{ formatDate(clientData.creationDate) }}</span>
        </div>
      </div>
      <div class="py-1 flex flex-col gap-y-[6px]">
        <el-row>
          <el-col :span="18">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Razão Social:</p>
                <p>{{ clientData.clientName }}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Contato:</p>
                <p>{{ clientData.contact }}</p>
              </div>
            </div>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <div class="flex gap-x-[10px] items-center">
              <p class="text-black font-bold">Telefone:</p>
              <p>{{ clientData.phone }}</p>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Celular:</p>
                <p>{{ clientData.mobile }}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px]">
                <p class="text-black font-bold">Email:</p>
                <p>{{ clientData.clientEmail }}</p>
              </div>
            </div>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="18">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Endereço:</p>
                <p>
                  {{ clientData.address + ", " + clientData.addressNumber }}
                </p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Bairro:</p>
                <p>{{ clientData.neighborhood }}</p>
              </div>
            </div>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <div class="flex gap-x-[10px] items-center">
              <p class="text-black font-bold">Cidade:</p>
              <p>{{ clientData.city }}</p>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Estado:</p>
                <p>{{ clientData.state }}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="grid-content">
              <div class="flex gap-x-[10px]">
                <p class="text-black font-bold">Cep:</p>
                <p class="text-sm">{{ clientData.zipCode }}</p>
              </div>
            </div>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">CNPJ:</p>
                <p>{{ clientData.cnpj }}</p>
              </div>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content">
              <div class="flex gap-x-[10px] items-center">
                <p class="text-black font-bold">Inscr.Est:</p>
                <p>{{ clientData.insEst }}</p>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>

      <!-- Tabela de serviços/produtos -->
      <div class="overflow-x-auto">
        <h6 class="text-center bg-[#b5b1b1] text-black font-bold py-1">
          Descrição dos Equipamentos/Serviços:
        </h6>
        <table class="min-w-full text-sm border-[1px]">
          <thead>
            <tr class="border-b">
              <th class="py-2 pl-2 text-left">Produtos e Serviços</th>
              <th class="py-2 pl-2 text-left">Qtde</th>
              <th class="py-2 pl-2 text-left">Valor Un</th>
              <th class="py-2 pl-2 text-left">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.description" class="border-b">
              <td class="py-2 pl-2">{{ item.description }}</td>
              <td class="py-2 pl-2 text-left">{{ item.quantity }}</td>
              <td class="py-2 pl-2 text-left">
                R$ {{ parseFloat(item.unitPrice).toFixed(2) }}
              </td>
              <td class="py-2 pl-2 text-left uppercase">
                R$ {{ parseFloat(item.unitPrice * item.quantity).toFixed(2) }}
              </td>
            </tr>
          </tbody>
        </table>

        <div class="flex items-center justify-between w-100 pr-9 mt-1 mb-1">
          <p class="text-black font-bold uppercase">
            Total &nbsp;da&nbsp; Proposta&nbsp; com &nbsp;Equipamentos
            &nbsp;e&nbsp; Serviços:
          </p>
          <p>R$ {{ checkoutFormData.total }}</p>
        </div>
      </div>
      <el-divider />
    </div>

    <!-- Totais -->
    <div>
      <div class="flex gap-x-[10px]">
        <p class="font-bold">Prazo de Entrega:</p>
        <p></p>
      </div>
      <div class="flex gap-x-[10px]">
        <p class="font-bold">Validade da Proposta:</p>
        <p>05 Dias</p>
      </div>
      <div class="flex gap-x-[10px]">
        <p class="font-bold">Impostos:</p>
        <p>Inclusos</p>
      </div>
    </div>

    <!-- Assinaturas -->
    <div class="mt-3">
      <h6 class="text-center bg-[#b5b1b1] text-black font-bold py-1">
        Aceite da Proposta Num: {{ clientData.proposalNumber }}
      </h6>
      <div class="signatures h-[120px] flex flex-col justify-end">
        <el-divider> &nbsp&nbsp </el-divider>
        <div class="flex justify-between gap-x-2 items-center">
          <div class="flex w-[50%] justify-center text-center">
            <span class="text-black font-bold">{{
              clientData.clientName
            }}</span>
          </div>
          <div class="flex w-[50%] justify-center text-center">
            <span class="text-black font-bold"
              >DINÂMICA INFORM E TELECOMUNICAÇÕES LTDA</span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from "vue";
import jsPDF from "jspdf";

// Recebe as props passadas pelo componente pai
const props = defineProps({
  clientData: {
    type: Object,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  },
  checkoutFormData: {
    type: Array,
    required: true,
  },
});

const invoiceContent = ref(null);

const subtotal = computed(() => {
  console.log(props.checkoutFormData);
  return props.items.reduce((acc, item) => acc + item.rate * item.hours, 0);
});

const discount = computed(() => 28); // Exemplo de desconto
const taxRate = computed(() => 21); // Exemplo de taxa
const totalAmount = computed(() => {
  const subtotalAmount = subtotal.value;
  const discountAmount = discount.value;
  const taxAmount = (subtotalAmount - discountAmount) * (taxRate.value / 100);
  return subtotalAmount - discountAmount + taxAmount;
});

// Método para gerar o PDF
const generatePDF = async () => {
  await nextTick();

  const doc = new jsPDF({
    orientation: "portrait",
    unit: "px",
    format: "a4", // pagina A4
  });

  const content = invoiceContent.value;

  // margens
  const margin = 10;

  // Aguardar a renderização completa antes de gerar o PDF
  setTimeout(() => {
    doc.html(content, {
      callback: function (doc) {
        // Exibir o PDF gerado
        const pdfDataUri = doc.output("datauristring");
        const newTab = window.open("Proposta_PDF", "_blank");
        setTimeout(() => {
          newTab.document.write(
            `<html><head><title>Proposta</title></head><body><embed src="${pdfDataUri}" width="100%" height="100%"></body></html>`
          );
        }, 100);
      },
      margin: [margin, margin, margin, margin], // Ajuste de margens do PDF
      x: margin,
      y: margin,
      autoPaging: true, // Isso ajuda a dividir o conteúdo em várias páginas automaticamente, caso não caiba
      html2canvas: {
        scale: 0.4, // escala para reduzir o conteúdo
      },
    });
  }, 500);
};

const formatDate = (date) => {
  return new Intl.DateTimeFormat("pt-BR").format(new Date(date));
};

defineExpose({ generatePDF });
</script>

<style scoped>

.signatures :deep(.el-divider) {
  margin-bottom: 6px;
}
</style>