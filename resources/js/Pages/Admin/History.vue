<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = usePage().props;
const loans = ref(props.loans.data || []);
const links = ref(props.loans.links || []);

function goToPage(url) {
    if (!url) return;
    router.get(url, {}, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <AuthenticatedLayout>

        <div class="mt-10 max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">Historial de Préstamos</h1>

            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Libro</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Usuario</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Estado</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Fecha Préstamo</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Fecha Vencimiento</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Fecha Devolución</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loans.length === 0">
                        <td colspan="6" class="text-center p-4 text-gray-500 dark:text-gray-400">No hay préstamos
                            registrados.</td>
                    </tr>
                    <tr v-for="loan in loans" :key="loan.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.book.title }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.user.name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 capitalize">{{ loan.status }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.fecha_prestamo ?? 'N/A' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.fecha_vencimiento ?? 'N/A'
                            }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ loan.returned_at ?? 'No devuelto'
                            }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginación simple -->
            <nav class="mt-6 flex justify-center space-x-2 text-gray-700 dark:text-gray-300">
                <button v-for="link in links" :key="link.label" v-html="link.label" :class="[
                    'px-3 py-1 rounded-md border',
                    link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 border-gray-300 dark:border-gray-600',
                    link.url ? 'cursor-pointer' : 'cursor-not-allowed opacity-50'
                ]" @click="goToPage(link.url)" :disabled="!link.url"></button>
            </nav>
        </div>
    </AuthenticatedLayout>
</template>
