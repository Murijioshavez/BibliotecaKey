<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';

const props = usePage().props;

function goToPage(url) {
  if (!url) return;
  window.location.href = url;  // Forzar recarga
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-10 max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
      <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100 tracking-wide">
        Historial de Préstamos
      </h1>

      <div class="overflow-x-auto rounded-md border border-gray-300 dark:border-gray-700">
        <table class="min-w-full table-auto border-collapse">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Libro
              </th>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Usuario
              </th>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Estado
              </th>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Fecha Préstamo
              </th>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Fecha Vencimiento
              </th>
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-left text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Fecha Devolución
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="props.loans.data.length === 0">
              <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400 italic select-none">
                No hay préstamos registrados.
              </td>
            </tr>

            <tr
              v-for="loan in props.loans.data"
              :key="loan.id"
              class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors cursor-pointer"
              title="Detalles del préstamo"
            >
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 truncate max-w-xs">
                {{ loan.book.title }}
              </td>
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 truncate max-w-xs">
                {{ loan.user.name }}
              </td>
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 capitalize font-medium text-gray-800 dark:text-gray-300">
                {{ loan.status }}
              </td>
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 whitespace-nowrap">
                {{ loan.fecha_prestamo ?? 'N/A' }}
              </td>
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 whitespace-nowrap">
                {{ loan.fecha_vencimiento ?? 'N/A' }}
              </td>
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 whitespace-nowrap">
                {{ loan.returned_at ?? 'No devuelto' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav class="mt-8 flex flex-wrap justify-center gap-3 select-none">
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
          :aria-disabled="!link.url"
        />
      </nav>
    </div>
  </AuthenticatedLayout>
</template>
