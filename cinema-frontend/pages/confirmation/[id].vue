<template>
    <div class="max-w-2xl mx-auto py-8">
      <div v-if="purchase" class="bg-white rounded-lg shadow-sm p-6">
        <!-- Success Header -->
        <div class="text-center mb-6">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Compra Confirmada!</h1>
          <p class="text-gray-600">Gràcies per la teva compra. Hem enviat els detalls al teu correu electrònic.</p>
        </div>
  
        <!-- Order Details -->
        <div class="border-t border-b border-gray-200 py-4 mb-6">
          <div class="flex items-start gap-4">
            <img :src="purchase.session.movie.poster" :alt="purchase.session.movie.title" 
                 class="w-24 h-36 object-cover rounded-md shadow-sm">
            <div>
              <h2 class="text-lg font-bold mb-1">{{ purchase.session.movie.title }}</h2>
              <p class="text-sm text-gray-600 mb-2">
                {{ formatDate(purchase.session.date) }} - {{ purchase.session.time }}
              </p>
              <div class="space-y-1 text-sm">
                <p><span class="font-medium">Nom:</span> {{ purchase.userInfo.name }} {{ purchase.userInfo.surname }}</p>
                <p><span class="font-medium">Email:</span> {{ purchase.userInfo.email }}</p>
                <p><span class="font-medium">Telèfon:</span> {{ purchase.userInfo.phone }}</p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Tickets -->
        <div class="mb-6">
          <h3 class="text-lg font-bold mb-3">Les teves entrades</h3>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="space-y-2">
              <div v-for="seat in purchase.seats" :key="seat.id" 
                   class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                <div>
                  <span class="font-medium">Seient {{ seat.id }}</span>
                  <span v-if="seat.isVip" class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">
                    VIP
                  </span>
                </div>
                <span>{{ seat.price }}€</span>
              </div>
            </div>
            <div class="flex justify-between items-center pt-4 mt-2 border-t border-gray-300 font-bold">
              <span>Total</span>
              <span>{{ calculateTotal(purchase.seats) }}€</span>
            </div>
          </div>
        </div>
  
        <!-- Actions -->
        <div class="flex justify-between items-center">
          <NuxtLink to="/sessions" class="text-primary hover:underline text-sm">
            Tornar a Sessions
          </NuxtLink>
          <button class="btn btn-primary" @click="downloadTickets">
            Descarregar Entrades
          </button>
        </div>
      </div>
  
      <div v-else class="text-center">
        <p class="text-lg text-gray-600">No s'ha trobat la informació de la compra.</p>
        <NuxtLink to="/sessions" class="text-primary hover:underline block mt-4">
          Tornar a Sessions
        </NuxtLink>
      </div>
    </div>
  </template>
  
  <script setup>
  const route = useRoute();
  const router = useRouter();
  
  // Get purchase data from localStorage
  const purchase = ref(null);
  
  onMounted(() => {
    const purchaseData = localStorage.getItem('lastPurchase');
    if (purchaseData) {
      purchase.value = JSON.parse(purchaseData);
      // Clear the purchase data from localStorage
      localStorage.removeItem('lastPurchase');
    }
  });
  
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ca-ES', options);
  };
  
  const calculateTotal = (seats) => {
    return seats.reduce((total, seat) => total + seat.price, 0);
  };
  
  const downloadTickets = () => {
    // In a real app, this would generate and download a PDF with the tickets
    alert('Aquesta funcionalitat estaria disponible en una versió real de l\'aplicació');
  };
  </script>