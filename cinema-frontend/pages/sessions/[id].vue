<template>
    <div v-if="session">
      <div class="mb-8">
        <NuxtLink to="/sessions" class="text-primary hover:underline mb-4 inline-block">
          &larr; Back to Sessions
        </NuxtLink>
        <h1 class="text-3xl font-bold mb-2">{{ session.movie.title }}</h1>
        <p class="text-gray-600 mb-6">{{ formatDate(session.date) }} at {{ session.time }}</p>
      </div>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div>
          <img :src="session.movie.poster" :alt="session.movie.title" class="w-full rounded-lg shadow-md">
        </div>
        <div class="md:col-span-2">
          <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Movie Details</h2>
            <p class="mb-2"><span class="font-semibold">Director:</span> {{ session.movie.director }}</p>
            <p class="mb-2"><span class="font-semibold">Year:</span> {{ session.movie.year }}</p>
            <p class="mb-2"><span class="font-semibold">Plot:</span> {{ session.movie.plot }}</p>
            <div class="mt-4">
              <p class="font-semibold mb-2">Ticket Prices:</p>
              <p>Normal: {{ session.isSpecialDay ? '€4' : '€6' }}</p>
              <p>VIP (Row F): {{ session.isSpecialDay ? '€6' : '€8' }}</p>
            </div>
          </div>
        </div>
      </div>
  
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-6">Select Your Seats</h2>
        
        <div class="mb-8">
          <div class="w-full bg-gray-800 text-white text-center py-2 mb-6 rounded">SCREEN</div>
          
          <div class="flex justify-center mb-6">
            <div class="flex flex-col items-center">
              <div v-for="(row, rowIndex) in seatMap" :key="rowIndex" class="flex mb-2">
                <div class="w-6 flex items-center justify-center font-bold mr-2">
                  {{ String.fromCharCode(65 + rowIndex) }}
                </div>
                <div v-for="(seat, seatIndex) in row" :key="`${rowIndex}-${seatIndex}`" 
                     @click="toggleSeat(rowIndex, seatIndex)"
                     :class="[
                       'seat',
                       seat === 0 ? 'seat-available' : (seat === 1 ? 'seat-occupied' : 'seat-selected'),
                       rowIndex === 5 ? 'seat-vip' : ''
                     ]">
                  {{ seatIndex + 1 }}
                </div>
              </div>
            </div>
          </div>
          
          <div class="flex justify-center space-x-6 mb-4">
            <div class="flex items-center">
              <div class="seat-available seat w-6 h-6 mr-2"></div>
              <span>Available</span>
            </div>
            <div class="flex items-center">
              <div class="seat-occupied seat w-6 h-6 mr-2"></div>
              <span>Occupied</span>
            </div>
            <div class="flex items-center">
              <div class="seat-selected seat w-6 h-6 mr-2"></div>
              <span>Selected</span>
            </div>
            <div class="flex items-center">
              <div class="seat-available seat seat-vip w-6 h-6 mr-2"></div>
              <span>VIP</span>
            </div>
          </div>
          
          <div class="text-center">
            <p class="mb-2">Selected seats: {{ selectedSeats.length }}/10</p>
            <p class="font-semibold">Total: €{{ calculateTotal() }}</p>
          </div>
        </div>
        
        <div v-if="selectedSeats.length > 0">
          <h3 class="text-lg font-bold mb-4">Your Information</h3>
          <form @submit.prevent="submitPurchase" class="space-y-4">
            <div>
              <label for="name" class="block mb-1">Name</label>
              <input 
                type="text" 
                id="name" 
                v-model="userInfo.name" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              >
            </div>
            <div>
              <label for="surname" class="block mb-1">Surname</label>
              <input 
                type="text" 
                id="surname" 
                v-model="userInfo.surname" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              >
            </div>
            <div>
              <label for="email" class="block mb-1">Email</label>
              <input 
                type="email" 
                id="email" 
                v-model="userInfo.email" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              >
            </div>
            <div>
              <label for="phone" class="block mb-1">Phone Number</label>
              <input 
                type="tel" 
                id="phone" 
                v-model="userInfo.phone" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              >
            </div>
            <div class="flex justify-end">
              <button type="submit" class="btn btn-primary">Confirm Purchase</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-12">
      <p class="text-xl">Loading session information...</p>
    </div>
  </template>
  
  <script setup>
  const route = useRoute();
  const sessionId = parseInt(route.params.id);
  
  // Mock session data
  const session = ref({
    id: sessionId,
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
  });
  
  // Initialize seat map (12 rows x 10 seats)
  // 0 = available, 1 = occupied, 2 = selected by current user
  const seatMap = ref(Array(12).fill().map(() => Array(10).fill(0)));
  
  // Randomly mark some seats as occupied for demo purposes
  onMounted(() => {
    for (let i = 0; i < 20; i++) {
      const row = Math.floor(Math.random() * 12);
      const seat = Math.floor(Math.random() * 10);
      if (seatMap.value[row][seat] === 0) {
        seatMap.value[row][seat] = 1;
      }
    }
  });
  
  // Track selected seats
  const selectedSeats = ref([]);
  
  // User information
  const userInfo = ref({
    name: '',
    surname: '',
    email: '',
    phone: ''
  });
  
  // Toggle seat selection
  const toggleSeat = (rowIndex, seatIndex) => {
    // If seat is occupied, do nothing
    if (seatMap.value[rowIndex][seatIndex] === 1) return;
    
    // If seat is already selected, unselect it
    if (seatMap.value[rowIndex][seatIndex] === 2) {
      seatMap.value[rowIndex][seatIndex] = 0;
      const seatId = `${String.fromCharCode(65 + rowIndex)}${seatIndex + 1}`;
      selectedSeats.value = selectedSeats.value.filter(seat => seat.id !== seatId);
      return;
    }
    
    // If user has already selected 10 seats, don't allow more
    if (selectedSeats.value.length >= 10) {
      alert('You can select a maximum of 10 seats per session.');
      return;
    }
    
    // Select the seat
    seatMap.value[rowIndex][seatIndex] = 2;
    const seatId = `${String.fromCharCode(65 + rowIndex)}${seatIndex + 1}`;
    const isVip = rowIndex === 5; // Row F (index 5) is VIP
    const price = isVip 
      ? (session.value.isSpecialDay ? 6 : 8) 
      : (session.value.isSpecialDay ? 4 : 6);
    
    selectedSeats.value.push({
      id: seatId,
      row: String.fromCharCode(65 + rowIndex),
      number: seatIndex + 1,
      isVip,
      price
    });
  };
  
  // Calculate total price
  const calculateTotal = () => {
    return selectedSeats.value.reduce((total, seat) => total + seat.price, 0);
  };
  
  // Submit purchase
  const submitPurchase = () => {
    // In a real application, this would send data to the server
    console.log('Purchase submitted:', {
      sessionId: session.value.id,
      seats: selectedSeats.value,
      userInfo: userInfo.value,
      total: calculateTotal()
    });
    
    // Redirect to confirmation page (in a real app)
    alert('Purchase successful! You would be redirected to a confirmation page.');
  };
  
  // Format date function
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  </script>