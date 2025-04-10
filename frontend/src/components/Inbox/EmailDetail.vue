<template>
    <div v-if="email" class="email-detail bg-white shadow-md rounded-lg overflow-hidden">
      <!-- Header -->
      <div class="email-detail-header p-4 flex justify-between items-center border-b">
        <div class="flex items-center">
          <button @click="goBack" class="btn btn-icon text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          <h4 class="ml-2 text-lg font-semibold">{{ email.subject }}</h4>
        </div>
        <div class="flex items-center space-x-2">
          <button class="btn btn-icon text-gray-600 hover:text-gray-800">
            <i class="fa fa-star-o"></i>
          </button>
          <el-dropdown>
            <el-button class="dropdown-button" type="text">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
              </svg>
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>Draft</el-dropdown-item>
              <el-dropdown-item>Spam</el-dropdown-item>
              <el-dropdown-item>Trash</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <el-dropdown>
            <el-button class="dropdown-button" type="text">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              </svg>
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>Personal</el-dropdown-item>
              <el-dropdown-item>Company</el-dropdown-item>
              <el-dropdown-item>Important</el-dropdown-item>
              <el-dropdown-item>Private</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <button class="btn btn-icon text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
          </button>
        </div>
      </div>
  
      <!-- Email Content -->
      <div class="email-body p-4 flex items-center justify-center px-8 relative">
        <div class=email-body__container>
            <div class="mb-4 flex">
              <span class="badge badge-light-{{ email.label }}">{{ email.label }}</span>
            </div>
            <div class="email-meta mb-4 flex justify-between bg-white px-4">
              <div>
                <span class="font-semibold">De:</span> {{ email.from }}
              </div>
              <div>
                <span>{{ email.date }}</span> 
              </div>
            </div>
            <el-divider></el-divider>
            <div v-html="email.message" class="email-content"></div>
        </div>
      </div>
  
      <!-- Email Attachments -->
      <div v-if="email.attachments.length" class="email-attachments p-4 border-t">
        <h5 class="text-lg font-semibold mb-2">Attachments</h5>
        <ul>
          <li v-for="attachment in email.attachments" :key="attachment.name" class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
            </svg>
            <a :href="attachment.url" class="text-blue-600 hover:underline">{{ attachment.name }}</a> 
            <span class="text-gray-600 ml-2">({{ attachment.size }})</span>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      email: {
        type: Object,
        required: true
      }
    },
    methods: {
      goBack() {
        this.$emit('go-back');
      }
    }
  }
  </script>
  
  <style scoped>
  .email-detail {
    max-height: calc(100vh - 100px);
    overflow-y: auto;
  }
  .email-detail-header {
    background-color: #ffffff;
    width: 100%;
  }
  .email-body {
    overflow-y: auto;
    background-color: #F8F8F8;
  }

  .email-body__container {
    height: 100%;
    background-color: white;
    border-radius: 6px;
    width: 100%;
    margin-top: 60px;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  }

  .email-attachments {
    background-color: #f8f9fa;
  }
  </style>
  