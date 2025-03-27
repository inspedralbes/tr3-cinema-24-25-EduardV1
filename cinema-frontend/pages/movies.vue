<template>
  <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Filtres -->
    <div class="mb-8 bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-4">Filtra les pel·lícules</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Gènere</label
          >
          <select
            v-model="filters.genre"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
          >
            <option value="">Tots els gèneres</option>
            <option value="action">Acció</option>
            <option value="comedy">Comèdia</option>
            <option value="drama">Drama</option>
            <option value="horror">Horror</option>
            <option value="sci-fi">Ciència Ficció</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Classificació</label
          >
          <select
            v-model="filters.rating"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
          >
            <option value="">Totes les classificacions</option>
            <option value="G">G</option>
            <option value="PG">PG</option>
            <option value="PG-13">PG-13</option>
            <option value="R">R</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Horari</label
          >
          <select
            v-model="filters.showTime"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
          >
            <option value="">Tots els horaris</option>
            <option value="morning">Matí</option>
            <option value="afternoon">Tarda</option>
            <option value="evening">Vespre</option>
            <option value="night">Nit</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Ordena per</label
          >
          <select
            v-model="filters.sortBy"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
          >
            <option value="popularity">Popularitat</option>
            <option value="releaseDate">Data d'estrena</option>
            <option value="alphabetical">Alfabètic</option>
            <option value="rating">Classificació</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Graella de Pel·lícules -->
    <div v-if="loading" class="text-center">Carregant...</div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div
        v-for="movie in movies"
        :key="movie.id"
        class="bg-white rounded-lg shadow-lg overflow-hidden"
      >
        <div class="relative">
          <img
            :src="movie.poster_url"
            :alt="movie.title"
            class="w-full h-96 object-cover"
          />
          <div
            class="absolute top-4 right-4 bg-red-600 text-white px-2 py-1 rounded"
          >
            {{ movie.rating }}
          </div>
        </div>
        <div class="p-6">
          <h3 class="text-2xl font-bold mb-2">{{ movie.title }}</h3>
          <div class="flex items-center mb-4">
            <div class="flex items-center text-yellow-400">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                />
              </svg>
              <span class="ml-1">{{ movie.score }}/10</span>
            </div>
            <span class="mx-2">•</span>
            <span>{{ movie.duration }} Minuts</span>
            <span class="mx-2">•</span>
            <span>{{ movie.genre }}</span>
          </div>
          <p class="text-gray-600 mb-4">{{ movie.description }}</p>
          <div class="space-y-3">
            <div class="flex items-center">
              <span class="text-gray-700 font-semibold w-24">Director:</span>
              <span>{{ movie.director }}</span>
            </div>
            <div class="flex items-center">
              <span class="text-gray-700 font-semibold w-24"
                >Repartiment:
              </span>
              <span>{{ movie.cast }}</span>
            </div>
          </div>
          <div class="mt-6 space-y-3">
            <div class="grid grid-cols-3 gap-2">
              <span class="text-gray-700 font-semibold w-24">Horari:</span>
              <span>{{ movie.time }}</span>
            </div>
          </div>
          <br />
          <button
            @click="goToMovie(movie.id)"
            class="w-full bg-red-600 text-white px-3 py-1.5 rounded-md hover:bg-red-700 transition-colors text-sm"
          >
            Reservar Entrades
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Reserva -->
    <div
      v-if="showBookingModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-2xl font-bold mb-4">
          Reserva entrades per a {{ selectedMovie?.title }}
        </h3>
        <form @submit.prevent="submitBooking" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom</label>
            <input
              type="text"
              v-model="booking.name"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Correu electrònic</label
            >
            <input
              type="email"
              v-model="booking.email"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Horari</label
            >
            <select
              v-model="booking.showTime"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
            >
              <option
                v-for="time in selectedMovie?.showTimes"
                :key="time"
                :value="time"
              >
                {{ time }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Nombre d'entrades</label
            >
            <input
              type="number"
              v-model="booking.tickets"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
            />
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="showBookingModal = false"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Cancel·la
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
            >
              Confirma la Reserva
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal d'Èxit -->
    <div
      v-if="showSuccessModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-lg max-w-md w-full p-6 text-center">
        <div class="mb-4 text-green-500">
          <svg
            class="mx-auto h-12 w-12"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            />
          </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2">Reserva Confirmada!</h3>
        <p class="text-gray-600 mb-4">
          Gràcies per reservar entrades per a {{ selectedMovie?.title }}. Hem
          enviat la confirmació al teu correu electrònic.
        </p>
        <button
          @click="showSuccessModal = false"
          class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
        >
          Tanca
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const filters = ref({
  genre: "",
  rating: "",
  showTime: "",
  sortBy: "popularity",
});

const showBookingModal = ref(false);
const showSuccessModal = ref(false);
const selectedMovie = ref(null);

const booking = ref({
  name: "",
  email: "",
  showTime: "",
  tickets: 1,
});

// Obtenir les pel·lícules des del backend
const movies = ref([]);
const loading = ref(true);
const error = ref(false);

const fetchMovies = async () => {
  try {
    const res = await fetch(
      "http://moviietickback.daw.inspedralbes.cat/public/api/movies"
    );
    if (!res.ok) throw new Error("Error en carregar les pel·lícules");
    const data = await res.json();
    movies.value = data;
  } catch (err) {
    error.value = true;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

fetchMovies();

// Funcions per obrir el modal de reserva
const openBookingModal = (movie, showTime = null) => {
  selectedMovie.value = movie;
  showBookingModal.value = true;
  if (showTime) {
    booking.value.showTime = showTime;
  }
};

const submitBooking = async () => {
  try {
    const res = await fetch(
      "http://moviietickback.daw.inspedralbes.cat/public/api/tickets",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          // Ajusta els camps segons el model del teu backend
          movie_id: selectedMovie.value ? selectedMovie.value.id : null,
          showTime: booking.value.showTime,
          tickets: booking.value.tickets,
          customer: {
            name: booking.value.name,
            email: booking.value.email,
          },
        }),
      }
    );
    if (!res.ok) throw new Error("Error en reservar");
    showBookingModal.value = false;
    showSuccessModal.value = true;
    booking.value = { name: "", email: "", showTime: "", tickets: 1 };
  } catch (err) {
    console.error(err);
    alert("No s'ha pogut processar la reserva.");
  }
};
onMounted(fetchMovies);
const goToMovie = (id) => {
  router.push(`/movie/${id}`);
};
</script>
