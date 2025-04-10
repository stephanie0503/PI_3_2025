<template>
  <div class="w-full relative py-4">
    <div class="flex flex-col px-4">
      <span class="text-[#5E5873] text-[14px] font-montserrat font-medium"
        >Ordens</span
      >
      <span class="text-[#5E5873] text-[23px] font-montserrat font-semibold"
        >195</span
      >
    </div>
    <div class="h-full">
      <apexchart
        type="bar"
        :options="chartOptions"
        :series="chartSeries"
        height="80%"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const chartSeries = ref([
  {
    name: "Ordens",
    data: [30, 40, 55, 50, 60],
  },
]);

const chartOptions = ref({
  chart: {
    type: "bar",
    height: 60,
    toolbar: { show: false },
    sparkline: {
      enabled: true,
    },
  },
  colors: ["#FF9F43"],
  plotOptions: {
    bar: {
      columnWidth: "20%",
      dataLabels: {
        position: "top",
      },
    },
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: ["Maio", "Junho", "Julho", "Agosto", "Setembro"],
    position: "top",
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
    },
    tooltip: {
      enabled: false,
    },
  },
  yaxis: {
    labels: {
      show: false,
    },
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
  },
  grid: {
    show: false,
  },
  title: {
    text: "",
  },
  legend: {
    show: false,
  },
});
</script>
