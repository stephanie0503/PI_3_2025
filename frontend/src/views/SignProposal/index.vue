<template>
     <SignaturePdf :data="proposalData" />
     
  </template>
  
  <script setup>
  import { onMounted, ref } from "vue";
  import { useRoute } from "vue-router";
  import api from "@/services/api";
  import SignaturePdf from "@/components/SignaturePdf/index.vue";
  
  // Utilizamos useRoute para acessar os parâmetros da rota
  const route = useRoute();
  const token = route.params.token;
  
  // Dados da proposta e mensagem de erro
  const proposalData = ref(null);  // Mudado para `null` para iniciar como vazio
  const errorMessage = ref("");
  
  // Função que chama a API para obter os dados da proposta
  const handleGetData = async () => {
    try {
      const response = await api.get(`/proposals/signature/${token}`);
      proposalData.value = response.data;  // Preenche os dados da proposta
    } catch (error) {
      console.error("Erro ao obter os dados da proposta", error);

    }
  };
  
  // Carregar os dados assim que o componente for montado
  onMounted(() => {
    handleGetData();
  });
  </script>
  
  <style scoped>
  /* Estilos para melhorar a exibição */
  h1 {
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  p {
    font-size: 16px;
    color: #333;
  }
  
  .signature-pdf-container {
    margin-top: 20px;
  }
  
  .error-message {
    color: red;
    font-weight: bold;
  }
  </style>
  