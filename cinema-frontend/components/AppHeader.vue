<template>
    <header class="bg-primary text-white shadow-md">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <NuxtLink to="/" class="text-2xl font-bold">Cinema Pedralbes</NuxtLink>
        <nav>
          <ul class="flex space-x-6">
            <li><NuxtLink to="/" class="hover:text-accent transition-colors">Home</NuxtLink></li>
            <li><NuxtLink to="/sessions" class="hover:text-accent transition-colors">Sessions</NuxtLink></li>
            <li><NuxtLink to="/check-tickets" class="hover:text-accent transition-colors">My Tickets</NuxtLink></li>
            <li><NuxtLink to="/admin" class="hover:text-accent transition-colors">Admin</NuxtLink></li>
          </ul>
        </nav>
      </div>
    </header>
  </template>