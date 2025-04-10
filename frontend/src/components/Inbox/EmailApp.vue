<template>
  <div class="email-app-list">
    <!-- Barra de Pesquisa -->
    <div v-if="!selectedEmail">
      <div class="app-fixed-search d-flex align-items-center">
        <div class="sidebar-toggle d-block d-lg-none ml-1 hidden">
          <el-button class="btn p-0">
            <el-icon>
              <Menu />
            </el-icon>
          </el-button>
        </div>
        <div class="d-flex align-content-center justify-content-between w-100">
          <el-input
            class="input-group"
            placeholder="Pesquisar email"
            :prefix-icon="Search"
            v-model="searchQuery"
          ></el-input>
        </div>
      </div>
      <!-- Ação de Seleção -->
      <div class="app-action">
        <div class="action-left bg-white h-[50px] flex items-center pl-3.5">
          <el-checkbox
            class="select-all"
            v-model="selectAll"
            @change="toggleSelectAll"
          >
            Select All
          </el-checkbox>
        </div>
      </div>
      <!-- Lista de E-mails -->
      <EmailList
        :emails="emails"
        :searchQuery="searchQuery"
        @email-selected="handleEmailSelect"
      />
    </div>
    <!-- Exibir E-mail Selecionado -->
    <div v-if="selectedEmail" class="email-detail">
      <EmailDetail :email="selectedEmail" @go-back="handleGoBack" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { Menu, Search } from "@element-plus/icons-vue";
import EmailList from "@/components/Inbox/EmailList.vue";
import EmailDetail from "@/components/Inbox/EmailDetail.vue";
import api from "@/services/api";
import { useEmailStore } from "@/stores/emailStore";

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

const emails = ref<Email[]>([]);
const searchQuery = ref("");
const selectAll = ref(false);
const selectedEmails = ref<number[]>([]);
const selectedEmail = ref<Email | null>(null);

const fetchUserEmails = async () => {
  const emailStore = useEmailStore();

  try {
    const response = await api.get("/emails");
    emails.value = response.data.map((email: any) => ({
      id: email.id,
      avatar: email.avatar || "https://example.com/default-avatar.png",
      sender: email.from || "Desconhecido",
      subject: email.subject || "Sem Assunto",
      date: email.date ? new Date(email.date).toLocaleString() : "",
      message: email.body.html || "Mensagem sem conteúdo",
      isFavorite: false,
      importance: "normal",
      attachments: email.attachments || [],
      flags: email.flags || {},
    }));

    const unseenCount = emails.value.filter(
      (email) => !email.flags.seen
    ).length;

    emailStore.setInboxCount(unseenCount);
  } catch (error) {
    console.error("Erro ao buscar emails do usuário:", error);
  }
};

onMounted(() => {
  fetchUserEmails();
});

const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedEmails.value = emails.value.map((email) => email.id);
  } else {
    selectedEmails.value = [];
  }
};

const handleEmailSelect = (email: Email) => {
  selectedEmail.value = email;
};

const handleGoBack = () => {
  selectedEmail.value = null;
};
</script>

<style>
.email-app-list .el-input__inner {
  flex: 1;
  width: 100%;
  --el-input-inner-height: 50px;
}
</style>

<style scoped>
.email-app-list {
  width: 100%;
}

.app-action {
  border-bottom: 00.01px solid rgba(170, 167, 167, 0.301);
}

.el-input .el-input__prefix {
  color: #b9b9c3;
}
</style>
