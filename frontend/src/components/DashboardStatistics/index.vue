<template>
    <div class="py-4 px-6 h-full">
      <span>Estatísticas</span>
      <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:grid-cols-4 h-full">
        <div
          v-for="stat in stats"
          :key="stat.name"
          class="item-stats flex items-center gap-x-4"
        >
          <div :class="`rounded-full p-3 flex items-center ${stat.bgColor}`">
            <vue-feather :type="stat.icon" :stroke="stat.iconColor"></vue-feather>
          </div>
          <div class="flex flex-col">
            <span>{{ stat.value }}</span>
            <span>{{ stat.name }}</span>
          </div>
        </div>
      </div>
    </div>
  </template>
  

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";

const stats = ref([
  {
    icon: "trending-up",
    iconColor: "#857BF2",
    bgColor: "bg-[#EEECFE]",
    value: 0,
    name: "Vendas",
  },
  {
    icon: "user",
    iconColor: "#00CFE8",
    bgColor: "bg-[#E0F9FD]",
    value: 0,
    name: "Clientes",
  },
  {
    icon: "box",
    iconColor: "#EA5455",
    bgColor: "bg-[#FDEAEA]",
    value: 0, 
    name: "Produtos",
  },
  {
    icon: "dollar-sign",
    iconColor: "#28C76F",
    bgColor: "bg-[#E5F8ED]",
    value: 0,
    name: "Receita",
  },
]);

const fetchData = async () => {
  try {
    const response = await api.get("/dashboard");
    const data = response.data;
    stats.value[2].value = data.products_count || 0; 
    stats.value[1].value = data.clients_count || 0;
  } catch (error) {
    console.error(error); 
  }
};

onMounted(() => {
  fetchData();
});

</script>
