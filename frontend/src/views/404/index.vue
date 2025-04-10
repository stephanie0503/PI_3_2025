<template>
  <div class="p-4 bg-gray-50">
    <div class="flex">
      <img
        src="@/assets/images/logo-svg.svg"
        alt="brand-logo"
        class="mr-2 logo"
      />
    </div>
    <div class="flex flex-col items-center justify-center">
      <div class="text-center">
        <div class="flex items-center justify-center mb-6"></div>
        <h2
          class="text-3xl font-bold mb-2 text-[#5E5873] font-montserrat font-semibold"
        >
          Página não encontrada 🕵🏻‍♀️
        </h2>
        <p class="text-[14px] mb-4 text-[#6E6B7B] font-montserrat font-medium">
          Oops! 😖 A URL solicitada não foi encontrada.
        </p>
        <el-button class="mb-4" @click="backToHome" color="#7367F0">
          Voltar
        </el-button>
        <img
          class="img-fluid"
          src="@/assets/images/error.svg"
          alt="Error page"
          width="400"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const backToHome = () => {
  router.push("/");
};
</script>

<style scoped>
.logo {
  height: 40px;
  width: 40px;
  transform: rotate(90deg);
}

:deep(.el-button) {
  padding: 15px 60px;
}
</style>
