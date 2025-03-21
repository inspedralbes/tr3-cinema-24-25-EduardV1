<template>
  <div class="max-w-5xl mx-auto px-4 py-6">
    <div v-if="loading" class="text-center">Carregant...</div>
    <div v-else-if="error" class="text-center text-red-500">Error en carregar la pel·lícula</div>
    <div v-else-if="movie" class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="md:flex">
        <div class="md:w-1/3">
          <img :src="movie.poster_url" :alt="movie.title" class="w-full h-full object-cover" />
        </div>
        <div class="p-6 md:w-2/3">
          <h1 class="text-2xl font-bold mb-3">{{ movie.title }}</h1>
          <p class="text-gray-600 mb-4">{{ movie.description }}</p>
          <div class="space-y-2 mb-6">
            <p><span class="font-semibold">Durada:</span> {{ movie.duration }} min</p>
            <p><span class="font-semibold">Classificació:</span> {{ movie.rating }}</p>
            <p><span class="font-semibold">Director:</span> {{ movie.director }}</p>
            <p><span class="font-semibold">Puntuació:</span> {{ movie.score }}/10</p>
            <p><span class="font-semibold">Horari:</span> {{ movie.time }}</p>
            <p><span class="font-semibold">Trailer:</span> <a :href="movie.trailer_url" target="_blank"
                class="text-blue-600 hover:underline">Veure Trailer</a></p>
          </div>
          <button @click="showBookingModal = true"
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
            Reservar Entrades
          </button>
        </div>
      </div>
    </div>

    <!-- Booking Modal -->
    <div v-if="showBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-4 max-w-md w-full">
        <h2 class="text-lg font-bold mb-4">Reservar Entrades</h2>

        <!-- Selecció de Seients -->
        <div class="mb-4">
          <h3 class="text-sm font-semibold mb-2">Selecciona els seients</h3>
          <div class="flex items-center space-x-3 text-xs mb-3">
            <div class="flex items-center">
              <div class="w-4 h-4 bg-gray-200 rounded mr-1"></div>
              <span>Disponible</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-red-500 rounded mr-1"></div>
              <span>Seleccionat</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-gray-500 rounded mr-1"></div>
              <span>Ocupat</span>
            </div>
          </div>

          <div class="w-full bg-gray-800 text-white text-center py-1 rounded mb-4 text-xs">
            Pantalla
          </div>
          <div class="max-w-lg mx-auto">
            <div class="grid grid-cols-11 gap-1">
              <template v-for="row in rows" :key="row">
                <div class="w-5 flex items-center justify-center text-xs font-medium">
                  {{ row }}
                </div>
                <template v-for="seat in 10" :key="`${row}${seat}`">
                  <button :class="[
                    'w-5 h-5 rounded transition-colors text-xs font-medium',
                    getSeatClass(`${row}${seat}`),
                  ]" @click="toggleSeat(`${row}${seat}`)" :title="`${row}${seat}`"
                    :disabled="isSeatOccupied(`${row}${seat}`)">
                    {{ row }}{{ seat }}
                  </button>
                </template>
              </template>
            </div>
          </div>

          <!-- <div class="max-w-lg mx-auto">
          <div class="grid grid-cols-11 gap-1 mb-1">
            <div class="w-5"></div>
            <div v-for="col in 10" :key="col" class="w-5 text-center text-xs font-medium">
              {{ col }}
            </div>
          </div>
          <div class="grid grid-cols-11 gap-1">
            <template v-for="row in rows" :key="row">
              <div class="w-5 flex items-center justify-center text-xs font-medium">
                {{ row }}
              </div>
              <template v-for="seat in 10" :key="`${row}${seat}`">
                <button 
                  :class="[ 'w-5 h-5 rounded transition-colors text-xs font-medium', selectedSeats.includes(`${row}${seat}`) ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-red-400' ]"
                  @click="toggleSeat(`${row}${seat}`)"
                  :title="`${row}${seat}`"
                >
                  {{ row }}{{ seat }}
                </button>
              </template>
            </template>
          </div>
        </div> -->
        </div>

        <div class="text-right mb-3">
          <p class="text-xs font-semibold">Seients seleccionats: {{ selectedSeats.join(', ') }}</p>
          <p class="text-xs font-semibold">Total: {{ selectedSeats.length * 12 }}€</p>
        </div>

        <!-- Formulari de Detalls del Client -->
        <form @submit.prevent="submitBooking" class="space-y-3">
          <div>
            <label class="block text-xs font-medium text-gray-700">Nom</label>
            <input type="text" v-model="booking.name" required
              class="mt-1 block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700">Correu electrònic</label>
            <input type="email" v-model="booking.email" required
              class="mt-1 block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="showBookingModal = false"
              class="px-3 py-1 text-xs border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
              Cancel·lar
            </button>
            <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded-md hover:bg-red-700">
              Confirma la Reserva
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full text-center">
        <div class="text-green-500 text-4xl mb-3">✓</div>
        <h2 class="text-xl font-bold mb-2">Reserva Confirmada!</h2>
        <p class="text-gray-600 mb-4 text-sm">La teva reserva s'ha realitzat correctament. Rebràs un correu de
          confirmació.
        </p>
        <div class="mb-4 text-sm">
          <h3 class="font-semibold mb-2">Detalls de la reserva:</h3>
          <p>Seients seleccionats: {{ selectedSeats.join(', ') }}</p>
          <p>Import total: {{ selectedSeats.length * 12 }}€</p>
        </div>
        <button @click="showSuccessModal = false"
          class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">
          Tancar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const movieId = route.params.id;

const movie = ref(null);
const loading = ref(true);
const error = ref(false);
const showBookingModal = ref(false);
const showSuccessModal = ref(false);
const selectedSeats = ref([]);
const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
const booking = ref({ name: '', email: '' });
const tickets = ref([]);
const getSeatClass = (seatId) => {
  const ticket = tickets.value.find(ticket => ticket.position === seatId);
  if (!ticket) return 'bg-gray-200'; // Seient no trobat
  return ticket.available ? 'bg-gray-200 hover:bg-red-400' : 'bg-gray-500'; // Disponible o ocupat
};

const isSeatOccupied = (seatId) => {
  const ticket = tickets.value.find(ticket => ticket.position === seatId);
  return ticket && !ticket.available; // Retorna true si està ocupat
};

const fetchMovie = async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/movies/${movieId}`);
    if (!res.ok) throw new Error('Error en carregar la pel·lícula');
    movie.value = await res.json();
  } catch (err) {
    error.value = true;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const fetchTickets = async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/movie_sessions/${movieId}/tickets`);
    if (!res.ok) throw new Error('Error en carregar les entrades');
    tickets.value = await res.json();
  } catch (err) {
    console.error(err);
  }
};

onMounted(() => {
  fetchMovie();
  fetchTickets();
});


onMounted(fetchMovie);

const toggleSeat = (seatId) => {
  const index = selectedSeats.value.indexOf(seatId);
  if (index === -1) {
    selectedSeats.value.push(seatId);
  } else {
    selectedSeats.value.splice(index, 1);
  }
};

const submitBooking = async () => {
  try {
    const res = await fetch('http://localhost:8000/api/tickets', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        movie_id: movieId,
        seats: selectedSeats.value,
        customer: booking.value,
      }),
    });
    if (!res.ok) throw new Error('Error en processar la reserva');
    showBookingModal.value = false;
    showSuccessModal.value = true;
    selectedSeats.value = [];
    booking.value = { name: '', email: '' };
  } catch (err) {
    console.error(err);
    alert("No s'ha pogut processar la reserva.");
  }
};
</script>