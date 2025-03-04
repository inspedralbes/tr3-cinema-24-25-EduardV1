<template>
    <footer class="bg-gray-800 text-white py-6">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="mb-4 md:mb-0">
            <p>&copy; {{ new Date().getFullYear() }} Cinema Pedralbes. All rights reserved.</p>
          </div>
          <div class="flex space-x-4">
            <a href="#" class="hover:text-accent transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-accent transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-accent transition-colors">Contact</a>
          </div>
        </div>
      </div>
    </footer>
  </template>