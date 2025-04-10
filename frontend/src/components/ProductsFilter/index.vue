<template>
    <div class="filter bg-white shadow-lg rounded-[8px] p-3 w-[200px]">
      <el-slider
        v-model="value"
        range
        :min="0"
        :max="100"
        :step="1"
        @change="handleSliderChange"
      ></el-slider>
      <div>
        Valor selecionado: {{ value[0] }} - {{ value[1] }}
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        value: [20, 80], // Valores iniciais do range
      };
    },
    methods: {
      handleSliderChange(val) {
        console.log("Novo intervalo:", val);
      },
    },
  };
  </script>
  