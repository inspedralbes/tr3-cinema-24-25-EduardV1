<template>
  <footer class="bg-gray-900 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-bold mb-4">MoviieTick</h3>
          <p>La teva destinació principal per a les últimes pel·lícules i entreteniment.</p>
        </div>
        <div>
          <h3 class="text-lg font-bold mb-4">Enllaços Ràpids</h3>
          <ul class="space-y-2">
            <li><NuxtLink to="/" class="hover:text-gray-300">Inici</NuxtLink></li>
            <li><NuxtLink to="/movies" class="hover:text-gray-300">Pel·lícules</NuxtLink></li>
            <li><NuxtLink to="/contact" class="hover:text-gray-300">Contacte</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-bold mb-4">Contacta'ns</h3>
          <p>Email: info@MoviieTick.com</p>
          <p>Telèfon: (555) 123-4567</p>
        </div>
      </div>
      <div class="mt-8 text-center border-t border-gray-700 pt-8">
        <p>&copy; {{ new Date().getFullYear() }} MoviieTick. Tots els drets reservats.</p>
      </div>
    </div>
  </footer>
</template>
