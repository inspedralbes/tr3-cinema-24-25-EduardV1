import { defineStore } from 'pinia';

export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    purchases: []
  }),

  actions: {
    addPurchase(purchase) {
      this.purchases.push(purchase);
      // Also store in localStorage for persistence
      this.savePurchases();
    },

    getPurchasesByEmail(email) {
      return this.purchases.filter(p => p.userInfo.email.toLowerCase() === email.toLowerCase());
    },

    savePurchases() {
      localStorage.setItem('movieTicketPurchases', JSON.stringify(this.purchases));
    },

    loadPurchases() {
      const stored = localStorage.getItem('movieTicketPurchases');
      if (stored) {
        this.purchases = JSON.parse(stored);
      }
    }
  }
});