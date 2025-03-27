<template>
  <!-- Navbar -->
  <nav class="bg-black text-white w-full fixed top-0 left-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-8 flex items-center justify-between h-16">
      <NuxtLink to="/" class="text-2xl font-extrabold"
        >CinemaPedralbes</NuxtLink
      >
      <div class="flex space-x-8">
        <NuxtLink to="/" class="hover:text-gray-300 transition">Inici</NuxtLink>
        <NuxtLink to="/movies" class="hover:text-gray-300 transition"
          >Pel·lícules</NuxtLink
        >
        <NuxtLink to="/admin" class="hover:text-gray-300 transition"
          >Administració</NuxtLink
        >
      </div>
    </div>
  </nav>
</template>
