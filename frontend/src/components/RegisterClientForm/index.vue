<template>
  <div>
    <el-form
      :model="form"
      label-width="120px"
      label-position="top"
      @submit.prevent="submitForm"
    >
      <div class="rounded-lg">
        <el-row :gutter="20">
          <el-col :sm="24" :md="7">
            <el-form-item
              label="Cliente"
              :rules="[
                {
                  required: true,
                  message: 'Nome do cliente é obrigatório',
                  trigger: 'blur',
                },
              ]"
            >
              <el-input
                v-model="form.clientName"
                placeholder="Digite o nome do cliente"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="7">
            <el-form-item
              label="Nome Fantasia"
              :rules="[
                {
                  required: true,
                  message: 'Nome fantasia é obrigatório',
                  trigger: 'blur',
                },
              ]"
            >
              <el-input
                v-model="form.clientFantasyName"
                placeholder="Digite o nome fantasia"
              />
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="4">
            <el-form-item
              label="CPF/CNPJ"
              :rules="[
                {
                  required: true,
                  message: 'CPF/CNPJ é obrigatório',
                  trigger: 'blur',
                },
              ]"
            >
              <el-input
                v-model="form.cnpj"
                :formatter="formatCNPJ"
                maxlength="14"
                placeholder="Digite o CPF ou CNPJ"
                :disabled="loading"
              >
                <template #suffix>
                  <el-button
                    :icon="Search"
                    @click="handleSearchClick"
                    :disabled="loading"
                    class="search-btn"
                    circle
                  ></el-button>
                </template>
              </el-input>
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="6">
            <el-form-item
              label="Email do Cliente"
              :rules="[
                { type: 'email', message: 'E-mail inválido', trigger: 'blur' },
              ]"
            >
              <el-input
                v-model="form.clientEmail"
                type="email"
                placeholder="Digite o e-mail do cliente"
              />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="6">
            <el-form-item
              label="CEP"
              :rules="[
                {
                  required: true,
                  message: 'CEP é obrigatório',
                  trigger: 'blur',
                },
              ]"
            >
              <el-input
                v-model="form.zipCode"
                @blur="fetchAddress"
                :formatter="formatCep"
                maxlength="8"
                placeholder="Digite o CEP"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Endereço">
              <el-input
                v-model="form.address"
                placeholder="Digite o endereço"
              />
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="2">
            <el-form-item label="Número">
              <el-input v-model="form.addressNumber" placeholder="" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="10">
            <el-form-item label="Bairro">
              <el-input
                v-model="form.neighborhood"
                placeholder="Digite o bairro"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="10">
            <el-form-item label="Cidade">
              <el-input v-model="form.city" placeholder="Digite a cidade" />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="2">
            <el-form-item label="UF">
              <el-input v-model="form.state" placeholder="Digite o estado" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="6">
            <el-form-item label="Telefone">
              <el-input
                v-model="form.phone"
                placeholder="Digite o telefone fixo"
              />
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="6">
            <el-form-item label="Celular">
              <el-input v-model="form.mobile" placeholder="Digite o celular" />
            </el-form-item>
          </el-col>

          <el-col :sm="24" :md="10">
            <el-form-item label="Contato">
              <el-input v-model="form.contact" placeholder="" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :sm="24" :md="24">
            <el-form-item>
              <el-button
                color="#7367F0"
                @click="submitForm"
                :loading="loading"
                :disabled="loading"
              >
                Salvar
              </el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </div>
    </el-form>
    <el-progress
      v-if="loading"
      :percentage="progressPercentage"
      :stroke-width="12"
      color="#696CFF"
      striped
      striped-flow
      :duration="10"
    />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { ElForm, ElFormItem, ElInput, ElMessage } from "element-plus";
import consultarCNPJ from "consultar-cnpj";
import { Search } from "@element-plus/icons-vue";
import api from "@/services/api";

const form = ref({
  clientName: "",
  clientFantasyName: "",
  clientEmail: "",
  cnpj: "",
  address: "",
  addressNumber: "",
  neighborhood: "",
  city: "",
  state: "",
  phone: "",
  mobile: "",
  contact: "",
  zipCode: "",
  insEst: "",
});

const formatCNPJ = (value) => value.replace(/\D/g, "");

const loading = ref(false);
const progressPercentage = ref(0);

const emit = defineEmits(["clientCreated"]);

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

const handleSearchClick = () => {
  if (!loading.value) {
    fetchCompanyData();
  }
};

const waitForNextRequest = async () => {
  loading.value = true;
  let remainingTime = 60;
  const interval = setInterval(() => {
    progressPercentage.value = Math.floor(((60 - remainingTime) / 60) * 100);
    remainingTime--;

    if (remainingTime <= 0) {
      clearInterval(interval);
      loading.value = false;
    }
  }, 1000);

  await new Promise((resolve) => setTimeout(resolve, 60000)); // Espera de 60 segundos
};



const fetchCompanyData = async () => {
  const cnpj = form.value.cnpj.replace(/\D/g, "");

  if (cnpj.length === 14) {
    ElMessage.success("CNPJ válido, buscando dados...");

    try {
      await waitForNextRequest();

      const response = await consultarCNPJ(cnpj);
      const data = response;

      form.value.clientName = data.razao_social;
      form.value.clientFantasyName = data.estabelecimento.nome_fantasia || "";
      form.value.address = `${data.estabelecimento.tipo_logradouro} ${data.estabelecimento.logradouro}, ${data.estabelecimento.numero}`; // Endereço
      form.value.neighborhood = data.estabelecimento.bairro || "";
      form.value.city = data.estabelecimento.cidade.nome || "";
      form.value.state = data.estabelecimento.estado.sigla || "";
      form.value.zipCode = data.estabelecimento.cep || "";
      form.value.addressNumber = data.estabelecimento.numero;
      form.value.phone = `${data.estabelecimento.ddd1} ${data.estabelecimento.telefone1}` || "";
      form.value.clientEmail = data.estabelecimento.email || "";

      // Simples Nacional
      form.value.insEst = data.simples.simples;

      ElMessage.success("Dados do CNPJ carregados com sucesso!");
    } catch (error) {
      ElMessage.error("Não foi possível consultar o CNPJ. Tente novamente.");
    }
  } else {
    ElMessage.warning(
      "CNPJ inválido. Por favor, insira um CNPJ válido com 14 dígitos."
    );
  }
};

const submitForm = async () => {
  try {

    const response = await api.post('/clients', form.value);


    if(response.data.data) {
        ElMessage.success('Cliente criado com sucesso!');
    const newClient = await response.data.data;
    emit('clientCreated', newClient);
    }


  } catch (error) {
    console.error(error);

    if (error.response) {
      switch (error.response.status) {
        case 422:
          if (error.response.data.messages) {
            const messages = error.response.data.messages;
            if (typeof messages === 'string') {
              ElMessage.error(messages);
            } else {
              Object.keys(messages).forEach((field) => {
                ElMessage.error(`${field}: ${messages[field].join(', ')}`);
              });
            }
          }
          break;
        case 500:
          ElMessage.error('Ocorreu um erro interno no servidor. Tente novamente.');
          break;
        case 404:
          ElMessage.error('Recurso não encontrado. Tente novamente mais tarde.');
          break;
        default:
          ElMessage.error(`Erro: ${error.response.data.error || 'Erro desconhecido. Tente novamente.'}`);
          break;
      }
    } else {
      ElMessage.warning('Ocorreu um erro desconhecido. Tente novamente.');
    }
  }
};
</script>

<style scoped>
:deep(.el-button.search-btn) {
  background-color: transparent;
  border: none;
  padding: 0;
  box-shadow: none;
}

.search-btn .el-icon {
  font-size: 20px;
  color: var(--primary-color-indigo);
}

:deep(.el-button.search-btn:disabled) {
  opacity: 0.5;
  pointer-events: none;
}


:deep(.el-progress-bar__innerText) {
    font-size: 0.6rem;
}
</style>
