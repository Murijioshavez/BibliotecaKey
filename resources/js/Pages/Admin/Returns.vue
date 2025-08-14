<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = usePage().props;
const loans = ref(props.loans || []);

function markReturned(loanId) {
  Swal.fire({
    title: '¿Marcar como devuelto?',
    text: "No podrás revertir esta acción.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, devolverlo',
  }).then((result) => {
    if (result.isConfirmed) {
      router.put(route('loans.markAsReturned', loanId), {}, {
        onSuccess: () => {
          Swal.fire('¡Hecho!', 'El préstamo fue marcado como devuelto.', 'success');
          loans.value = loans.value.filter(l => l.id !== loanId);
        },
        onError: () => {
          Swal.fire('Error', 'No se pudo marcar el préstamo como devuelto.', 'error');
        }
      });
    }
  });
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-10 max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
      <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100 tracking-wide">
        Préstamos para devolución
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
              <th class="border-b border-gray-300 dark:border-gray-600 p-3 text-center text-gray-700 dark:text-gray-200 font-semibold text-sm">
                Acciones
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="loans.length === 0">
              <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400 italic select-none">
                No hay préstamos pendientes de devolución.
              </td>
            </tr>

            <tr
              v-for="loan in loans"
              :key="loan.id"
              class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:text-white dark:even:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900 transition-colors"
              title="Marcar préstamo como devuelto"
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
              <td class="border-b border-gray-300 dark:border-gray-600 p-3 text-center">
                <button
                  @click="markReturned(loan.id)"
                  class="px-4 py-1 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow-sm transition"
                  aria-label="Marcar como devuelto"
                >
                  Marcar como devuelto
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
