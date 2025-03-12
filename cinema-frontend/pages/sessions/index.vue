<template>
  <div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Sessions Properes</h1>
    
    <div class="mb-6">
      <div class="flex flex-wrap gap-2 mb-3">
        <button 
          v-for="date in availableDates" 
          :key="date"
          @click="selectedDate = date"
          :class="[
            'px-3 py-1 rounded-md text-sm',
            selectedDate === date ? 'bg-primary text-white' : 'bg-gray-200 hover:bg-gray-300'
          ]"
        >
          {{ formatDate(date) }}
        </button>
      </div>
    </div>
    
    <div v-if="filteredSessions.length > 0" class="grid grid-cols-3 gap-4">
      <div v-for="session in filteredSessions" :key="session.id" class="bg-white rounded-lg shadow-sm overflow-hidden">
        <img :src="session.movie.poster" :alt="session.movie.title" class="w-full h-40 object-cover">
        <div class="p-3">
          <h2 class="text-base font-bold mb-1">{{ session.movie.title }}</h2>
          <p class="text-sm text-gray-600 mb-1">{{ formatDate(session.date) }} - {{ session.time }}</p>
          <p v-if="session.isSpecialDay" class="text-accent text-sm font-semibold mb-1">Dia Especial!</p>
          <p class="text-sm mb-2 line-clamp-2">{{ session.movie.plot }}</p>
          <div class="flex justify-between items-center">
            <div class="text-sm">
              <p>Normal: {{ session.isSpecialDay ? '4€' : '6€' }}</p>
              <p>VIP: {{ session.isSpecialDay ? '6€' : '8€' }}</p>
            </div>
            <NuxtLink :to="`/sessions/${session.id}`" class="btn btn-primary text-sm">Detalls</NuxtLink>
          </div>
        </div>
      </div>
    </div>
    
    <div v-else class="bg-gray-100 p-4 rounded-lg text-center">
      <p>No hi ha sessions disponibles per a la data seleccionada.</p>
    </div>
  </div>
</template>

<script setup>
import { useFormatDate } from '~/composables/useFormatDate';

const { formatDate } = useFormatDate();

const sessions = ref([
  {
    id: 1,
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
  },
  {
    id: 2,
    date: '2024-05-02',
    time: '16:00',
    isSpecialDay: true,
    movie: {
      title: 'The Shawshank Redemption',
      director: 'Frank Darabont',
      year: '1994',
      plot: 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
      poster: 'https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg'
    }
  },
  {
    id: 3,
    date: '2024-05-03',
    time: '20:00',
    isSpecialDay: false,
    movie: {
      title: 'The Dark Knight',
      director: 'Christopher Nolan',
      year: '2008',
      plot: 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
      poster: 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg'
    }
  }
]);

const availableDates = computed(() => {
  return [...new Set(sessions.value.map(session => session.date))];
});

const selectedDate = ref(availableDates.value[0] || '');

const filteredSessions = computed(() => {
  return sessions.value.filter(session => session.date === selectedDate.value);
});
</script>