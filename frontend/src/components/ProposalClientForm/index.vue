<template>
  <div class="p-4 space-y-4 shadow-md bg-white">
    <el-form :model="form" label-width="120px" label-position="top">
      <!-- Dados do Consultor -->
      <div class="p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Dados do Consultor</h3>

        <el-row :gutter="20">
          <el-col :sm="24" :md="12">
            <el-form-item label="N° Proposta">
              <el-input
                v-model="form.proposalNumber"
                disabled
                placeholder=""
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="12">
            <el-form-item label="Data">
              <el-date-picker
                v-model="form.creationDate"
                type="date"
                placeholder="Selecione a data"
                style="width: 100%"
                format="DD/MM/YYYY"
                placement="top"
                :disabled="isEditing"
              ></el-date-picker>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="12">
            <el-form-item label="Consultor">
              <el-autocomplete
                v-model="form.consultantName"
                :fetch-suggestions="fetchConsultantSuggestions"
                placeholder="Digite o nome do consultor"
                @select="selectConsultant"
              >
                <template #suggestion="{ item }">
                  <div>{{ item.value }}</div>
                </template>
              </el-autocomplete>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Email do Consultor">
              <el-input
                v-model="form.consultantEmail"
                type="email"
                placeholder="Digite o e-mail do consultor"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </div>

      <el-divider />

      <!-- Dados do Cliente -->
      <div class="p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Dados do Cliente</h3>
        <el-row :gutter="20">
          <el-col :sm="24" :md="8">
            <el-form-item label="Cliente">
              <el-autocomplete
                v-model="form.clientName"
                :fetch-suggestions="fetchClientsSuggestions"
                placeholder="Digite o nome do cliente"
                @select="selectClient"
              >
                <template #suggestion="{ item }">
                  <div>{{ item.value }}</div>
                </template>
              </el-autocomplete>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="8">
            <el-form-item label="Nome Fantasia">
              <el-input
                v-model="form.clientFantasyName"
                placeholder="Digite o e-mail do cliente"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="8">
            <el-form-item label="Email do Cliente">
              <el-input
                v-model="form.clientEmail"
                type="email"
                placeholder="Digite o e-mail do cliente"
              ></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="6">
            <el-form-item label="CEP">
              <el-input
                v-model="form.zipCode"
                @blur="fetchAddress"
                :formatter="formatCep"
                maxlength="8"
                placeholder="Digite o CEP"
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="12">
            <el-form-item label="Endereço">
              <el-input
                v-model="form.address"
                placeholder="Digite o endereço do cliente"
              ></el-input>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="2">
            <el-form-item label="Número">
              <el-input v-model="form.addressNumber" placeholder=""></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="10">
            <el-form-item label="Bairro">
              <el-input
                v-model="form.neighborhood"
                placeholder="Digite o bairro"
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="10">
            <el-form-item label="Cidade">
              <el-input
                v-model="form.city"
                placeholder="Digite a cidade"
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="2">
            <el-form-item label="UF">
              <el-input v-model="form.state" placeholder=""></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="6">
            <el-form-item label="Telefone">
              <el-input
                v-model="form.phone"
                placeholder="Digite o telefone fixo"
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="6">
            <el-form-item label="Celular">
              <el-input
                v-model="form.mobile"
                placeholder="Digite o celular"
              ></el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="10">
            <el-form-item label="Contato">
              <el-input v-model="form.contact" placeholder=""></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </div>
    </el-form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { ElForm, ElFormItem, ElInput, ElDatePicker } from "element-plus";
import api from "@/services/api";
import { useAuthStore } from "@/stores/authStore";

const authStore = useAuthStore();

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const form = ref({
  proposalNumber: "",
  consultantName: authStore.user?.name,
  consultantEmail: authStore.user?.email,
  creationDate: Date.now(),
  clientId: 0,
  clientName: "",
  clientFantasyName: "",
  address: "",
  addressNumber: "",
  neighborhood: "",
  city: "",
  state: "",
  phone: "",
  mobile: "",
  clientEmail: "",
  cnpj: "",
  insEst: "",
  contact: "",
  zipCode: "",
});

const emit = defineEmits(["formUpdated"]);
// Computed para buscar endereço baseado no CEP
const fetchAddress = computed(async () => {
  const zipCode = form.value.zipCode;
  if (zipCode.length === 8) {
    try {
      const response = await fetch(`https://viacep.com.br/ws/${zipCode}/json/`);
      const data = await response.json();
      if (!data.erro) {
        form.value.address = data.logradouro;
        form.value.neighborhood = data.bairro;
        form.value.city = data.localidade;
        form.value.state = data.uf;
      } else {
        console.error("CEP não encontrado");
      }
    } catch (error) {
      console.error("Erro ao buscar CEP", error);
    }
  }
});

const formatCep = (value) => {
  return value.replace(/\D/g, "");
};

const fetchConsultantSuggestions = async (query, callback) => {
  if (query.length < 3) {
    callback([]);
    return;
  }
  try {
    const response = await fetchConsultantByName(query);
    callback(response); //callback
  } catch (error) {
    console.error("Erro ao buscar sugestões", error);
    callback([]);
  }
};

const fetchConsultantByName = async (name) => {
  try {
    const response = await api.get("/users", {
      params: { name },
    });
    return response.data.map((consultant) => ({
      value: consultant.name,
      id: consultant.id,
      email: consultant.email,
    }));
  } catch (error) {
    console.error("Erro ao buscar sugestões", error);
    return [];
  }
};

const selectConsultant = (suggestion) => {
  form.value.consultantName = suggestion.value;
  form.value.consultantEmail = suggestion.email;
};

const fetchClientsSuggestions = async (query, callback) => {
  if (query.length < 3) {
    callback([]);
    return;
  }
  try {
    const response = await fetchClientsByName(query);
    callback(response);
  } catch (error) {
    console.error("Erro ao buscar sugestões de clientes", error);
  }
};

const fetchClientsByName = async (name) => {
  try {
    const response = await api.get("/clients", {
      params: { name },
    });
    return response.data.map((client) => ({
      value: client.name,
      id: client.id,
      email: client.email,
      fantasyName: client.fantasy_name,
      zipCode: client.zip_code,
    }));
  } catch (error) {
    console.error("Erro ao buscar sugestões de clientes", error);
    return [];
  }
};

const selectClient = (suggestion) => {
  form.value.clientName = suggestion.value;
  form.value.clientEmail = suggestion.email;
  form.value.zipCode = suggestion.zipCode;
  form.value.clientId = suggestion.id;
  form.value.clientFantasyName = suggestion.fantasyName;
};

const emitFormUpdate = () => {
  emit("formUpdated", form.value);
};

watch(
  form,
  (newValue) => {
    emitFormUpdate();
  },
  { deep: true }
);

watch(
  () => props.data,
  (newData) => {
    if (props.isEditing) {
      form.value = {
        proposalNumber: newData?.proposalNumber || "",
        consultantName: newData?.consultantName || "",
        consultantEmail: newData.consultantEmail || "",
        clientId: newData.clientId || 0,
        clientName: newData.clientName || "",
        clientFantasyName: newData.clientFantasyName || "",
        address: newData.address || "",
        addressNumber: newData.addressNumber || "",
        neighborhood: newData.neighborhood || "",
        city: newData.city || "",
        state: newData.state || "",
        phone: newData.phone || "",
        mobile: newData.mobile || "",
        clientEmail: newData.clientEmail || "",
        contact: newData.contact || "",
        cnpj: newData.cnpj || "",
        insEst: newData.insEst || "",
        zipCode: newData.zipCode || "",
        creationDate: newData.creationDate || "",
      };
    }
    console.log(newData)
  },
  { immediate: true }
);
</script>
