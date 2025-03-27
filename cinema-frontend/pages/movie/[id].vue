<template>
  <div class="max-w-5xl mx-auto px-4 py-6">
    <div v-if="loading" class="text-center">Carregant...</div>
    <div v-else-if="error" class="text-center text-red-500">
      Error en carregar la pel·lícula
    </div>
    <div v-else-if="movie" class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="md:flex">
        <div class="md:w-1/3">
          <img :src="movie.poster_url" :alt="movie.title" class="w-full h-full object-cover" />
        </div>
        <div class="p-6 md:w-2/3">
          <h1 class="text-2xl font-bold mb-3">{{ movie.title }}</h1>
          <p class="text-gray-600 mb-4">{{ movie.description }}</p>
          <div class="space-y-2 mb-6">
            <p>
              <span class="font-semibold">Durada:</span>
              {{ movie.duration }} min
            </p>
            <p>
              <span class="font-semibold">Classificació:</span>
              {{ movie.rating }}
            </p>
            <p>
              <span class="font-semibold">Director:</span> {{ movie.director }}
            </p>
            <p>
              <span class="font-semibold">Puntuació:</span> {{ movie.score }}/10
            </p>
            <p><span class="font-semibold">Horari:</span> {{ movie.time }}</p>
            <p>
              <span class="font-semibold">Trailer:</span>
              <a :href="movie.trailer_url" target="_blank" class="text-blue-600 hover:underline">Veure Trailer</a>
            </p>
          </div>
          <button @click="showBookingModal = true"
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
            Reservar Entrades
          </button>
        </div>
      </div>
    </div>

    <!-- Booking Modal -->
    <div v-if="showBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-4 max-w-md w-full">
        <h2 class="text-lg font-bold mb-4">Reservar Entrades</h2>

        <!-- Selecció de Seients -->
        <div class="mb-4">
          <h3 class="text-sm font-semibold mb-2">Selecciona els seients</h3>
          <div class="flex items-center space-x-3 text-xs mb-3">
            <div class="flex items-center">
              <div class="w-4 h-4 bg-gray-200 rounded mr-1"></div>
              <span>Disponible</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-green-500 rounded mr-1"></div>
              <span>Seleccionat</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-gray-500 rounded mr-1"></div>
              <span>Ocupat</span>
            </div>
          </div>

          <div class="w-full bg-gray-800 text-white text-center py-1 rounded mb-4 text-xs">
            Pantalla
          </div>
          <div class="max-w-lg mx-auto">
            <div class="grid grid-cols-11 gap-1">
              <template v-for="row in rows" :key="row">
                <div class="w-5 flex items-center justify-center text-xs font-medium">
                  {{ row }}
                </div>
                <template v-for="seat in 10" :key="`${row}${seat}`">
                  <button :class="[
                    'w-5 h-5 rounded transition-colors text-xs font-medium',
                    getSeatClass(`${row}${seat}`),
                  ]" @click="toggleSeat(`${row}${seat}`)" :title="`${row}${seat}`"
                    :disabled="isSeatOccupied(`${row}${seat}`)">
                    {{ row }}{{ seat }}
                  </button>
                </template>
              </template>
            </div>
          </div>
        </div>

        <div class="text-right mb-3">
          <p class="text-xs font-semibold">
            Seients seleccionats: {{ selectedSeats.join(", ") }}
          </p>
          <p class="text-xs font-semibold">Total: {{ calculateTotal() }}€</p>
        </div>

        <!-- Formulari de Detalls del Client -->
        <form @submit.prevent="submitBooking" class="space-y-3">
          <div>
            <label class="block text-xs font-medium text-gray-700">Nom</label>
            <input type="text" v-model="booking.name" required
              class="mt-1 block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200" />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700">Correu electrònic</label>
            <input type="email" v-model="booking.email" required
              class="mt-1 block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200" />
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="showBookingModal = false"
              class="px-3 py-1 text-xs border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
              Cancel·lar
            </button>
            <button type="submit"
              class="px-3 py-1 text-xs bg-red-600 text-white rounded-md hover:bg-red-700">
              Confirma la Reserva
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full text-center">
        <div class="text-green-500 text-4xl mb-3">✓</div>
        <h2 class="text-xl font-bold mb-2">Reserva Confirmada!</h2>
        <p class="text-gray-600 mb-4 text-sm">
          La teva reserva s'ha realitzat correctament. Rebràs un correu de confirmació.
        </p>
        <div class="mb-4 text-sm">
          <h3 class="font-semibold mb-2">Detalls de la reserva:</h3>
          <p>Seients seleccionats: {{ reservedSeats.join(", ") }}</p>
          <p>Import total: {{ reservedTotal }}€</p>
        </div>
        <div class="space-x-2">          
          <button @click="closeSuccessModal" class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">
            Tancar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { jsPDF } from "jspdf";
import QRCode from "qrcode";

const route = useRoute();
const movieId = route.params.id;

const movie = ref(null);
const loading = ref(true);
const error = ref(false);
const showBookingModal = ref(false);
const showSuccessModal = ref(false);
const selectedSeats = ref([]);
const reservedSeats = ref([]);
const reservedTotal = ref(0);
const rows = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J"];
const booking = ref({ name: "", email: "" });
const tickets = ref([]);

const generateQRCode = async (ticketData) => {
  try {
    const data = JSON.stringify(ticketData);
    const qrDataUrl = await QRCode.toDataURL(data, {
      width: 128,
      margin: 1,
      color: {
        dark: '#000000',
        light: '#ffffff'
      }
    });
    return qrDataUrl;
  } catch (err) {
    console.error("Error generating QR code:", err);
    return null;
  }
};

const generateTicketsPDF = async () => {
  const doc = new jsPDF({
    orientation: 'landscape',
    unit: 'mm',
    format: 'a5'
  });

  for (let i = 0; i < reservedSeats.value.length; i++) {
    const seat = reservedSeats.value[i];
    if (i > 0) {
      doc.addPage();
    }

    // Generate QR code for this ticket
    const ticketData = {
      movieTitle: movie.value.title,
      seat: seat,
      customer: booking.value.name,
      showtime: movie.value.time,
      room: movie.value.room || '1',
      ticketId: `${movieId}-${seat}-${Date.now()}`
    };
    
    const qrCodeDataUrl = await generateQRCode(ticketData);

    // Background
    doc.setFillColor(245, 245, 245);
    doc.rect(0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight(), 'F');

    // Header
    doc.setFillColor(220, 38, 38);
    doc.rect(0, 0, doc.internal.pageSize.getWidth(), 20, 'F');

    doc.setTextColor(255, 255, 255);
    doc.setFontSize(24);
    doc.text(movie.value.title, 10, 15);

    // Ticket details
    doc.setTextColor(0, 0, 0);

    // Left column
    doc.setFontSize(14);
    doc.text('Seient:', 10, 40);
    doc.setFontSize(18);
    doc.text(seat, 10, 48);

    doc.setFontSize(14);
    doc.text('Client:', 10, 65);
    doc.setFontSize(16);
    doc.text(booking.value.name, 10, 73);

    // Right column
    doc.setFontSize(14);
    doc.text('Horari:', 100, 40);
    doc.setFontSize(16);
    doc.text(movie.value.time, 100, 48);

    doc.setFontSize(14);
    doc.text('Sala:', 100, 65);
    doc.setFontSize(16);
    doc.text(movie.value.room || '1', 100, 73);

    // Add QR Code
    if (qrCodeDataUrl) {
      doc.addImage(qrCodeDataUrl, 'PNG', 130, 30, 40, 40);
    }

    // Footer
    doc.setFillColor(220, 38, 38);
    doc.rect(0, doc.internal.pageSize.getHeight() - 20, doc.internal.pageSize.getWidth(), 20, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(12);
    doc.text('Gràcies per escollir el nostre cinema!', 10, doc.internal.pageSize.getHeight() - 8);

    // Decorative lines
    doc.setDrawColor(220, 38, 38);
    doc.setLineWidth(0.5);
    doc.line(10, 55, 90, 55);
    doc.line(100, 55, 180, 55);
  }

  return doc;
};

const downloadTickets = async () => {
  const doc = await generateTicketsPDF();
  const fileName = `entrades-${movie.value.title.toLowerCase().replace(/\s+/g, '-')}.pdf`;
  doc.save(fileName);
};

const closeSuccessModal = () => {
  downloadTickets();
  
  setTimeout(() => {
    showSuccessModal.value = false;
    window.location.href ="/";
  }, 2000);
};

const getSeatClass = (seatId) => {
  if (selectedSeats.value.includes(seatId)) {
    return "bg-green-500 text-white";
  }

  const ticket = tickets.value.find((ticket) => ticket.position === seatId);
  if (!ticket) return "bg-gray-200";
  if (!ticket.available) return "bg-gray-500";
  return ticket.type === "vip"
    ? "bg-yellow-400 hover:bg-yellow-500"
    : "bg-gray-200 hover:bg-green-400";
};

const calculateTotal = () => {
  return selectedSeats.value.reduce((total, seatId) => {
    const ticket = tickets.value.find((ticket) => ticket.position === seatId);
    return total + (ticket ? Number(ticket.price) : 0);
  }, 0);
};

const isSeatOccupied = (seatId) => {
  const ticket = tickets.value.find((ticket) => ticket.position === seatId);
  return ticket && !ticket.available;
};

const fetchMovie = async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/movies/${movieId}`);
    if (!res.ok) throw new Error("Error en carregar la pel·lícula");
    movie.value = await res.json();
  } catch (err) {
    error.value = true;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const fetchTickets = async () => {
  try {
    const res = await fetch(
      `http://localhost:8000/api/movie_sessions/${movieId}/tickets`
    );
    if (!res.ok) throw new Error("Error en carregar les entrades");
    tickets.value = await res.json();
  } catch (err) {
    console.error(err);
  }
};

onMounted(() => {
  fetchMovie();
  fetchTickets();
});

const toggleSeat = (seatId) => {
  const ticket = tickets.value.find((ticket) => ticket.position === seatId);
  if (!ticket || !ticket.available) return;

  const index = selectedSeats.value.indexOf(seatId);
  if (index === -1) {
    if (selectedSeats.value.length >= 10) {
      alert("Només es poden seleccionar màxim 10 seients.");
      return;
    }
    selectedSeats.value.push(seatId);
  } else {
    selectedSeats.value.splice(index, 1);
  }
};

const submitBooking = async () => {
  if (selectedSeats.value.length === 0) {
    alert("Si us plau, selecciona almenys un seient.");
    return;
  }

  try {
    const res = await fetch("http://localhost:8000/api/buytickets", {
      method: "POST",
      headers: { "Content-Type": "application/json", "Accept": "application/json" },
      body: JSON.stringify({
        movie_session_id: movieId,
        seats: selectedSeats.value,
      }),
    });

    if (!res.ok) {
      const errorData = await res.json();
      throw new Error("Error en processar la reserva");
    }    
    
    reservedSeats.value = [...selectedSeats.value];
    reservedTotal.value = calculateTotal();

    showBookingModal.value = false;
    showSuccessModal.value = true;
    selectedSeats.value = [];
    booking.value = { name: "", email: "" };
  } catch (err) {
    console.error(err);
    alert("No s'ha pogut processar la reserva.");
  }
};
</script>