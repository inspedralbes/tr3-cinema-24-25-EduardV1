<template>
  <!-- Footer -->
  <footer class="bg-black text-gray-400 mt-16">
    <div
      class="max-w-7xl mx-auto px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-8"
    >
      <div>
        <h3 class="text-xl font-extrabold text-white mb-4">CinemaPedralbes</h3>
        <p>
          La teva destinació principal per a les últimes pel·lícules i
          entreteniment.
        </p>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-white mb-4">Enllaços Ràpids</h3>
        <ul class="space-y-2">
          <li>
            <NuxtLink to="/" class="hover:text-white transition"
              >Inici</NuxtLink
            >
          </li>
          <li>
            <NuxtLink to="/movies" class="hover:text-white transition"
              >Pel·lícules</NuxtLink
            >
          </li>
          <li>
            <NuxtLink to="/contact" class="hover:text-white transition"
              >Contacte</NuxtLink
            >
          </li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-white mb-4">Contacta'ns</h3>
        <p>Email: info@CinemaPedralbes.com</p>
        <p>Telèfon: 664 565 564</p>
      </div>
    </div>
    <div class="text-center py-6 border-t border-gray-700">
      <p>
        &copy; {{ new Date().getFullYear() }} CinemaPedralbes. Tots els drets
        reservats.
      </p>
    </div>
  </footer>
</template>
