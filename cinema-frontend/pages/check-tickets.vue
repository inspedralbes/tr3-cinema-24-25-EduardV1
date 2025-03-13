<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Comprova les teves entrades</h1>
    
    <div v-if="!userEmail" class="bg-white p-6 rounded-lg shadow-md mb-8">
      <h2 class="text-xl font-bold mb-4">Introdueix el teu correu electrònic</h2>
      <form @submit.prevent="checkTickets" class="flex flex-col md:flex-row gap-4">
        <input 
          type="email" 
          v-model="emailInput" 
          placeholder="El teu correu electrònic" 
          required
          class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
        >
        <button type="submit" class="btn btn-primary">Comprova Entrades</button>
      </form>
    </div>
    
    <div v-else>
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Entrades per a {{ userEmail }}</h2>
        <button @click="resetSearch" class="text-primary hover:underline">Cerca amb un altre correu</button>
      </div>
      
      <div v-if="userTickets.length > 0">
        <div v-for="purchase in userTickets" :key="purchase.id" class="bg-white p-6 rounded-lg shadow-md mb-6">
          <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/4">
              <img :src="purchase.session.movie.poster" :alt="purchase.session.movie.title" class="w-full rounded-lg shadow-md">
            </div>
            <div class="md:w-3/4">
              <h3 class="text-xl font-bold mb-2">{{ purchase.session.movie.title }}</h3>
              <p class="mb-2"><span class="font-semibold">Data:</span> {{ formatDate(purchase.session.date) }}</p>
              <p class="mb-2"><span class="font-semibold">Hora:</span> {{ purchase.session.time }}</p>
              <p class="mb-4"><span class="font-semibold">Seients:</span> {{ formatSeats(purchase.seats) }}</p>
              
              <div class="bg-gray-100 p-4 rounded-md mb-4">
                <h4 class="font-semibold mb-2">Detalls de les entrades</h4>
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="text-left py-2">Seient</th>
                      <th class="text-left py-2">Tipus</th>
                      <th class="text-right py-2">Preu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="seat in purchase.seats" :key="seat.id" class="border-b">
                      <td class="py-2">{{ seat.id }}</td>
                      <td class="py-2">{{ seat.isVip ? 'VIP' : 'Normal' }}</td>
                      <td class="text-right py-2">{{ seat.price }}€</td>
                    </tr>
                    <tr class="font-semibold">
                      <td class="py-2" colspan="2">Total</td>
                      <td class="text-right py-2">{{ calculateTotal(purchase.seats) }}€</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="flex justify-end">
                <button @click="downloadTickets(purchase)" class="btn btn-primary">Descarregar Entrades</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="bg-gray-100 p-6 rounded-lg text-center">
        <p class="text-lg">No s'han trobat entrades per a aquest correu electrònic.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useFormatDate } from '~/composables/useFormatDate';
import { useTicketsStore } from '~/stores/tickets';

const { formatDate } = useFormatDate();
const ticketsStore = useTicketsStore();
const emailInput = ref('');
const userEmail = ref('');
const userTickets = ref([]);

// Load stored purchases when component mounts
onMounted(() => {
  ticketsStore.loadPurchases();
});

// Check tickets function
const checkTickets = () => {
  userEmail.value = emailInput.value;
  userTickets.value = ticketsStore.getPurchasesByEmail(emailInput.value);
};

// Reset search
const resetSearch = () => {
  userEmail.value = '';
  emailInput.value = '';
  userTickets.value = [];
};

// Format seats for display
const formatSeats = (seats) => {
  return seats.map(seat => seat.id).join(', ');
};

// Calculate total price
const calculateTotal = (seats) => {
  return seats.reduce((total, seat) => total + seat.price, 0);
};

// Download tickets function
const downloadTickets = (purchase) => {
  alert('Aquesta funcionalitat estaria disponible en una versió real de l\'aplicació');
};
</script>