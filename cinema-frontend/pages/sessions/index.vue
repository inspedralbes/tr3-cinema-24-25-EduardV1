<template>
    <div>
      <h1 class="text-3xl font-bold mb-6">Properes sessions</h1>
      
      <div class="mb-8">
        <div class="flex flex-wrap gap-2 mb-4">
          <button 
            v-for="date in availableDates" 
            :key="date"
            @click="selectedDate = date"
            :class="[
              'px-4 py-2 rounded-md',
              selectedDate === date ? 'bg-primary text-white' : 'bg-gray-200 hover:bg-gray-300'
            ]"
          >
            {{ formatDate(date) }}
          </button>
        </div>
      </div>
      
      <div v-if="filteredSessions.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="session in filteredSessions" :key="session.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <img :src="session.movie.poster" :alt="session.movie.title" class="w-full h-64 object-cover">
          <div class="p-4">
            <h2 class="text-xl font-bold mb-2">{{ session.movie.title }}</h2>
            <p class="text-gray-600 mb-2">{{ formatDate(session.date) }} at {{ session.time }}</p>
            <p v-if="session.isSpecialDay" class="text-accent font-semibold mb-2">Dia Especial</p>
            <p class="mb-4 line-clamp-3">{{ session.movie.plot }}</p>
            <div class="flex justify-between items-center">
              <div>
                <span class="font-semibold">Preu: </span>
                <span>{{ session.isSpecialDay ? '€4' : '€6' }} (Normal)</span>
                <br>
                <span>{{ session.isSpecialDay ? '€6' : '€8' }} (VIP)</span>
              </div>
              <NuxtLink :to="`/sessions/${session.id}`" class="btn btn-primary">Detalls</NuxtLink>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="bg-gray-100 p-6 rounded-lg text-center">
        <p class="text-lg">No hi ha sessions disponibles per a la data seleccionada.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  // Mock data for sessions
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
        plot: 'Un lladre que roba secrets corporatius mitjançant la tecnologia de compartició de somnis rep la tasca inversa de plantar una idea a la ment d’un director executiu.',
        poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'
      }
    },
    {
      id: 2,
      date: '2024-05-02',
      time: '16:00',
      isSpecialDay: true,
      movie: {
        title: 'Cadena perpètua',
        director: 'Frank Darabont',
        year: '1994',
        plot: 'Dos homes empresonats creen un vincle durant molts anys, trobant consol i eventual redempció a través d’actes de decència comuna.',
        poster: 'https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg'
      }
    },
    {
      id: 3,
      date: '2024-05-03',
      time: '20:00',
      isSpecialDay: false,
      movie: {
        title: 'El cavaller fosc',
        director: 'Christopher Nolan',
        year: '2008',
        plot: 'Quan l’amenaça coneguda com el Joker causa estralls i caos entre la gent de Gotham, Batman ha d’afrontar una de les proves psicològiques i físiques més grans per combatre la injustícia.',
        poster: 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg'
      }
    }
  ]);
  
  // Get unique dates from sessions
  const availableDates = computed(() => {
    return [...new Set(sessions.value.map(session => session.date))];
  });
  
  // Default selected date to the first available date
  const selectedDate = ref(availableDates.value[0] || '');
  
  // Filter sessions by selected date
  const filteredSessions = computed(() => {
    return sessions.value.filter(session => session.date === selectedDate.value);
  });
  
  // Format date function
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  </script>