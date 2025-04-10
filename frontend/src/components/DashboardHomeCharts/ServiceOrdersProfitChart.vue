<template>
  <div class="w-full relative p-2">
    <div class="flex flex-col pt-2">
      <span class="text-[#5E5873] text-[14px] font-montserrat font-medium"
        >Lucro</span
      >
      <span class="text-[#5E5873] text-[20px] font-montserrat font-semibold"
        >R$ 11.950</span
      >
    </div>
    <div class="h-full flex items-end pt-8 w-full">
      <apexchart
        type="line"
        :options="chartOptions"
        :series="chartSeries"
        height="60%"
        width="90%"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const chartSeries = ref([
  {
    name: "Lucro",
    data: [0, 20, 5, 30, 15, 45],
  },
]);

const chartOptions = ref({
  chart: {
    height: 350,
    type: "line",
    zoom: {
      enabled: false,
    },
    toolbar: { show: false },
    sparkline: {
      enabled: true,
    },
  },
  colors: ["#02CDE5"],
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: "straight",
    width: 2,
  },
  markers: {
    size: 5,
    colors: ["#02CDE5"],
    strokeColors: "#fff",
    strokeWidth: 2,
    hover: {
      size: 7,
    },
  },
  grid: {
    show: false,
  },
  xaxis: {
    categories: [
      "Maio",
      "Junho",
      "Julho",
      "Agosto",
      "Setembro",
      "Projeção",
      
    ],
    labels: {
      show: false,
    },
  },
  yaxis: {
    labels: {
      show: false,
    },
  },
  title: {
    text: "",
  },
  legend: {
    show: false,
  },
});
</script>
