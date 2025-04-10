<template>
  <div class="w-full p-2 users-account-settings">
    <!-- Avatar Section -->
    <div class="flex items-center space-x-4 mb-6">
      <el-avatar shape="square" size="large" :src="avatarUrl" />
      <div>
        <span class="userName">{{ form.name }}</span>
        <div class="mt-2 space-x-2">
          <el-button color="#7367F0" @click="triggerFileInput">
            Trocar
          </el-button>
          <el-button v-if="avatarUrl" @click="handleRemovePicture">
            Remover
          </el-button>
        </div>
      </div>
    </div>

    <!-- Hidden File Input -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="handleFileChange"
    />

    <!-- Form Section -->
    <el-form :model="form" label-position="top" class="space-y-4">
      <div class="grid grid-cols-3 gap-x-6">
        <el-form-item label="Username" prop="username">
          <el-input
            v-model="form.username"
            placeholder="Digite o nome de usuário"
          ></el-input>
        </el-form-item>

        <el-form-item label="Nome" prop="name">
          <el-input v-model="form.name" placeholder=""></el-input>
        </el-form-item>

        <el-form-item label="Email" prop="email">
          <el-input v-model="form.email" type="email" placeholder=""></el-input>
        </el-form-item>

        <el-form-item label="Status" prop="status">
          <el-select v-model="form.status" placeholder="Selecione o status">
            <el-option label="Ativo" value="active"></el-option>
            <el-option label="Inativo" value="inactive"></el-option>
            <el-option label="Aguardando" value="pending"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Role" prop="role">
          <el-select v-model="form.role" placeholder="Selecione o cargo">
            <el-option label="Admin" value="admin"></el-option>
            <el-option label="Usuário" value="user"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Senha" prop="password">
          <el-input
            v-model="form.password"
            placeholder=""
            type="password"
            show-password
          ></el-input>
        </el-form-item>
      </div>
    </el-form>
  </div>
</template>

<script setup>
import {ref, watch } from "vue";
import {
  ElAvatar,
  ElButton,
  ElForm,
  ElFormItem,
  ElInput,
  ElSelect,
  ElOption,
} from "element-plus";

const props = defineProps({
  userAccountData: {
    type: Object,
    default: () => ({}),
  },
});

const form = ref({
  username: "",
  name: "",
  email: "",
  status: "",
  role: "",
  password: "",
  profilepicture: "", // URL ou dados do arquivo da imagem
});

const avatarUrl = ref(""); // URL para visualização da imagem

watch(
  () => props.userAccountData,
  (newData) => {
    if (newData) {
      form.value = {
        username: newData.username || "",
        name: newData.name || "",
        email: newData.email || "",
        status: newData.status || "",
        role: newData.role || "",
        password: "",
        profilepicture: newData.profilepicture || "",
      };
      avatarUrl.value = newData.profilepicture || "";
    }
  },
  { immediate: true }
);

const fileInput = ref(null);

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarUrl.value = e.target.result;
      form.value.profilepicture = e.target.result; // Atualiza o campo profilepicture com a URL da imagem
    };
    reader.readAsDataURL(file);
  } else {
    alert("Por favor, selecione um arquivo de imagem.");
  }
};

const handleRemovePicture = () => {
  avatarUrl.value = "";
  form.value.profilepicture = ""; // Limpa o campo profilepicture
  // Opcional: limpar o input de arquivo
  if (fileInput.value) {
    fileInput.value.value = "";
  }
};

const getFormData = () => {
  return form.value;
};

defineExpose({ getFormData });
</script>

<style>
.users-account-settings {
  --primary-color: var(--primary-color-indigo);
}

.users-account-settings .el-input {
  --el-input-focus-border-color: var(--primary-color-indigo);
  --el-input-hover-border-color: var(--primary-color-indigo);
}

.users-account-settings .el-avatar {
  --el-avatar-size: 90px;
}

.users-account-settings .userName {
  color: #777480;
  font-family: "Montserrat", sans-serif;
  font-size: "20px";
  font-weight: 600;
}

.users-account-settings .el-select__wrapper.is-focused {
  box-shadow: 0 0 0 1px var(--primary-color-indigo) inset;
}
</style>
