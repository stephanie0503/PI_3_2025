<template>
  <div class="w-full userWebMailSettings">
    <el-form
      :model="form"
      label-position="top"
      ref="formRef"
      label-width="120px"
      class="grid grid-cols-3 gap-4"
    >
      <el-form-item label="Email" prop="imap_user" class="col-span-1">
        <el-input
          v-model="form.imap_user"
          placeholder="Digite seu email"
        ></el-input>
      </el-form-item>

      <el-form-item label="Senha" prop="imap_password'" class="col-span-1">
        <el-input
          v-model="form.imap_password"
          show-password
          type="password"
          placeholder="Digite sua senha"
        ></el-input>
      </el-form-item>

      <el-form-item label="Provedor" prop="imap_host" class="col-span-1">
        <el-select v-model="form.imap_host" placeholder="Selecione um provedor">
          <el-option label="Hostinger" value="imap.hostinger.com.br"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
  </div>
</template>
<script setup>
import {ref, watch } from "vue";
import {
  ElForm,
  ElFormItem,
  ElInput,
  ElSelect,
  ElOption,
  ElButton,
} from "element-plus";


const form = ref({
  imap_user: "",
  imap_password: "",
  imap_host: "",
});

const getFormData = () => {
  return form.value;
};

defineExpose({ getFormData });
</script>

<style>
.userWebMailSettings .el-input {
  --el-input-focus-border-color: var(--primary-color-indigo);
  --el-input-hover-border-color: var(--primary-color-indigo);
}

.userWebMailSettings .el-form-item__label {
  color: #5e5873;
  font-family: "Montserrat", sans-serif;
}
</style>
