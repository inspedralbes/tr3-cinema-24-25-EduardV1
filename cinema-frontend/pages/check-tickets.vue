<template>
    <div>
      <h1 class="text-3xl font-bold mb-6">Comprova les teves entrades</h1>
      
      <div v-if="!userEmail" class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Introdueix el teu correu electrònic</h2>
        <form @submit.prevent="checkTickets" class="flex flex-col md:flex-row gap-4">
          <input 
            type="email" 
            v-model="emailInput" 
            placeholder="El teu coorreu electrònic" 
            required
            class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
          >
          <button type="submit" class="btn btn-primary">Comprovar entrades</button>
        </form>
      </div>
      
      <div v-else>
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold">Entrades per a {{ userEmail }}</h2>
          <button @click="resetSearch" class="text-primary hover:underline">Cercar amb un altre correu</button>
        </div>
        
        <div v-if="userTickets.length > 0">
          <div v-for="(sessionTickets, index) in userTickets" :key="index" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex flex-col md:flex-row gap-6">
              <div class="md:w-1/4">
                <img :src="sessionTickets.session.movie.poster" :alt="sessionTickets.session.movie.title" class="w-full rounded-lg shadow-md">
              </div>
              <div class="md:w-3/4">
                <h3 class="text-xl font-bold mb-2">{{ sessionTickets.session.movie.title }}</h3>
                <p class="mb-2"><span class="font-semibold">Data:</span> {{ formatDate(sessionTickets.session.date) }}</p>
                <p class="mb-2"><span class="font-semibold">Hora:</span> {{ sessionTickets.session.time }}</p>
                <p class="mb-4"><span class="font-semibold">Seients:</span> {{ formatSeats(sessionTickets.tickets) }}</p>
                
                <div class="bg-gray-100 p-4 rounded-md mb-4">
                  <h4 class="font-semibold mb-2">Detalls de l'entrada</h4>
                  <table class="w-full">
                    <thead>
                      <tr class="border-b">
                        <th class="text-left py-2">Seient</th>
                        <th class="text-left py-2">Tipus</th>
                        <th class="text-right py-2">Preu</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="ticket in sessionTickets.tickets" :key="ticket.id" class="border-b">
                        <td class="py-2">{{ ticket.id }}</td>
                        <td class="py-2">{{ ticket.isVip ? 'VIP' : 'Normal' }}</td>
                        <td class="text-right py-2">€{{ ticket.price }}</td>
                      </tr>
                      <tr class="font-semibold">
                        <td class="py-2" colspan="2">Total</td>
                        <td class="text-right py-2">€{{ calculateTotal(sessionTickets.tickets) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="flex justify-end">
                  <button class="btn btn-primary">Descarregar entrades</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="bg-gray-100 p-6 rounded-lg text-center">
          <p class="text-lg">No s'han trobat entrades per a aquest correu electrònic.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  const emailInput = ref('');
  const userEmail = ref('');
  const userTickets = ref([]);
  
  // Check tickets function
  const checkTickets = () => {
    userEmail.value = emailInput.value;
    
    // Mock data - in a real app, this would fetch from the server
    if (emailInput.value === 'user@example.com') {
      userTickets.value = [
        {
          session: {
            id: 1,
            date: '2024-05-01',
            time: '18:00',
            isSpecialDay: false,
            movie: {
              title: 'Inception',
              director: 'Christopher Nolan',
              year: '2010',
              plot: 'Un lladre que roba secrets corporatius mitjançant la tecnologia de compartir somnis rep la tasca inversa de plantar una idea a la ment d’un director executiu.',
              poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'
            }
          },
          tickets: [
            { id: 'A1', row: 'A', number: 1, isVip: false, price: 6 },
            { id: 'A2', row: 'A', number: 2, isVip: false, price: 6 },
            { id: 'F5', row: 'F', number: 5, isVip: true, price: 8 }
          ]
        }
      ];
    } else {
      userTickets.value = [];
    }
  };
  
  // Reset search
  const resetSearch = () => {
    userEmail.value = '';
    emailInput.value = '';
    userTickets.value = [];
  };
  
  // Format seats for display
  const formatSeats = (tickets) => {
    return tickets.map(ticket => ticket.id).join(', ');
  };
  
  // Calculate total price
  const calculateTotal = (tickets) => {
    return tickets.reduce((total, ticket) => total + ticket.price, 0);
  };
  
  // Format date function
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  </script>