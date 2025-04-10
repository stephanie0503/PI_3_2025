<template>
  <div class="flex flex-col gap-y-[20px] py-2">
    <div>
      <el-row :gutter="24" class="flex gap-y-[20px]">
        <el-col :md="8" :sm="24"><div class="grid-content bg-white h-[200px] w-[100%] rounded-[10px]"></div></el-col>
        <el-col :md="16" :sm="24">
          <div class="grid-content bg-white h-[200px] w-[100%] rounded-[10px]">
            <Statistics/>
          </div>
        </el-col>
      </el-row>
    </div>
    <div>
      <el-row :gutter="24">
        <el-col :md="8" :sm="24">
          <el-row :gutter="24">      
            <el-col :span="12">
              <div class="grid-content bg-white h-[100%] w-[100%] rounded-[10px] relative">
                <ServiceOrdersChart />
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-white h-[100%] w-[100%] rounded-[10px]">
                <ServiceOrdersProfitChart />
              </div>
            </el-col>
          </el-row>
          <el-row class="mt-[20px]">
            <el-col :span="24">
              <div class="grid-content bg-white h-[200px] w-[100%] rounded-[10px]">
                <MonthProfitChart />
              </div>
            </el-col>
          </el-row>       
        </el-col>
        <el-col :md="16" :sm="24"  class="mt-[20px] sm:mt-[0px]"><div class="grid-content bg-white h-[100%] w-[100%] rounded-[10px]">
          <RevenueReportChart/>
        </div>
      </el-col>
      </el-row>
    </div>
  </div>
</template>

<script setup>

import Statistics from '@/components/DashboardStatistics/index.vue'
import RevenueReportChart from '@/components/DashboardHomeCharts/RevenueReportChart.vue'
import ServiceOrdersChart from '@/components/DashboardHomeCharts/ServiceOrdersChart.vue';
import ServiceOrdersProfitChart from '@/components/DashboardHomeCharts/ServiceOrdersProfitChart.vue';
import MonthProfitChart from '@/components/DashboardHomeCharts/MonthProfitChart.vue';

</script>
