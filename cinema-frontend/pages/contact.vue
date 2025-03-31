<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Contacta'ns</h1>

    <div class="grid md:grid-cols-2 gap-12">
      <!-- Formulari de Contacte -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6">Envia'ns un missatge</h2>
        <form @submit.prevent="submitForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom</label>
            <input
              type="text"
              v-model="form.name"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Correu electrònic</label
            >
            <input
              type="email"
              v-model="form.email"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Assumpte</label
            >
            <input
              type="text"
              v-model="form.subject"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"
              >Missatge</label
            >
            <textarea
              v-model="form.message"
              rows="4"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
            ></textarea>
          </div>
          <button
            type="submit"
            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
          >
            Envia el Missatge
          </button>
        </form>
      </div>

      <!-- Informació de Contacte -->
      <div class="space-y-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h2 class="text-2xl font-semibold mb-6">Visita'ns</h2>
          <div class="space-y-4">
            <div class="flex items-start space-x-4">
              <div class="text-red-600">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </div>
              <div>
                <h3 class="font-semibold">Adreça</h3>
                <p class="text-gray-600">123 Carrer de la Pel·lícula</p>
                <p class="text-gray-600">Ciutat del Cinema, CP 12345</p>
              </div>
            </div>
            <div class="flex items-start space-x-4">
              <div class="text-red-600">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
              </div>
              <div>
                <h3 class="font-semibold">Correu electrònic</h3>
                <p class="text-gray-600">info@MoviieTick.com</p>
              </div>
            </div>
            <div class="flex items-start space-x-4">
              <div class="text-red-600">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                  />
                </svg>
              </div>
              <div>
                <h3 class="font-semibold">Telèfon</h3>
                <p class="text-gray-600">(555) 123-4567</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h2 class="text-2xl font-semibold mb-6">Horari</h2>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-600">Dilluns - Divendres</span>
              <span class="font-semibold">10:00 - 23:00</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Dissabte - Diumenge</span>
              <span class="font-semibold">09:00 - 00:00</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const form = ref({
  name: "",
  email: "",
  subject: "",
  message: "",
});

const submitForm = () => {
  // Aquí faries una crida a l'API per enviar el missatge
  alert("Missatge enviat correctament!");
  form.value = {
    name: "",
    email: "",
    subject: "",
    message: "",
  };
};
</script>
