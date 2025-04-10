<template>
  <el-config-provider :locale="ptBr">
    <RouterView />
  </el-config-provider>
</template>

<script setup>
import { ElConfigProvider } from 'element-plus'
import ptBr from 'element-plus/es/locale/lang/pt-br'

// Importando a localização do Day.js
import 'dayjs/locale/pt-br'
import dayjs from 'dayjs'

// Configurando o Day.js para usar o idioma
dayjs.locale('pt-br')
</script>
