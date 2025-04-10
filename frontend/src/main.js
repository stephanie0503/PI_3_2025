import '@/assets/css/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import '@/assets/css/tailwind.css'

import 'animate.css';
import SlideUpDown from 'vue-slide-up-down'

import VueFeather from 'vue-feather';

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'

import VueApexCharts from "vue3-apexcharts";

//import Vue3Signature from "vue3-signature"

const app = createApp(App)
app.use(createPinia())
app.use(router)

app.component('slide-up-down', SlideUpDown)
app.component(VueFeather.name, VueFeather);

app.use(ElementPlus)
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
}

app.use(VueApexCharts);
//app.use(Vue3Signature);

app.mount('#app')
