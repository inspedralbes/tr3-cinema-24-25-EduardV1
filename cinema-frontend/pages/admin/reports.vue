<template>
    <div>
      <h1 class="text-3xl font-bold mb-6">Reports</h1>
      
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Session Occupancy and Collection</h2>
        
        <div class="mb-6">
          <label for="reportDate" class="block mb-2">Select Date</label>
          <div class="flex gap-2">
            <input 
              type="date" 
              id="reportDate" 
              v-model="selectedDate" 
              class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            >
            <button @click="generateReport" class="btn btn-primary">Generate Report</button>
          </div>
        </div>
        
        <div v-if="report" class="space-y-6">
          <div>
            <h3 class="text-lg font-semibold mb-2">Session Details</h3>
            <div class="bg-gray-100 p-4 rounded-md">
              <p><span class="font-semibold">Movie:</span> {{ report.session.movie.title }}</p>
              <p><span class="font-semibold">Date:</span> {{ formatDate(report.session.date) }}</p>
              <p><span class="font-semibold">Time:</span> {{ report.session.time }}</p>
              <p><span class="font-semibold">Special Day:</span> {{ report.session.isSpecialDay ? 'Yes' : 'No' }}</p>
            </div>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-2">Seat Occupancy</h3>
            <div class="mb-4">
              <div class="w-full bg-gray-800 text-white text-center py-2 mb-6 rounded">SCREEN</div>
              
              <div class="flex justify-center mb-6">
                <div class="flex flex-col items-center">
                  <div v-for="(row, rowIndex) in report.seatMap" :key="rowIndex" class="flex mb-2">
                    <div class="w-6 flex items-center justify-center font-bold mr-2">
                      {{ String.fromCharCode(65 + rowIndex) }}
                    </div>
                    <div v-for="(seat, seatIndex) in row" :key="`${rowIndex}-${seatIndex}`" 
                         :class="[
                           'seat',
                           seat === 0 ? 'seat-available' : 'seat-occupied',
                           rowIndex === 5 ? 'seat-vip' : ''
                         ]">
                      {{ seatIndex + 1 }}
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="flex justify-center space-x-6">
                <div class="flex items-center">
                  <div class="seat-available seat w-6 h-6 mr-2"></div>
                  <span>Available</span>
                </div>
                <div class="flex items-center">
                  <div class="seat-occupied seat w-6 h-6 mr-2"></div>
                  <span>Occupied</span>
                </div>
                <div class="flex items-center">
                  <div class="seat-available seat seat-vip w-6 h-6 mr-2"></div>
                  <span>VIP</span>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-2">Collection Report</h3>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="py-2 px-4 text-left">Ticket Type</th>
                    <th class="py-2 px-4 text-right">Quantity</th>
                    <th class="py-2 px-4 text-right">Price</th>
                    <th class="py-2 px-4 text-right">Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b">
                    <td class="py-2 px-4">Normal</td>
                    <td class="py-2 px-4 text-right">{{ report.normalTickets }}</td>
                    <td class="py-2 px-4 text-right">€{{ report.session.isSpecialDay ? '4' : '6' }}</td>
                    <td class="py-2 px-4 text-right">€{{ report.normalRevenue }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="py-2 px-4">VIP</td>
                    <td class="py-2 px-4 text-right">{{ report.vipTickets }}</td>
                    <td class="py-2 px-4 text-right">€{{ report.session.isSpecialDay ? '6' : '8' }}</td>
                    <td class="py-2 px-4 text-right">€{{ report.vipRevenue }}</td>
                  </tr>
                  <tr class="font-semibold">
                    <td class="py-2 px-4" colspan="3">Total</td>
                    <td class="py-2 px-4 text-right">€{{ report.totalRevenue }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-2">Occupancy Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-gray-100 p-4 rounded-md">
                <p class="font-semibold">Total Seats</p>
                <p class="text-2xl">120</p>
              </div>
              <div class="bg-gray-100 p-4 rounded-md">
                <p class="font-semibold">Occupied Seats</p>
                <p class="text-2xl">{{ report.occupiedSeats }}</p>
              </div>
              <div class="bg-gray-100 p-4 rounded-md">
                <p class="font-semibold">Occupancy Rate</p>
                <p class="text-2xl">{{ report.occupancyRate }}%</p>
              </div>
            </div>
          </div>
          
          <div class="flex justify-end">
            <button class="btn btn-primary">Export Report</button>
          </div>
        </div>
        
        <div v-else-if="reportGenerated" class="bg-gray-100 p-6 rounded-lg text-center">
          <p class="text-lg">No session found for the selected date.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  const selectedDate = ref(new Date().toISOString().split('T')[0]);
  const report = ref(null);
  const reportGenerated = ref(false);
  
  // Generate report
  const generateReport = () => {
    reportGenerated.value = true;
    
    // Mock data - in a real app, this would fetch from the server
    if (selectedDate.value === '2024-05-01') {
      // Initialize seat map (12 rows x 10 seats)
      // 0 = available, 1 = occupied
      const seatMap = Array(12).fill().map(() => Array(10).fill(0));
      
      // Randomly mark some seats as occupied for demo purposes
      let normalTickets = 0;
      let vipTickets = 0;
      
      for (let i = 0; i < 12; i++) {
        for (let j = 0; j < 10; j++) {
          if (Math.random() < 0.4) {
            seatMap[i][j] = 1;
            if (i === 5) { // Row F (index 5) is VIP
              vipTickets++;
            } else {
              normalTickets++;
            }
          }
        }
      }
      
      const isSpecialDay = false;
      const normalPrice = isSpecialDay ? 4 : 6;
      const vipPrice = isSpecialDay ? 6 : 8;
      
      report.value = {
        session: {
          id: 1,
          date: '2024-05-01',
          time: '18:00',
          isSpecialDay: isSpecialDay,
          movie: {
            title: 'Inception',
            director: 'Christopher Nolan',
            year: '2010',
            plot: 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
            poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'
          }
        },
        seatMap: seatMap,
        normalTickets: normalTickets,
        vipTickets: vipTickets,
        normalRevenue: normalTickets * normalPrice,
        vipRevenue: vipTickets * vipPrice,
        totalRevenue: (normalTickets * normalPrice) + (vipTickets * vipPrice),
        occupiedSeats: normalTickets + vipTickets,
        occupancyRate: Math.round(((normalTickets + vipTickets) / 120) * 100)
      };
    } else {
      report.value = null;
    }
  };
  
  // Format date function
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  </script>