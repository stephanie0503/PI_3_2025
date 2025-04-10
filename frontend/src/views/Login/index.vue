<template>
  <div
    class="flex items-center justify-center h-screen bg-gray-100 login-container"
  >
    <div class="flex w-full bg-white shadow-lg h-screen">
      <div
        class="hidden lg:flex lg:flex-1 bg-[#F8F8F8] items-center justify-center"
      >
        <img
          src="@/assets/images/login-v2.svg"
          alt="Imagem de Login"
          class="max-w-full max-h-full w-3/4 h-auto object-contain animate__animated animate__fadeInLeft"
        />
      </div>

      <div class="w-full lg:w-[450px] py-16 px-20 flex flex-col">
        <h2 class="text-2xl font-bold mb-2 text-[#5E5873] font-montserrat">
          Bem-vindo! 👋
        </h2>
        <p
          class="text-gray-600 mb-6 text-[#5E5873] text-[14px] font-montserrat"
        >
          Faça login na sua conta
        </p>

        <!-- Form de login -->
        <el-form
          :model="form"
          @submit.prevent="handleSubmit"
          label-position="top"
          :rules="rules"
          ref="formRef"
        >
          <el-form-item label="Email" prop="email" class="mb-4">
            <el-input
              v-model="form.email"
              placeholder="user@dinamicasp.com.br"
              type="email"
            />
          </el-form-item>

          <el-form-item label="Senha" prop="password" class="mb-4">
            <el-input
              v-model="form.password"
              placeholder="············"
              type="password"
              suffix-icon="el-icon-view"
              show-password
            />
          </el-form-item>

          <el-form-item class="flex items-center justify-between mb-6">
            <el-checkbox v-model="form.rememberMe">Lembrar de mim</el-checkbox>
            <router-link
              to="/forgot-password"
              class="text-[12px] font-normal text-[#5E50EE] hover:text-indigo-500 pl-2 font-montserrat"
            >
              Esqueceu a senha?
            </router-link>
          </el-form-item>

          <el-form-item>
            <el-button
              type="primary"
              :loading="loading"
              :disabled="loading"
              class="w-full"
              native-type="submit"
              @click="handleSubmit"
              color="#4338CA"
            >
              Entrar
            </el-button>
          </el-form-item>
        </el-form>

        <p class="text-center mt-4 text-sm">
          Novo na nossa plataforma?
          <a href="#" class="text-indigo-600 hover:text-indigo-500"
            >Crie uma conta</a
          >
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import { ElNotification, ElMessage } from "element-plus";

const formRef = ref(null);

const form = ref({
  email: "",
  password: "",
  rememberMe: false,
});

const rules = reactive({
  email: [
    {
      required: true,
      message: "Por favor, insira seu e-mail",
      trigger: "blur",
    },
    {
      type: "email",
      message: "Por favor, insira um e-mail válido",
      trigger: "blur",
    },
  ],
  password: [
  { 
    required: true, 
    message: "Por favor, insira sua senha", 
    trigger: "blur" 
  },
  { 
    validator: (rule, value, callback) => {
      if (!value.trim()) {  
        callback(new Error('A senha não pode ser vazia ou composta apenas por espaços.'));
      } else {
        callback(); 
      }
    },
    trigger: 'blur'
  }
]

});

const loading = ref(false);
const authStore = useAuthStore();
const router = useRouter();

const handleSubmit = async () => {
  loading.value = true;

  try {
    const isValid = await formRef.value.validate(() => {});

    if (!isValid) {
      return;
    }

    await authStore.login(form.value.email, form.value.password);

    if (form.value.rememberMe) {
      localStorage.setItem("userEmail", form.value.email);
    }

    const userName = authStore.user?.name || "Usuário";
    ElNotification({
      customClass: "login-notify",
      title: `👋 Bem-vindo, ${userName}!`,
      message: "Login realizado com sucesso 🎉",
      type: "success",
    });

    router.push("/");
  } catch (error) {
    switch (true) {
      case !!error.response && error.response.status === 401:
        ElMessage.error(
          "Credenciais inválidas. Por favor, verifique seu e-mail e senha."
        );
        break;
      case !!error.response && error.response.status === 500:
        ElMessage.error("Erro no servidor. Tente novamente mais tarde.");
        break;
      case !!error.response:
        ElMessage.error(
          "Ocorreu um erro ao realizar o login. Tente novamente."
        );
        break;
      case !!error.request:
        ElMessage.warning(
          "Erro de conexão. Não foi possível alcançar o servidor. Tente novamente mais tarde."
        );
        break;
      default:
        ElMessage.warning("Oops! Ocorreu um erro inesperado.");
        break;
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  const storedEmail = localStorage.getItem("userEmail");
  if (storedEmail) {
    form.value.email = storedEmail;
    form.value.rememberMe = true;
  }
});
</script>

<style>
.el-notification.login-notify .el-notification__title {
  color: #56d38e;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.login-container .el-input {
  --el-input-focus-border-color: var(--primary-color-indigo);
  --el-input-hover-border-color: var(--primary-color-indigo);
}
</style>

<style scoped>
button {
  min-height: 48px;
}
</style>
