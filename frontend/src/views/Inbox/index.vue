<template>
  <div class="flex">
    <div class="side-nav">
      <SideNav></SideNav>
    </div>
    <div class="email-list">
      <EmailList></EmailList>
    </div>
  </div>
</template>

<script setup>
import SideNav from "@/components/Inbox/SideNav.vue";
import EmailList from "@/components/Inbox/EmailApp.vue";
</script>

<style>

.side-nav {
  width: 260px;
  background: #f0f0f0;
  border: 00.01px solid  rgba(170, 167, 167, 0.301);
  border-right: none;
}

.email-list {
  flex: 1;
  height: 70vh;
  border: 00.01px solid  rgba(170, 167, 167, 0.301);
  border-top: none;
}

.email-list::-webkit-scrollbar {
    display: none;
} 
</style>
