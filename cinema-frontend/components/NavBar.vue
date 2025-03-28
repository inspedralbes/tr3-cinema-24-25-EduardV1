<template>
  <nav class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <NuxtLink to="/" class="flex items-center">
          <span class="text-xl font-bold">MoviieTick</span>
        </NuxtLink>
        <div class="flex space-x-4">
          <NuxtLink to="/" class="hover:text-gray-300">Inici</NuxtLink>
          <NuxtLink to="/movies" class="hover:text-gray-300">Pel·lícules</NuxtLink>
        </div>
      </div>
    </div>
  </nav>
</template>
