<template>
    <div>
      <section class="mb-12">
        <h1 class="text-4xl font-bold mb-6">Benvingut al Cinema Pedralbes</h1>
        <p class="text-lg mb-4">Experimenta la màgia del cinema amb les nsotrees projeccions diàries.</p>
        <NuxtLink to="/sessions" class="btn btn-primary">Veure sessions pròximes</NuxtLink>
      </section>
  
      <section v-if="todaySession" class="bg-gray-100 p-6 rounded-lg mb-12">
        <h2 class="text-2xl font-bold mb-4">Pel·lícula d'avui</h2>
        <div class="flex flex-col md:flex-row gap-6">
          <div class="md:w-1/4">
            <img :src="todaySession.movie.poster" :alt="todaySession.movie.title" class="w-full rounded-lg shadow-md">
          </div>
          <div class="md:w-3/4">
            <h3 class="text-xl font-bold mb-2">{{ todaySession.movie.title }}</h3>
            <p class="mb-2"><span class="font-semibold">Hora:</span> {{ formatTime(todaySession.time) }}</p>
            <p class="mb-2"><span class="font-semibold">Director:</span> {{ todaySession.movie.director }}</p>
            <p class="mb-2"><span class="font-semibold">Any:</span> {{ todaySession.movie.year }}</p>
            <p class="mb-4">{{ todaySession.movie.plot }}</p>
            <NuxtLink :to="`/sessions/${todaySession.id}`" class="btn btn-primary">Comprar entrades</NuxtLink>
          </div>
        </div>
      </section>
  
      <section class="mb-12">
        <h2 class="text-2xl font-bold mb-4">Com funciona</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">1</div>
            <h3 class="text-xl font-bold mb-2">Seleciona una sessió</h3>
            <p>Consulta les sessions pròxims i tria la pel·lícula que vols veure.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">2</div>
            <h3 class="text-xl font-bold mb-2">Tria els teus seients</h3>
            <p>Seleccions fins a 10 seients al nostre mapa interactiu.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">3</div>
            <h3 class="text-xl font-bold mb-2">Confirma la compra</h3>
            <p>Introdueix les teves dades i confirma la compra de les entrades.</p>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  // Mock data for today's session
  const todaySession = ref({
    id: 1,
    date: new Date().toISOString().split('T')[0],
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
  
  // Format time function
  const formatTime = (time) => {
    return time;
  };
  </script>