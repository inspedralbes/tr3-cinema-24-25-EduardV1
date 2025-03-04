<template>
    <div class="flex justify-center items-center min-h-[70vh]">
      <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>
        
        <form @submit.prevent="login" class="space-y-4">
          <div>
            <label for="password" class="block mb-1">Password</label>
            <input 
              type="password" 
              id="password" 
              v-model="password" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>
          
          <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded-md">
            {{ error }}
          </div>
          
          <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  const password = ref('');
  const error = ref('');
  const authStore = useAuthStore();
  const router = useRouter();
  
  const login = () => {
    if (authStore.login(password.value)) {
      router.push('/admin');
    } else {
      error.value = 'Invalid password. Please try again.';
    }
  };
  </script>