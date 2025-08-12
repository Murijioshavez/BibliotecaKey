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
                    // Opcional: actualizar localmente para no recargar página
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

        <div class="mt-10 max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Préstamos para devolución</h1>

            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Libro</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Usuario</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Estado</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Fecha Préstamo</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Fecha Vencimiento</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loans.length === 0">
                        <td colspan="6" class="text-center p-4 text-gray-500 dark:text-gray-400">No hay préstamos
                            pendientes de devolución.</td>
                    </tr>
                    <tr v-for="loan in loans" :key="loan.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.book.title }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.user.name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 capitalize">{{ loan.status }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.fecha_prestamo ?? 'N/A' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.fecha_vencimiento ?? 'N/A'
                            }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                            <button @click="markReturned(loan.id)"
                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-md transition">
                                Marcar como devuelto
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
