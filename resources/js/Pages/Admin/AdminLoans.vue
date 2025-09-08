<script setup>
import { router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Swal from 'sweetalert2'



const props = defineProps({
  loans: Array
})

function approveLoan(id) {
  Swal.fire({
    title: '¿Aprobar préstamo?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, aprobar',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.put(`/loans/${id}/approve`, {}, {
        onSuccess: () => {
          Swal.fire('¡Aprobado!', 'El préstamo fue aprobado.', 'success')
        },
        onError: () => {
          Swal.fire('Error', 'No se pudo aprobar el préstamo.', 'error')
        }
      })
    }
  })
}

function rejectLoan(id) {
  Swal.fire({
    title: '¿Rechazar préstamo?',
    text: 'Esta acción eliminará la reserva.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, rechazar',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(`/loans/${id}/reject`, {
        onSuccess: () => {
          Swal.fire('Rechazado', 'El préstamo fue rechazado y eliminado.', 'success')
        },
        onError: () => {
          Swal.fire('Error', 'No se pudo rechazar el préstamo.', 'error')
        }
      })
    }
  })
}
</script>

<template>
  <Head title="Reservas pendientes" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Reservas por aprobar
      </h2>
    </template>

    <div class="max-w-5xl mt-10 mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                Libro
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                Usuario
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                Acción
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="loan in loans.slice().reverse()" :key="loan.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">
                {{ loan.book.title }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">
                {{ loan.user.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center flex justify-center gap-3">
                <button
                  @click="approveLoan(loan.id)"
                  class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                >
                  Aprobar
                </button>
                <button
                  @click="rejectLoan(loan.id)"
                  class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                >
                  Rechazar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
