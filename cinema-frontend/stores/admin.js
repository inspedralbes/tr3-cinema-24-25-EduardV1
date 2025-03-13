import { defineStore } from 'pinia';

export const useAdminStore = defineStore('admin', {
  state: () => ({
    isAdmin: false
  }),
  
  actions: {
    login(password) {
      // Simple admin authentication with hardcoded password
      if (password === 'admin123') {
        this.isAdmin = true;
        return true;
      }
      return false;
    },
    
/*************  ✨ Codeium Command ⭐  *************/
/******  fe55a824-93ff-4836-a08c-6d550fd96fea  *******/
    logout() {
      this.isAdmin = false;
    }
  }
});