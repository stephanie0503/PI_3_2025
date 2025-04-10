<template>
  <div class="edit-user__el-tabs">
    <el-tabs type="border-card">
      <el-tab-pane>
        <template #label="account">
          <span class="custom-tabs-label">
            <el-icon><User /></el-icon>
            <span>Conta</span>
          </span>
        </template>
        <UserAccountSettings
          ref="userAccountSettings"
          :userAccountData="userAccountData"
        />
      </el-tab-pane>

      <el-tab-pane>
        <template #label="Webmail">
          <span class="custom-tabs-label">
            <el-icon><Message /></el-icon>
            <span>Webmail</span>
          </span>
        </template>
        <WebmailSettings ref="webmailSettings"  />
      </el-tab-pane>

      <el-button color="#7367F0" size="large" @click="handleSubmit">
        Salvar
      </el-button>
    </el-tabs>
  </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { ref, onMounted } from "vue";
import WebmailSettings from "@/components/UserWebmailSettings/index.vue";
import UserAccountSettings from "@/components/UserAccountSettings/index.vue";
import api from "@/services/api";
import { ElMessage } from "element-plus";

const route = useRoute();
const userId = route.params.id;

const userAccountSettings = ref(null);
const webmailSettings = ref(null);

const userAccountData = ref({});
const webmailAccountData = ref({});

const fetchUserData = async (userId) => {
  try {
    const response = await api.get(`/users/${userId}`);
    const data = response.data;
    userAccountData.value = data;
  } catch (error) {
    console.error("Error fetching user data:", error);
  }
};

onMounted(() => {
  fetchUserData(userId);
});

const handleSubmit = async () => {
  try {
    // Obtem os dados dos componentes filhos
    const userAccountDataFromChild = userAccountSettings.value?.getFormData();
    const webmailDataFromChild = webmailSettings.value?.getFormData();

    if (!userAccountDataFromChild || !webmailDataFromChild) {
      console.error("Não foi possível obter os dados dos componentes filhos.");
      return;
    }

    console.log("Dados do UserAccountSettings:", userAccountDataFromChild);
    console.log("Dados do WebmailSettings:", webmailDataFromChild);

    // merge dos dados
    const mergedData = {
      ...userAccountDataFromChild,
      webmail: webmailDataFromChild,
    };

    console.log("dados sendo enviados", mergedData);

    // Envia os dados para o backend
    const submit = await api.put(`/users/${userId}`, mergedData);

    switch (submit.status) {
      case 200:
        ElMessage({
          message: "Usuário atualizado com sucesso.",
          type: "success",
        });
        break;
      default:
        ElMessage({
          message: "Falha ao atualizar usuário..",
          type: "error",
        });
    }
  } catch (error) {
    console.error("Erro ao salvar dados:", error);
  }
};
</script>

<style>
.edit-user__el-tabs .el-tabs__item {
  color: #5e5873 !important;
  font-family: "Montserrat", sans-serif;
  --el-text-color-secondary: var(--secondary-color);
  border-radius: 8px;
}

.edit-user__el-tabs
  .el-tabs--border-card
  > .el-tabs__header
  .el-tabs__item:not(.is-disabled):hover {
  color: #5e5873 !important;
}

.edit-user__el-tabs
  .el-tabs--border-card
  > .el-tabs__header
  .el-tabs__item.is-active {
  background: var(--primary-color-indigo);
  background-color: var(--primary-color-indigo);
  border-left-color: var(--el-border-color);
  border-right-color: var(--el-border-color);
  color: white !important;
}

.edit-user__el-tabs .el-tabs__nav-scroll {
  background-color: white;
}

.edit-user__el-tabs .el-tabs__nav {
  display: flex;
  column-gap: 20px;
  background-color: white;
  padding-left: 20px;
  padding-top: 1rem;
}
</style>
