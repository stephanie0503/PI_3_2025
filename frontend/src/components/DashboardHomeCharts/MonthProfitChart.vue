<template>
  <div class="p-4">
    <span class="text-[#5E5873] text-[14px] font-montserrat font-medium"
      >Ganhos</span
    >
    <div class="grid grid-cols-2 items-center">
      <div class="flex flex-col">
        <span class="text-[#5E5873] text-[13px] font-montserrat font-medium"
          >Esse Mês</span
        >
        <span class="text-[#5E5873] text-[14px] font-montserrat font-bold"
          >R$4055.56</span
        >
        <span class="text-[#5E5873] text-[12px] font-montserrat mt-2"
          >68,2% mais ganhos do que no mês passado.</span
        >
      </div>
      <div>
        <apexchart
          type="donut"
          :options="chartOptions"
          :series="chartSeries"
          height="200px"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const chartSeries = ref([16, 53, 31]);
const chartOptions = ref({
  chart: {
    type: "donut",
  },
  colors: ["#A9E9C5", "#4CAF50", "#D4F4E2"],
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: false,
  },
  tooltip: {
    enabled: true,
  },
  plotOptions: {
    pie: {
      expandOnClick: true,
      donut: {
        labels: {
          show: true,
          name: {
            show: true,
            fontSize: "12px",
            fontFamily: "Montserrat",
            color: "#5E5873",
            fontWeight: 400,
            offsetY: 16, // Mover o nome para baixo
            formatter: function (val) {
              return val; // Nome do segmento
            },
          },
          value: {
            show: true,
            fontSize: "16px",
            fontFamily: "Montserrat",
            color: "#5E5873",
            fontWeight: 400,
            offsetY: -10, // Mover a porcentagem para cima
            formatter: function (val) {
              return val + "%"; // Exibir porcentagem
            },
          },
        },
      },
    },
  },
  labels: ["CFTV", "Apps", "Outros"],
  responsive: [
    {
      breakpoint: 480,
      options: {
        chart: {
          width: 200,
        },
        legend: {
          position: "bottom",
        },
      },
    },
  ],
});
</script>
