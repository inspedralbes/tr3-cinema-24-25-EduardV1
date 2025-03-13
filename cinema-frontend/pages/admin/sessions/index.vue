<template>
    <div>
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestió de Sessions</h1>
        <button @click="showModal = true" class="btn btn-primary">Nova Sessió</button>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Sessions Programades</h2>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="py-2 px-4 text-left">Data</th>
                <th class="py-2 px-4 text-left">Hora</th>
                <th class="py-2 px-4 text-left">Pel·lícula</th>
                <th class="py-2 px-4 text-left">Dia Especial</th>
                <th class="py-2 px-4 text-left">VIP</th>
                <th class="py-2 px-4 text-left">Accions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="session in sessions" :key="session.id" class="border-b">
                <td class="py-2 px-4">{{ formatDate(session.date) }}</td>
                <td class="py-2 px-4">{{ session.time }}</td>
                <td class="py-2 px-4">
                  <div class="flex items-center gap-2">
                    <img :src="session.movie.poster" :alt="session.movie.title" class="w-8 h-12 object-cover rounded">
                    <span>{{ session.movie.title }}</span>
                  </div>
                </td>
                <td class="py-2 px-4">
                  <span v-if="session.is_special_day" class="text-accent font-semibold">Sí</span>
                  <span v-else>No</span>
                </td>
                <td class="py-2 px-4">
                  <span v-if="session.enable_vip" class="text-accent font-semibold">Activat</span>
                  <span v-else>Desactivat</span>
                </td>
                <td class="py-2 px-4">
                  <button @click="editSession(session)" class="text-primary hover:underline mr-2">Editar</button>
                  <button @click="confirmDelete(session)" class="text-secondary hover:underline">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Create/Edit Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
          <h2 class="text-2xl font-bold mb-4">{{ isEditing ? 'Editar Sessió' : 'Nova Sessió' }}</h2>
          
          <form @submit.prevent="saveSession" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="date" class="block mb-1">Data</label>
                <input 
                  type="date" 
                  id="date" 
                  v-model="sessionForm.date" 
                  required
                  :min="today"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                >
              </div>
              
              <div>
                <label for="time" class="block mb-1">Hora</label>
                <select 
                  id="time" 
                  v-model="sessionForm.time" 
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  <option value="16:00">16:00</option>
                  <option value="18:00">18:00</option>
                  <option value="20:00">20:00</option>
                  <option value="22:00">22:00</option>
                </select>
              </div>
            </div>
            
            <div>
              <label for="movieSearch" class="block mb-1">Cercar Pel·lícula</label>
              <div class="flex gap-2">
                <input 
                  type="text" 
                  id="movieSearch" 
                  v-model="movieSearch" 
                  placeholder="Títol de la pel·lícula"
                  class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                >
                <button 
                  type="button" 
                  @click="searchMovie" 
                  class="btn btn-primary"
                >
                  Cercar
                </button>
              </div>
            </div>
            
            <div v-if="movieResults.length > 0" class="max-h-60 overflow-y-auto border border-gray-300 rounded-md">
              <div 
                v-for="movie in movieResults" 
                :key="movie.imdbID"
                @click="selectMovie(movie)"
                class="p-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2 border-b"
              >
                <img :src="movie.Poster" :alt="movie.Title" class="w-12 h-16 object-cover">
                <div>
                  <p class="font-semibold">{{ movie.Title }}</p>
                  <p class="text-sm text-gray-600">{{ movie.Year }}</p>
                </div>
              </div>
            </div>
            
            <div v-if="sessionForm.movie.title" class="bg-gray-100 p-4 rounded-md">
              <h3 class="font-semibold mb-2">Pel·lícula Seleccionada</h3>
              <div class="flex gap-4">
                <img :src="sessionForm.movie.poster" :alt="sessionForm.movie.title" class="w-20 h-28 object-cover rounded">
                <div>
                  <p class="font-semibold">{{ sessionForm.movie.title }}</p>
                  <p>{{ sessionForm.movie.year }}</p>
                  <p class="text-sm">{{ sessionForm.movie.director }}</p>
                </div>
              </div>
            </div>
            
            <div class="flex items-center">
              <input 
                type="checkbox" 
                id="isSpecialDay" 
                v-model="sessionForm.is_special_day"
                class="mr-2"
              >
              <label for="isSpecialDay">Dia Especial (Preus Reduïts)</label>
            </div>
            
            <div class="flex items-center">
              <input 
                type="checkbox" 
                id="enableVIP" 
                v-model="sessionForm.enable_vip"
                class="mr-2"
              >
              <label for="enableVIP">Activar Fila VIP (Fila F)</label>
            </div>
            
            <div class="flex justify-end gap-2">
              <button type="button" @click="closeModal" class="btn bg-gray-300 hover:bg-gray-400">Cancel·lar</button>
              <button type="submit" class="btn btn-primary">Desar</button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
          <h2 class="text-xl font-bold mb-4">Confirmar Eliminació</h2>
          <p class="mb-4">Estàs segur que vols eliminar aquesta sessió?</p>
          <div class="flex justify-end gap-2">
            <button @click="showDeleteModal = false" class="btn bg-gray-300 hover:bg-gray-400">Cancel·lar</button>
            <button @click="deleteSession" class="btn btn-secondary">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  
  const api = useApi();
  const showModal = ref(false);
  const showDeleteModal = ref(false);
  const isEditing = ref(false);
  const selectedSession = ref(null);
  const movieSearch = ref('');
  const movieResults = ref([]);
  const sessions = ref([]);
  
  const today = computed(() => {
    return new Date().toISOString().split('T')[0];
  });
  
  const sessionForm = ref({
    date: '',
    time: '18:00',
    is_special_day: false,
    enable_vip: true,
    movie: {
      title: '',
      director: '',
      year: '',
      plot: '',
      poster: ''
    }
  });
  
  // Load sessions on mount
  onMounted(async () => {
    await loadSessions();
  });
  
  async function loadSessions() {
    try {
      const response = await api.get('/admin/sessions');
      sessions.value = response.data.data;
    } catch (error) {
      console.error('Error loading sessions:', error);
    }
  }
  
  function editSession(session) {
    isEditing.value = true;
    selectedSession.value = session;
    sessionForm.value = {
      date: session.date,
      time: session.time,
      is_special_day: session.is_special_day,
      enable_vip: session.enable_vip,
      movie: { ...session.movie }
    };
    showModal.value = true;
  }
  
  function confirmDelete(session) {
    selectedSession.value = session;
    showDeleteModal.value = true;
  }
  
  async function deleteSession() {
    if (!selectedSession.value) return;
  
    try {
      await api.delete(`/admin/sessions/${selectedSession.value.id}`);
      await loadSessions();
      showDeleteModal.value = false;
      selectedSession.value = null;
    } catch (error) {
      console.error('Error deleting session:', error);
    }
  }
  
  async function searchMovie() {
    if (!movieSearch.value) return;
    
    try {
      const response = await api.get(`/admin/movies/search?query=${encodeURIComponent(movieSearch.value)}`);
      movieResults.value = response.data.Search || [];
    } catch (error) {
      console.error('Error searching movies:', error);
    }
  }
  
  function selectMovie(movie) {
    sessionForm.value.movie = {
      title: movie.Title,
      director: movie.Director,
      year: movie.Year,
      plot: movie.Plot,
      poster: movie.Poster
    };
    movieResults.value = [];
    movieSearch.value = '';
  }
  
  async function saveSession() {
    try {
      if (isEditing.value) {
        await api.put(`/admin/sessions/${selectedSession.value.id}`, sessionForm.value);
      } else {
        await api.post('/admin/sessions', sessionForm.value);
      }
  
      await loadSessions();
      closeModal();
    } catch (error) {
      console.error('Error saving session:', error);
    }
  }
  
  function closeModal() {
    showModal.value = false;
    isEditing.value = false;
    selectedSession.value = null;
    sessionForm.value = {
      date: '',
      time: '18:00',
      is_special_day: false,
      enable_vip: true,
      movie: {
        title: '',
        director: '',
        year: '',
        plot: '',
        poster: ''
      }
    };
  }
  
  function formatDate(dateString) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ca-ES', options);
  }
  </script>