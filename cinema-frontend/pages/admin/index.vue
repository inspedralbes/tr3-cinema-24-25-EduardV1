<template>
    <div>
      <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <NuxtLink to="/admin/sessions" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
          <h2 class="text-xl font-bold mb-2">Session Management</h2>
          <p class="text-gray-600 mb-4">Create and manage cinema sessions</p>
          <div class="flex justify-end">
            <span class="btn btn-primary">Manage Sessions</span>
          </div>
        </NuxtLink>
        
        <NuxtLink to="/admin/reports" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
          <h2 class="text-xl font-bold mb-2">Reports</h2>
          <p class="text-gray-600 mb-4">View occupancy and collection reports</p>
          <div class="flex justify-end">
            <span class="btn btn-primary">View Reports</span>
          </div>
        </NuxtLink>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4">Quick Stats</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-gray-100 p-4 rounded-md">
            <h3 class="font-semibold mb-2">Today's Sessions</h3>
            <p class="text-2xl font-bold">1</p>
          </div>
          <div class="bg-gray-100 p-4 rounded-md">
            <h3 class="font-semibold mb-2">Tickets Sold Today</h3>
            <p class="text-2xl font-bold">24</p>
          </div>
          <div class="bg-gray-100 p-4 rounded-md">
            <h3 class="font-semibold mb-2">Today's Revenue</h3>
            <p class="text-2xl font-bold">€156</p>
          </div>
        </div>
      </div>
    </div>
  </template>