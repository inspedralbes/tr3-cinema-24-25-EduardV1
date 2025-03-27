<template>
  <div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Pel·lícula del Dia -->
    <div v-if="featuredMovie" class="bg-gray-800 rounded-lg p-6 mb-6">
      <h1 class="text-xl font-bold text-white mb-3">Pel·lícula del Dia</h1>
      <div
        class="grid grid-cols-1 sm:grid-cols-[180px_auto] gap-6 items-center"
      >
        <img
          :src="featuredMovie.poster_url"
          :alt="featuredMovie.title"
          class="rounded-lg w-full h-48 object-cover"
        />
        <div class="text-white text-sm">
          <h2 class="text-lg font-bold mb-2">{{ featuredMovie.title }}</h2>
          <p class="mb-2 line-clamp-4">{{ featuredMovie.description }}</p>
          <div class="space-y-1">
            <p>
              <span class="font-bold">Durada:</span>
              {{ featuredMovie.duration }} min
            </p>
            <p>
              <span class="font-bold">Classificació:</span>
              {{ featuredMovie.rating }}
            </p>
            <p>
              <span class="font-bold">Director:</span>
              {{ featuredMovie.director }}
            </p>
            <p>
              <span class="font-bold">Horari:</span> {{ featuredMovie.time }}
            </p>
            <div class="flex items-center text-yellow-400">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                />
              </svg>
              <span class="ml-1">{{ featuredMovie.score }}/10</span>
            </div>
          </div>
          <button
            @click="goToMovie(featuredMovie.id)"
            class="mt-3 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors text-sm"
          >
            Reservar Entrades
          </button>
        </div>
      </div>
    </div>
    <!-- Cartellera -->
    <h2 class="text-2xl font-bold mb-4">En Cartellera</h2>
    <div v-if="loading" class="text-center">Carregant...</div>
    <div v-else-if="error" class="text-center text-red-500">
      Error en carregar les pel·lícules
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div
        v-for="movie in movies"
        :key="movie.id"
        class="bg-white rounded-lg shadow-md overflow-hidden"
      >
        <img
          :src="movie.poster_url"
          :alt="movie.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-4">
          <h3 class="text-lg font-bold mb-2">{{ movie.title }}</h3>
          <p class="text-gray-600 mb-3 text-sm">{{ movie.description }}</p>
          <button
            @click="goToMovie(movie.id)"
            class="w-full bg-red-600 text-white px-3 py-1.5 rounded-md hover:bg-red-700 transition-colors text-sm"
          >
            Reservar Entrades
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

// Variables per les pel·lícules
const movies = ref([]);
const featuredMovie = ref(null);
const loading = ref(true);
const error = ref(false);

// Funció per obtenir les pel·lícules del backend
const fetchMovies = async () => {
  try {
    const res = await fetch(
      "http://moviietickback.daw.inspedralbes.cat/public/api/movies"
    );
    if (!res.ok) throw new Error("Error en carregar les pel·lícules");
    const data = await res.json();
    movies.value = data;
    featuredMovie.value = data[0] || null;
  } catch (err) {
    error.value = true;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchMovies);

const goToMovie = (id) => {
  router.push(`/movie/${id}`);
};
</script>
