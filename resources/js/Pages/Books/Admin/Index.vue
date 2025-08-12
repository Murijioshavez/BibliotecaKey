<script setup>
import { ref } from 'vue'

// Simulación de libros

const currentPage = ref(1)
const totalPages = ref(3)

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-bold">Administrar Libros</h1>
      <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        + Nuevo Libro
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="border p-2 text-left">ID</th>
            <th class="border p-2 text-left">Título</th>
            <th class="border p-2 text-left">Autor</th>
            <th class="border p-2 text-left">Año</th>
            <th class="border p-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books" :key="book.id" class="hover:bg-gray-50">
            <td class="border p-2">{{ book.id }}</td>
            <td class="border p-2">{{ book.title }}</td>
            <td class="border p-2">{{ book.author }}</td>
            <td class="border p-2">{{ book.year }}</td>
            <td class="border p-2 text-center space-x-2">
              <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Editar</button>
              <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="flex justify-center mt-6 space-x-2">
      <button @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-3 py-1 border rounded disabled:opacity-50">Prev</button>

      <span class="px-3 py-1 border rounded bg-gray-100">{{ currentPage }}</span>
      <span class="px-3 py-1">/</span>
      <span class="px-3 py-1 border rounded bg-gray-100">{{ totalPages }}</span>

      <button @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-3 py-1 border rounded disabled:opacity-50">Next</button>
    </div>
  </div>
</template>
