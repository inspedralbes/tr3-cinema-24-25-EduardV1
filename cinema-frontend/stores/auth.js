import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAdmin: false,
    adminPassword: 'admin123' // In a real app, this would be handled securely on the server
  }),
  
  actions: {
    login(password) {
      if (password === this.adminPassword) {
        this.isAdmin = true;
        return true;
      }
      return false;
    },
    
    logout() {
      this.isAdmin = false;
    },
    
    checkAuth() {
      return this.isAdmin;
    }
  }
});