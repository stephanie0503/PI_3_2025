<template>
  <div class="dialog-container">
    <el-dialog
      v-model="dialogVisible"
      title="Novo Usuário"
      width="30%"
      append-to-body
      @close="handleClose"
      class="user-register__modal"
    >
      <el-form :model="form" label-width="140px" class="px-4">
        <!-- Full Name -->
        <el-form-item label="Nome" class="flex-col text-[11px]">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>
        <!-- Username -->
        <el-form-item label="Usuário" class="flex-col">
          <el-input v-model="form.username" autocomplete="off" />
        </el-form-item>
        <!-- Email -->
        <el-form-item label="Email" class="flex-col">
          <el-input v-model="form.email" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Senha" class="flex-col">
          <el-input v-model="form.password" type="password" show-password />
        </el-form-item>
        <!-- User Role -->
        <el-form-item label="User Role" class="flex-col">
          <el-select v-model="form.role" placeholder="Select User Role">
            <el-option label="Usuário" value="user" />
            <el-option label="Admin" value="admin" />
          </el-select>
        </el-form-item>
      </el-form>
      <template #footer>
        <div class="dialog-footer">
          <el-button @click="cancel">Cancelar</el-button>
          <el-button class="register-btn" @click="submitForm"
            >Cadastrar</el-button
          >
        </div>
      </template>
    </el-dialog>
  </div>
</template>

<script setup>
import {reactive, ref, watch } from "vue";
import api from '@/services/api';
import { ElMessage } from "element-plus";

const props = defineProps({
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const dialogVisible = ref(props.modelValue);

const form = reactive({
  name: "",
  username: "",
  email: "",
  password: "",
  role: "",
});

const submitForm = async () => {
  try {
    
    await api.post('/users/register', form);
    ElMessage.success('Usuário cadastrado com sucesso!');
    // Fechar o modal e resetar o formulário
    emit("update:modelValue", false);
    resetForm();
  } catch (error) {
    ElMessage.error('Erro ao cadastrar usuário.');
  }
};


const cancel = () => {
  emit("update:modelValue", false);
};

const resetForm = () => {
  form.name = "";
  form.username = "";
  form.email = "";
  form.password = "";
  form.role = "";
};

const handleClose = () => {
  // Ensure the modal is closed and reset form
  emit("update:modelValue", false);
};

// Watch prop to sync local state with prop
watch(
  () => props.modelValue,
  (newVal) => {
    dialogVisible.value = newVal;
  }
);

watch(dialogVisible, (newVal) => {
  emit("update:modelValue", newVal);
});
</script>

<style>
.user-register__modal {
  right: 0;
  position: absolute !important;
  height: 100vh;
  top: 0 !important;
  font-family: "Montserrat", sans-serif;
}

.el-dialog.user-register__modal {
  margin-top: 0;
  margin: 0;
}

.form-item {
  margin-bottom: 1rem;
}

.el-dialog.user-register__modal .el-form-item__label {
  display: block;
  font-size: 11px;
  color: #5e5873;
}

.el-dialog.user-register__modal .el-input {
  --el-input-focus-border-color: var(--primary-color-indigo);
  --el-input-hover-border-color: var(--primary-color-indigo);
}

.el-dialog.user-register__modal .el-button.register-btn {
  background-color: var(--primary-color-indigo);
  --el-button-hover-bg-color: var(--primary-color-indigo);
  color: white;
}
</style>
