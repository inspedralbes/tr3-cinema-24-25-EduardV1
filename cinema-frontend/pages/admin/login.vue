<template>
  <div class="flex justify-center items-center min-h-[70vh]">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Accés Administrador</h1>
      
      <form @submit.prevent="login" class="space-y-4">
        <div>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Introdueix la contrasenya"
          >
        </div>
        
        <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded-md">
          {{ error }}
        </div>
        
        <div class="flex justify-end">
          <button type="submit" class="btn btn-primary">Iniciar sessió</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
const router = useRouter();
const adminStore = useAdminStore();
const password = ref('');
const error = ref('');

const login = () => {
  if (adminStore.login(password.value)) {
    router.push('/admin');
  } else {
    error.value = 'Contrasenya incorrecta. Torna-ho a intenar';
  }
};

// Redirect to admin dashboard if already logged in
onMounted(() => {
  if (adminStore.isAdmin) {
    router.push('/admin');
  }
});
</script>