<template>
  <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Filtres -->
    <div class="mb-8 bg-black text-gray-300 rounded-lg shadow p-6">
      <h2 class="text-3xl font-bold text-white mb-6">Filtra les pel·lícules</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div v-for="(label, key) in filterOptions" :key="key">
          <label :for="key" class="block text-sm font-medium text-gray-400 mb-2">{{ label.title }}</label>
          <select v-model="filters[key]" :id="key" class="w-full bg-gray-800 text-white rounded-md border border-gray-600 focus:border-red-600 focus:ring-red-600">
            <option v-for="option in label.options" :key="option.value" :value="option.value">{{ option.text }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Graella de Pel·lícules -->
    <div v-if="loading" class="text-center text-white">Carregant...</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="movie in movies" :key="movie.id" class="bg-gray-900 text-white rounded-lg shadow-lg overflow-hidden">
        <div class="relative">
          <img :src="movie.poster_url" :alt="movie.title" class="w-full h-96 object-cover" />
          <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded">{{ movie.rating }}</div>
        </div>
        <div class="p-6">
          <h3 class="text-2xl font-bold mb-4">{{ movie.title }}</h3>
          <p class="text-gray-400 mb-4">{{ movie.description }}</p>
          <button @click="goToMovie(movie.id)" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition">Reservar Entrades</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const filters = ref({
  genre: '',
  rating: '',
  showTime: '',
  sortBy: 'popularity'
});

const filterOptions = {
  genre: { title: 'Gènere', options: [
    { value: '', text: 'Tots els gèneres' },
    { value: 'action', text: 'Acció' },
    { value: 'comedy', text: 'Comèdia' },
    { value: 'drama', text: 'Drama' },
    { value: 'horror', text: 'Horror' },
    { value: 'sci-fi', text: 'Ciència Ficció' }
  ]},
  rating: { title: 'Classificació', options: [
    { value: '', text: 'Totes les classificacions' },
    { value: 'G', text: 'G' },
    { value: 'PG', text: 'PG' },
    { value: 'PG-13', text: 'PG-13' },
    { value: 'R', text: 'R' }
  ]},
  showTime: { title: 'Horari', options: [
    { value: '', text: 'Tots els horaris' },
    { value: 'morning', text: 'Matí' },
    { value: 'afternoon', text: 'Tarda' },
    { value: 'evening', text: 'Vespre' },
    { value: 'night', text: 'Nit' }
  ]},
  sortBy: { title: 'Ordena per', options: [
    { value: 'popularity', text: 'Popularitat' },
    { value: 'releaseDate', text: 'Data d\'estrena' },
    { value: 'alphabetical', text: 'Alfabètic' },
    { value: 'rating', text: 'Classificació' }
  ]}
};

const movies = ref([]);
const loading = ref(true);

const fetchMovies = async () => {
  try {
    const res = await fetch("http://localhost:8000/api/movies");
    if (!res.ok) throw new Error("Error en carregar les pel·lícules");
    movies.value = await res.json();
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const goToMovie = (id) => {
  router.push(`/movie/${id}`);
};

onMounted(fetchMovies);
</script>
