// Notifications alert messages : success/error (Pinia)
import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notifications', {
  state: () => ({
    message: '',
    type: '', //success/error
    show: false
  }),
  
  actions: {
    setSuccess(message) {
      this.message = message;
      this.type = 'success';
      this.show = true;
      setTimeout(() => {
        this.clear();
      }, 5000);
    },
    
    setError(message) {
      this.message = message;
      this.type = 'error';
      this.show = true;
      setTimeout(() => {
        this.clear();
      }, 8000);
    },
    
    clear() {
      this.message = '';
      this.type = '';
      this.show = false;
    }
  }
});