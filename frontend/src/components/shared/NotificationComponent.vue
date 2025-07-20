<script>
import { useNotificationStore } from '../../stores/notifications.js';

  export default {
    name: 'NotificationComponent',
     setup() {
      const notificationStore = useNotificationStore();

      return {
        notificationStore,
      };  
    },
  };
</script>

<!-- Notification Component success/error alert-->
<template>
  <div class="message fixed left-1/2 transform -translate-x-1/2 z-50 w-full max-w-lg p-6">
    <div
      v-if="notificationStore.show"
      class="p-4 rounded-2xl shadow-lg mb-4 flex items-center gap-4 transition-all duration-600"
      :class="{
        'bg-[--light-green-bg] border border-[--dark-green] text-[--black]': notificationStore.type === 'success',
        'bg-[--red-bg] border border-[--dark-green] text-[--black]': notificationStore.type === 'error'
      }"
    >
      <span class="flex-1 font-medium">{{ notificationStore.message }}</span>
      <!-- Close button -->
      <button @click="notificationStore.clear()" 
        class=" hover:opacity-100 ml-4 p-1 rounded-full transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
  </div>
</template>