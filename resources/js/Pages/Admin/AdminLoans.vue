<script setup>
import { ref } from 'vue'
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
</script>

<template>
    <Head title="Reservas pendientes"/>
    <AuthenticatedLayout>
            <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Reservas por aprobar
            </h2>
        </template>

        <div>
            <table class="min-w-full text-left border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Libro</th>
                        <th class="p-2">Usuario</th>
                        <th class="p-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="loan in loans" :key="loan.id" class="border-t">
                        <td class="p-2">{{ loan.book.title }}</td>
                        <td class="p-2">{{ loan.user.name }}</td>
                        <td class="p-2">
                            <button @click="approveLoan(loan.id)" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                                Aprobar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
    </template>
