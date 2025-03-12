<template>
  <div v-if="session" class="max-w-6xl mx-auto">
    <div class="mb-4">
      <NuxtLink to="/sessions" class="text-primary hover:underline text-sm mb-2 inline-block">
        &larr; Tornar a Sessions
      </NuxtLink>
      <h1 class="text-2xl font-bold mb-1">{{ session.movie.title }}</h1>
      <p class="text-gray-600 text-sm">{{ formatDate(session.date) }} - {{ session.time }}</p>
    </div>

    <div class="grid grid-cols-12 gap-4 mb-6">
      <!-- Movie poster and details -->
      <div class="col-span-3">
        <img :src="session.movie.poster" :alt="session.movie.title" class="w-full h-48 object-cover rounded-lg shadow-sm mb-3">
        <div class="bg-white p-3 rounded-lg shadow-sm">
          <h2 class="text-base font-bold mb-2">Detalls de la Pel·lícula</h2>
          <p class="text-sm mb-1"><span class="font-semibold">Director:</span> {{ session.movie.director }}</p>
          <p class="text-sm mb-1"><span class="font-semibold">Any:</span> {{ session.movie.year }}</p>
          <p class="text-sm mb-2"><span class="font-semibold">Sinopsi:</span> {{ session.movie.plot }}</p>
          <div class="mt-2 bg-gray-50 p-2 rounded text-sm">
            <p class="font-semibold mb-1">Preus:</p>
            <p>Normal: {{ session.isSpecialDay ? '4€' : '6€' }}</p>
            <p>VIP (Fila F): {{ session.isSpecialDay ? '6€' : '8€' }}</p>
          </div>
        </div>
      </div>

      <!-- Seat selection -->
      <div class="col-span-9">
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <h2 class="text-lg font-bold mb-3">Selecciona els Seients</h2>
          
          <div class="mb-4">
            <div class="w-full bg-gray-800 text-white text-center py-1 mb-4 rounded text-sm">PANTALLA</div>
            
            <div class="flex justify-center mb-4">
              <div class="flex flex-col items-center">
                <div v-for="(row, rowIndex) in seatMap" :key="rowIndex" class="flex mb-1">
                  <div class="w-5 flex items-center justify-center font-bold mr-1 text-sm">
                    {{ String.fromCharCode(65 + rowIndex) }}
                  </div>
                  <div v-for="(seat, seatIndex) in row" :key="`${rowIndex}-${seatIndex}`" 
                       @click="toggleSeat(rowIndex, seatIndex)"
                       :class="[
                         'seat w-8 h-8 text-sm',
                         seat === 0 ? 'seat-available' : (seat === 1 ? 'seat-occupied' : 'seat-selected'),
                         rowIndex === 5 ? 'seat-vip' : ''
                       ]">
                    {{ seatIndex + 1 }}
                  </div>
                </div>
              </div>
            </div>
            
            <div class="flex justify-center space-x-4 mb-3 text-sm">
              <div class="flex items-center">
                <div class="seat-available seat w-4 h-4 mr-1"></div>
                <span>Lliure</span>
              </div>
              <div class="flex items-center">
                <div class="seat-occupied seat w-4 h-4 mr-1"></div>
                <span>Ocupat</span>
              </div>
              <div class="flex items-center">
                <div class="seat-selected seat w-4 h-4 mr-1"></div>
                <span>Seleccionat</span>
              </div>
              <div class="flex items-center">
                <div class="seat-available seat seat-vip w-4 h-4 mr-1"></div>
                <span>VIP</span>
              </div>
            </div>
            
            <div class="text-center text-sm">
              <p class="mb-1">Seients seleccionats: {{ selectedSeats.length }}/10</p>
              <p class="font-semibold">Total: {{ calculateTotal() }}€</p>
            </div>
          </div>
          
          <div v-if="selectedSeats.length > 0">
            <h3 class="text-base font-bold mb-3">Les teves dades</h3>
            <form @submit.prevent="submitPurchase" class="space-y-3">
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="name" class="block text-sm mb-1">Nom</label>
                  <input 
                    type="text" 
                    id="name" 
                    v-model="userInfo.name" 
                    required
                    class="w-full px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                  >
                </div>
                <div>
                  <label for="surname" class="block text-sm mb-1">Cognoms</label>
                  <input 
                    type="text" 
                    id="surname" 
                    v-model="userInfo.surname" 
                    required
                    class="w-full px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                  >
                </div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="email" class="block text-sm mb-1">Correu electrònic</label>
                  <input 
                    type="email" 
                    id="email" 
                    v-model="userInfo.email" 
                    required
                    class="w-full px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                  >
                </div>
                <div>
                  <label for="phone" class="block text-sm mb-1">Telèfon</label>
                  <input 
                    type="tel" 
                    id="phone" 
                    v-model="userInfo.phone" 
                    required
                    class="w-full px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                  >
                </div>
              </div>
              <div class="flex justify-end">
                <button type="submit" class="btn btn-primary text-sm">Confirmar Compra</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-8">
    <p class="text-lg">Carregant informació de la sessió...</p>
  </div>
</template>

<script setup>
const route = useRoute();
const router = useRouter();
const sessionId = parseInt(route.params.id);

const session = ref({
  id: sessionId,
  date: '2024-05-01',
  time: '18:00',
  isSpecialDay: false,
  movie: {
    title: 'Inception',
    director: 'Christopher Nolan',
    year: '2010',
    plot: 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
    poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'
  }
});

const seatMap = ref(Array(12).fill().map(() => Array(10).fill(0)));
const selectedSeats = ref([]);
const userInfo = ref({
  name: '',
  surname: '',
  email: '',
  phone: ''
});

onMounted(() => {
  for (let i = 0; i < 20; i++) {
    const row = Math.floor(Math.random() * 12);
    const seat = Math.floor(Math.random() * 10);
    if (seatMap.value[row][seat] === 0) {
      seatMap.value[row][seat] = 1;
    }
  }
});

const toggleSeat = (rowIndex, seatIndex) => {
  if (seatMap.value[rowIndex][seatIndex] === 1) return;
  
  if (seatMap.value[rowIndex][seatIndex] === 2) {
    seatMap.value[rowIndex][seatIndex] = 0;
    const seatId = `${String.fromCharCode(65 + rowIndex)}${seatIndex + 1}`;
    selectedSeats.value = selectedSeats.value.filter(seat => seat.id !== seatId);
    return;
  }
  
  if (selectedSeats.value.length >= 10) {
    alert('Pots seleccionar un màxim de 10 seients per sessió.');
    return;
  }
  
  seatMap.value[rowIndex][seatIndex] = 2;
  const seatId = `${String.fromCharCode(65 + rowIndex)}${seatIndex + 1}`;
  const isVip = rowIndex === 5;
  const price = isVip 
    ? (session.value.isSpecialDay ? 6 : 8) 
    : (session.value.isSpecialDay ? 4 : 6);
  
  selectedSeats.value.push({
    id: seatId,
    row: String.fromCharCode(65 + rowIndex),
    number: seatIndex + 1,
    isVip,
    price
  });
};

const calculateTotal = () => {
  return selectedSeats.value.reduce((total, seat) => total + seat.price, 0);
};

const submitPurchase = () => {
  const purchaseData = {
    sessionId: session.value.id,
    session: {
      id: session.value.id,
      date: session.value.date,
      time: session.value.time,
      movie: session.value.movie
    },
    seats: selectedSeats.value,
    userInfo: userInfo.value,
    total: calculateTotal()
  };
  
  // Store purchase data in localStorage for the confirmation page
  localStorage.setItem('lastPurchase', JSON.stringify(purchaseData));
  
  // Generate a mock purchase ID and redirect to confirmation page
  const purchaseId = Math.random().toString(36).substr(2, 9);
  router.push(`/confirmation/${purchaseId}`);
};

const formatDate = (dateString) => {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('ca-ES', options);
};
</script>

<style scoped>
.seat {
  @apply m-0.5 rounded flex items-center justify-center cursor-pointer transition-colors duration-200;
}

.seat-available {
  @apply bg-gray-200 hover:bg-gray-300;
}

.seat-occupied {
  @apply bg-red-500 text-white cursor-not-allowed;
}

.seat-selected {
  @apply bg-green-500 text-white;
}

.seat-vip {
  @apply border border-yellow-500;
}
</style>