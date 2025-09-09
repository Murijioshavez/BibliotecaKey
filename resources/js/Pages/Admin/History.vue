<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

const props = usePage().props
const search = ref(props.search || '') // Mantener búsqueda actual si existe

function submitSearch(event) {
  if (event.key === 'Enter') {
    const query = encodeURIComponent(search.value.trim())
    window.location.href = `/admin/loans/history${query ? `?search=${query}` : ''}`
  }
}

function goToPage(url) {
  if (!url) return
  window.location.href = url // Forzar recarga
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-10 max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
      <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100 tracking-wide">
        Historial de Préstamos
      </h1>

      <!-- Searchbar -->
      <input
        v-model="search"
        @keyup="submitSearch"
        type="text"
        placeholder="Buscar por nombre de usuario y presiona Enter"
        class="mb-6 w-full p-2 rounded-md border border-gray-300 dark:border-gray-600
               dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
      />

      <!-- Tabla -->
      <div class="overflow-x-auto rounded-md border border-gray-300 dark:border-gray-700">
        <table class="min-w-full table-auto border-collapse">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Libro</th>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Usuario</th>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Estado</th>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Fecha Préstamo</th>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Fecha Vencimiento</th>
              <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600">Fecha Devolución</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="props.loans.data.length === 0">
              <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400 italic">
                No hay préstamos registrados.
              </td>
            </tr>
            <tr
              v-for="loan in props.loans.data"
              :key="loan.id"
              class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700
                     dark:text-white hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors"
            >
              <td class="p-3 border-b border-gray-300 dark:border-gray-600">{{ loan.book.title }}</td>
              <td class="p-3 border-b border-gray-300 dark:border-gray-600">{{ loan.user.name }}</td>
              <td class="p-3 border-b border-gray-300 dark:border-gray-600 capitalize">{{ loan.status }}</td>
              <td class="p-3 border-b border-gray-300 dark:border-gray-600">{{ loan.fecha_prestamo ?? 'N/A' }}</td>
              <td class="p-3 border-b border-gray-300 dark:border-gray-600">{{ loan.fecha_vencimiento ?? 'N/A' }}</td>
              <td class="p-3 border-b border-gray-300 dark:border-gray-600">{{ loan.returned_at ?? 'No devuelto' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <nav class="mt-8 flex flex-wrap justify-center gap-3">
        <a
          v-for="link in props.loans.links"
          :key="link.label"
          v-html="link.label"
          :href="link.url"
          @click.prevent="link.url && goToPage(link.url)"
          :class="[
            'px-4 py-1 rounded-md border text-sm font-medium',
            link.active
              ? 'bg-blue-600 text-white border-blue-600'
              : 'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 border-gray-300 dark:border-gray-600',
            !link.url ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'
          ]"
        />
      </nav>
    </div>
  </AuthenticatedLayout>
</template>
