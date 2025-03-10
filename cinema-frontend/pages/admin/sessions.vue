<template>
    <div>
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Gestió de Sessions</h1>
        <button @click="showCreateModal = true" class="btn btn-primary">Crear Nova Sessió</button>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Properes Sessions</h2>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="py-2 px-4 text-left">Data</th>
                <th class="py-2 px-4 text-left">Hora</th>
                <th class="py-2 px-4 text-left">Pel·lícula</th>
                <th class="py-2 px-4 text-left">Dia Especial</th>
                <th class="py-2 px-4 text-left">Accions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="session in sessions" :key="session.id" class="border-b">
                <td class="py-2 px-4">{{ formatDate(session.date) }}</td>
                <td class="py-2 px-4">{{ session.time }}</td>
                <td class="py-2 px-4">{{ session.movie.title }}</td>
                <td class="py-2 px-4">
                  <span v-if="session.isSpecialDay" class="text-accent font-semibold">Sí</span>
                  <span v-else>No</span>
                </td>
                <td class="py-2 px-4">
                  <button @click="editSession(session)" class="text-primary hover:underline mr-2">Editar</button>
                  <button @click="deleteSession(session.id)" class="text-secondary hover:underline">Esborrar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Create/Edit Session Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
          <h2 class="text-2xl font-bold mb-4">{{ editingSession ? 'Editar Sessió' : 'Crear Nova Sessió' }}</h2>
          
          <form @submit.prevent="saveSession" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="date" class="block mb-1">Data</label>
                <input 
                  type="date" 
                  id="date" 
                  v-model="sessionForm.date" 
                  required
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
                </select>
              </div>
            </div>
            
            <div>
              <label for="movieSearch" class="block mb-1">Cercar Pel·lícula (OMDB)</label>
              <div class="flex gap-2">
                <input 
                  type="text" 
                  id="movieSearch" 
                  v-model="movieSearch" 
                  placeholder="Introdueix el títol de la pel·lícula"
                  class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                >
                <button 
                  type="button" 
                  @click="searchMovie" 
                  class="btn btn-primary"
                >
                  Search
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
                v-model="sessionForm.isSpecialDay"
                class="mr-2"
              >
              <label for="isSpecialDay">Dia Especial (Preus Reduïts)</label>
            </div>
            
            <div class="flex items-center">
              <input 
                type="checkbox" 
                id="enableVIP" 
                v-model="sessionForm.enableVIP"
                class="mr-2"
              >
              <label for="enableVIP">Activar Fila VIP (Fila F)</label>
            </div>
            
            <div class="flex justify-end gap-2">
              <button type="button" @click="showCreateModal = false" class="btn bg-gray-300 hover:bg-gray-400">Cancel</button>
              <button type="submit" class="btn btn-primary">Guardar Session</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  // Mock sessions data
  const sessions = ref([
    {
      id: 1,
      date: '2024-05-01',
      time: '18:00',
      isSpecialDay: false,
      enableVIP: true,
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
      enableVIP: true,
      movie: {
        title: 'The Shawshank Redemption',
        director: 'Frank Darabont',
        year: '1994',
        plot: 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
        poster: 'https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg'
      }
    }
  ]);
  
  // Modal state
  const showCreateModal = ref(false);
  
  // Form for creating/editing sessions
  const sessionForm = ref({
    id: null,
    date: '',
    time: '18:00',
    isSpecialDay: false,
    enableVIP: true,
    movie: {
      title: '',
      director: '',
      year: '',
      plot: '',
      poster: ''
    }
  });
  
  // Movie search
  const movieSearch = ref('');
  const movieResults = ref([]);
  
  // Editing state
  const editingSession = ref(false);
  
  // Format date function
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  
  // Search for movies using OMDB API
  const searchMovie = async () => {
    if (!movieSearch.value) return;
    
    // In a real app, this would use the actual OMDB API
    // For this demo, we'll use mock data
    movieResults.value = [
      {
        Title: 'Inception',
        Year: '2010',
        imdbID: 'tt1375666',
        Type: 'movie',
        Poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg',
        Director: 'Christopher Nolan',
        Plot: 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.'
      },
      {
        Title: 'The Matrix',
        Year: '1999',
        imdbID: 'tt0133093',
        Type: 'movie',
        Poster: 'https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg',
        Director: 'Lana Wachowski, Lilly Wachowski',
        Plot: 'When a beautiful stranger leads computer hacker Neo to a forbidding underworld, he discovers the shocking truth--the life he knows is the elaborate deception of an evil cyber-intelligence.'
      }
    ];
  };
  
  // Select a movie from search results
  const selectMovie = (movie) => {
    sessionForm.value.movie = {
      title: movie.Title,
      director: movie.Director,
      year: movie.Year,
      plot: movie.Plot,
      poster: movie.Poster
    };
    movieResults.value = [];
    movieSearch.value = '';
  };
  
  // Edit a session
  const editSession = (session) => {
    editingSession.value = true;
    sessionForm.value = JSON.parse(JSON.stringify(session));
    showCreateModal.value = true;
  };
  
  // Delete a session
  const deleteSession = (id) => {
    if (confirm('Are you sure you want to delete this session?')) {
      sessions.value = sessions.value.filter(session => session.id !== id);
    }
  };
  
  // Save a session (create or update)
  const saveSession = () => {
    if (editingSession.value) {
      // Update existing session
      const index = sessions.value.findIndex(s => s.id === sessionForm.value.id);
      if (index !== -1) {
        sessions.value[index] = JSON.parse(JSON.stringify(sessionForm.value));
      }
    } else {
      // Create new session
      const newSession = JSON.parse(JSON.stringify(sessionForm.value));
      newSession.id = sessions.value.length + 1;
      sessions.value.push(newSession);
    }
    
    // Reset form and close modal
    resetForm();
    showCreateModal.value = false;
  };
  
  // Reset the form
  const resetForm = () => {
    sessionForm.value = {
      id: null,
      date: '',
      time: '18:00',
      isSpecialDay: false,
      enableVIP: true,
      movie: {
        title: '',
        director: '',
        year: '',
        plot: '',
        poster: ''
      }
    };
    editingSession.value = false;
  };
  </script>