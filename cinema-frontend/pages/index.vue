<template>
  <div class="max-w-6xl mx-auto">
    <section class="mb-8">
      <h1 class="text-3xl font-bold mb-4">Benvinguts a MoviieTick</h1>
      <p class="text-base mb-3">Gaudeix de la màgia del cinema amb les nostres projeccions diàries.</p>
      <NuxtLink to="/sessions" class="btn btn-primary">Veure Sessions Properes</NuxtLink>
    </section>

    <section v-if="todaySession" class="bg-gray-100 p-4 rounded-lg mb-8">
      <h2 class="text-xl font-bold mb-3">Pel·lícula d'avui</h2>
      <div class="flex gap-4">
        <div class="w-32">
          <img :src="todaySession.movie.poster" :alt="todaySession.movie.title" class="w-full rounded-lg shadow-sm">
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-bold mb-1">{{ todaySession.movie.title }}</h3>
          <p class="mb-1 text-sm"><span class="font-semibold">Hora:</span> {{ formatTime(todaySession.time) }}</p>
          <p class="mb-1 text-sm"><span class="font-semibold">Director:</span> {{ todaySession.movie.director }}</p>
          <p class="mb-2 text-sm line-clamp-2">{{ todaySession.movie.plot }}</p>
          <NuxtLink :to="`/sessions/${todaySession.id}`" class="btn btn-primary text-sm">Comprar Entrades</NuxtLink>
        </div>
      </div>
    </section>

    <section class="mb-8">
      <h2 class="text-xl font-bold mb-3">Com funciona?</h2>
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <div class="text-primary text-2xl mb-2">1</div>
          <h3 class="text-base font-bold mb-1">Selecciona una Sessió</h3>
          <p class="text-sm">Tria la pel·lícula que vols veure.</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <div class="text-primary text-2xl mb-2">2</div>
          <h3 class="text-base font-bold mb-1">Tria els teus Seients</h3>
          <p class="text-sm">Selecciona fins a 10 seients.</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <div class="text-primary text-2xl mb-2">3</div>
          <h3 class="text-base font-bold mb-1">Confirma la Compra</h3>
          <p class="text-sm">Introdueix les dades i confirma.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { useFormatDate } from '~/composables/useFormatDate';

const { formatTime } = useFormatDate();

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
</script>