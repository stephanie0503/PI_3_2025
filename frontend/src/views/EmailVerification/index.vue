<template>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { ElMessage } from 'element-plus';
import api from "@/services/api";

const loading = ref(true);
const router = useRouter();
const route = useRoute();

const verifyEmail = async () => {
  const token = route.params.token;

  try {
    const response = await api.post('/verify-email', { token });
    ElMessage({
      message: response.data.message,
      type: 'success',
      duration: 5000, 
    });
  } catch (error) {
    ElMessage({
      message: error.response?.data?.message || 'Erro na verificação. Tente novamente.',
      type: 'error',
      duration: 5000,
    });
  } finally {
    loading.value = false;
    router.push({ name: 'Login' }); 
  }
};

onMounted(() => {
  verifyEmail();
});
</script>
