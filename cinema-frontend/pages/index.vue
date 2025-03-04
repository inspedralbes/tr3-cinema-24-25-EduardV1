<template>
    <div>
      <section class="mb-12">
        <h1 class="text-4xl font-bold mb-6">Welcome to Cinema Pedralbes</h1>
        <p class="text-lg mb-4">Experience the magic of cinema with our daily film screenings.</p>
        <NuxtLink to="/sessions" class="btn btn-primary">View Upcoming Sessions</NuxtLink>
      </section>
  
      <section v-if="todaySession" class="bg-gray-100 p-6 rounded-lg mb-12">
        <h2 class="text-2xl font-bold mb-4">Today's Film</h2>
        <div class="flex flex-col md:flex-row gap-6">
          <div class="md:w-1/4">
            <img :src="todaySession.movie.poster" :alt="todaySession.movie.title" class="w-full rounded-lg shadow-md">
          </div>
          <div class="md:w-3/4">
            <h3 class="text-xl font-bold mb-2">{{ todaySession.movie.title }}</h3>
            <p class="mb-2"><span class="font-semibold">Time:</span> {{ formatTime(todaySession.time) }}</p>
            <p class="mb-2"><span class="font-semibold">Director:</span> {{ todaySession.movie.director }}</p>
            <p class="mb-2"><span class="font-semibold">Year:</span> {{ todaySession.movie.year }}</p>
            <p class="mb-4">{{ todaySession.movie.plot }}</p>
            <NuxtLink :to="`/sessions/${todaySession.id}`" class="btn btn-primary">Buy Tickets</NuxtLink>
          </div>
        </div>
      </section>
  
      <section class="mb-12">
        <h2 class="text-2xl font-bold mb-4">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">1</div>
            <h3 class="text-xl font-bold mb-2">Select a Session</h3>
            <p>Browse our upcoming sessions and choose the film you want to watch.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">2</div>
            <h3 class="text-xl font-bold mb-2">Choose Your Seats</h3>
            <p>Select up to 10 seats from our interactive seating map.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-primary text-4xl mb-4">3</div>
            <h3 class="text-xl font-bold mb-2">Confirm Purchase</h3>
            <p>Enter your details and confirm your ticket purchase.</p>
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