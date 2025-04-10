<template>
  <div class="flex w-full h-full revenueReportChart">
    <div class="report-chart__wrapper w-full h-full py-2">
      <apexchart
        type="bar"
        width="100%"
        height="100%"
        :series="series"
        :options="chartOptions"
      />
    </div>
    <div class="flex flex-col items-center py-10 px-4 w-[100%] sm:w-[50%] gap-y-8">
      <el-select v-model="yearValue" size="small" style="width: 40%">
        <el-option
          v-for="year in years"
          :key="year.value"
          :label="year.label"
          :value="year.value"
        />
      </el-select>
      <div
        class="flex flex-col items-center font-montserrat text-[#5E5873] font-[500]"
      >
        <span class="text-[23px]"> R$ 25,852 </span>
        <span class="text-[14px] font-[600]">
          Carteira:
          <span class="text-[#6E6B7B] font-[400]"> 56,800 </span>
        </span>
        <div class="w-full">
          <apexchart
            type="line"
            width="100%"
            height="100"
            :series="lineSeries"
            :options="lineChartOptions"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const currentYear = new Date().getFullYear();
const yearValue = ref(currentYear);
const years = ref([
  { label: currentYear - 2, value: currentYear - 2 },
  { label: currentYear - 1, value: currentYear - 1 },
  { label: currentYear, value: currentYear },
]);

const series = ref([
  {
    name: "Ganhos",
    data: [500, 600, 700, 800, 750, 900, 1100, 950, 1200, 1300, 1150, 1400],
  },
  {
    name: "Despesas",
    data: [
      -300, -400, -350, -450, -500, -550, -600, -700, -800, -750, -850, -900,
    ],
  },
]);

const chartOptions = ref({
  chart: {
    type: "bar",
    height: 440,
    stacked: true,
    inverted: true,
    toolbar: { show: false },
  },
  colors: ["#7367F0", "#FF9F43"],
  grid: {
    show: false,
  },
  plotOptions: {
    bar: {
      borderRadius: 5,
      horizontal: false,
      barHeight: "100%",
      columnWidth: "20%",
    },
  },
  dataLabels: {
    enabled: false,
  },
  tooltip: {
    shared: false,
    x: {
      formatter: (val) => val,
    },
    y: {
      formatter: (val) => Math.abs(val) + " $",
    },
  },
  title: {
    text: "Relatório de Receita",
    style: {
      fontSize: "17px",
      fontWeight: "500",
      fontFamily: "Montserrat",
      color: "#5E5873",
    },
  },
  xaxis: {
    categories: [
      "Jan",
      "Fev",
      "Mar",
      "Abr",
      "Mai",
      "Jun",
      "Jul",
      "Ago",
      "Set",
      "Out",
      "Nov",
      "Dez",
    ],
  },
});

// grafico de linha
const lineSeries = ref([
  {
    name: "Exemplo",
    data: [100, -50, 800, 200, 600, -100, 900, 300, 500, 50, 400, 650],
  },
]);

const lineChartOptions = ref({
  chart: {
    height: 100,
    toolbar: { show: false },
    zoom: { enabled: false },
  },
  stroke: {
    curve: "smooth",
    width: 2,
    colors: ["#7367F0"],
  },
  dataLabels: {
    enabled: false,
  },
  tooltip: {
    enabled: false,
  },
  grid: {
    show: false,
  },
  xaxis: {
    categories: [
      "Jan",
      "Fev",
      "Mar",
      "Abr",
      "Mai",
      "Jun",
      "Jul",
      "Ago",
      "Set",
      "Out",
      "Nov",
      "Dez",
    ],
    labels: {
      show: false,
    },
  },
  yaxis: {
    show: false,
  },
});
</script>

<style>
.revenueReportChart .report-chart__wrapper {
  border-right: 1px solid #ebe9f1;
}

.revenueReportChart .el-select__wrapper.is-focused {
  box-shadow: 0 0 0 1px var(--primary-color-indigo) inset;
}
</style>

<style scoped>
@media only screen and (max-width: 768px) {
  .revenueReportChart {
    flex-direction: column;
    row-gap: 10px;
  }
}
</style>
