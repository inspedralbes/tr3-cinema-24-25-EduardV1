<template>
  <footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-4 md:mb-0">
          <p>
            &copy; {{ new Date().getFullYear() }} MoviieTick. Tots els drets
            reservats.
          </p>
        </div>
        <div class="flex space-x-4">
          <a href="#" class="hover:text-accent transition-colors"
            >Termes del Servei</a
          >
          <a href="#" class="hover:text-accent transition-colors"
            >Política de privacitat</a
          >
          <a href="#" class="hover:text-accent transition-colors">Contacte</a>
        </div>
      </div>
    </div>
  </footer>
</template>
