<template>
  <div class="max-w-full mx-auto bg-black text-white min-h-screen">
    <!-- Pel·lícula del Dia -->
    <div v-if="featuredMovie" class="relative mb-12">
      <img :src="featuredMovie.poster_url" :alt="featuredMovie.title" class="w-full h-[60vh] object-cover brightness-75" />
      <div class="absolute bottom-16 left-12 max-w-xl">
        <h1 class="text-5xl font-extrabold mb-4">{{ featuredMovie.title }}</h1>
        <p class="mb-4 text-lg line-clamp-3">{{ featuredMovie.description }}</p>
        <div class="flex gap-6 mb-6">
          <p><span class="font-semibold">Durada:</span> {{ featuredMovie.duration }} min</p>
          <p><span class="font-semibold">Classificació:</span> {{ featuredMovie.rating }}</p>
        </div>
        <button @click="goToMovie(featuredMovie.id)" class="bg-red-600 text-white px-6 py-3 rounded-lg text-lg hover:bg-red-700 transition-all">
          Reservar Entrades
        </button>
      </div>
    </div>

    <!-- Cartellera -->
    <h2 class="text-3xl font-bold mb-8 px-8">En Cartellera</h2>
    <div v-if="loading" class="text-center text-lg">Carregant...</div>
    <div v-else-if="error" class="text-center text-red-500">Error en carregar les pel·lícules</div>
    <div v-else class="overflow-x-scroll whitespace-nowrap scrollbar-hide px-8">
      <div v-for="movie in movies" :key="movie.id" class="inline-block w-64 mr-4 cursor-pointer hover:scale-105 transition-transform">
        <img :src="movie.poster_url" :alt="movie.title" class="rounded-lg w-full h-96 object-cover" />
        <h3 class="text-lg font-bold mt-2">{{ movie.title }}</h3>
        <button @click="goToMovie(featuredMovie.id)" class="bg-red-600 text-white px-6 py-3 rounded-lg text-lg hover:bg-red-700 transition-all">
            Comprar Entrades
          </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const movies = ref([]);
const featuredMovie = ref(null);
const loading = ref(true);
const error = ref(false);

const fetchMovies = async () => {
  try {
    const res = await fetch("http://localhost:8000/api/movies");
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

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
