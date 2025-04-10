<template>
  <el-scrollbar max-height="320px">
    <el-list>
      <el-list-item v-for="email in filteredEmails" :key="email.id" class="media">
        <div
          class="list-item px-3 py-4 border-b-[1px] bg-white border-[#EBE9F1] items-center cursor-pointer"
          :class="{ 'seen': email.flags.seen === 'Seen' }"
          @click="handleEmailClick(email)"
        >
          <div class="flex items-center gap-x-[6px]">
            <div class="avatar">
              <el-avatar :size="40" :src="email.avatar" @error="errorHandler">
                <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
              </el-avatar>
            </div>
            <div>
              <div class="mail-items">
                <h5 class="mb-25 text-[#5E5873] font-montserrat text-[14px] font-medium">
                  {{ formatSender(email.sender) }}
                </h5>
                <span :class="{ 'text-[#6E6B7B] font-normal': email.flags.seen === 'Seen' }"
                  class="text-truncate text-[#00000] font-montserrat text-[14px] font-semibold">
                  {{ email.subject }}
                </span>
              </div>
            </div>
            <div class="ml-auto flex items-center gap-x-1">
              <el-icon v-if="email.attachments.length > 0" color="#6E6B7B">
                <Paperclip />
              </el-icon>
              <span class="mx-50 bullet" :class="email.importance"></span>
              <span class="text-[#B9B9C3] font-montserrat text-[11px] font-normal">
                {{ email.date }}
              </span>
            </div>
          </div>
          <div class="flex items-center pl-1 text-[#B9B9C3] text-[15px] gap-x-[3px]">
            <el-checkbox></el-checkbox>
            <el-icon size="17px">
              <Star />
            </el-icon>
            <div class="mail-message">
              <!-- Exibindo a mensagem como HTML, caso necessário -->
               <!-- <p class="text-truncate mb-0" v-html="email.message"></p> -->
            </div>
          </div>
        </div>
      </el-list-item>
    </el-list>
  </el-scrollbar>
</template>

<script lang="ts" setup>
import { computed} from 'vue';
import { Paperclip, Star } from '@element-plus/icons-vue';

interface Email {
  id: number;
  avatar: string;
  sender: string;
  subject: string;
  date: string;
  message: string;
  isFavorite: boolean;
  importance: string;
  attachments: [];
  flags: {
    seen?: string;
  };
}

const props = defineProps<{
  emails: Email[];
  searchQuery: string;
}>();

const emit = defineEmits<{
  (e: 'email-selected', email: Email): void;
}>();

const errorHandler = (event: Event) => {
  const target = event.target as HTMLImageElement;
  target.src = 'https://via.placeholder.com/150'; // URL para a imagem padrão
};

const filteredEmails = computed(() => {
  return props.emails.filter((email) =>
    email.sender.toLowerCase().includes(props.searchQuery.toLowerCase())
  );
});

const formatSender = (sender: string): string => {
  const indexOfAngleBracket = sender.indexOf('<');
  if (indexOfAngleBracket !== -1) {
    return sender.slice(0, indexOfAngleBracket).trim();
  }
  return sender; // Caso não encontre o '<', retorna o sender inteiro
};

const handleEmailClick = (email: Email) => {
  emit('email-selected', email);
};

</script>


<style scoped>
.el-list-item {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border-bottom: 1px solid #eaeaea;
}

.list-item {
  position: relative;
  top: 0;
  transition: all 0.5s ease;
}

.list-item:hover {
  top: -3px;
}

.el-list-item:hover {
  background-color: #f7f7f7;
}

.seen {
  background-color: #F8F8F8;
}
</style>
