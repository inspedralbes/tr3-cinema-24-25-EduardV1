<template>
  <header class="bg-primary text-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <NuxtLink to="/" class="text-2xl font-bold">MoviieTick</NuxtLink>
      <nav>
        <ul class="flex space-x-6 items-center">
          <li>
            <NuxtLink to="/" class="hover:text-accent transition-colors">Inici</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/sessions" class="hover:text-accent transition-colors">Sessions</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/check-tickets" class="hover:text-accent transition-colors">Les Meves entrades</NuxtLink>
          </li>

          <!-- Admin Menu -->
          <template v-if="adminStore.isAdmin">
            <li class="relative group">
              <button class="flex items-center space-x-2 hover:text-accent transition-colors" @click="toggleAdminMenu">
                <span>Admin</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-show="showAdminMenu"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 text-gray-700">
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                  Sortir
                </button>
              </div>
            </li>
          </template>

          <template v-else>
            <li>
              <NuxtLink to="/admin/login"
                class="px-4 py-2 rounded-md bg-transparent border-2 border-white hover:bg-white hover:text-primary transition-colors">
                Admin Access
              </NuxtLink>
            </li>
          </template>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup>
const adminStore = useAdminStore();
const router = useRouter();

const showAdminMenu = ref(false);

const toggleAdminMenu = () => {
  showAdminMenu.value = !showAdminMenu.value;
};

const handleLogout = () => {
  adminStore.logout();
  showAdminMenu.value = false;
  router.push('/');
};

// Close menu when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      showAdminMenu.value = false;
    }
  });
});
</script>