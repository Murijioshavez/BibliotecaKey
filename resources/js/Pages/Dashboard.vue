<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const { props } = usePage()
console.log(props)
// Props que Inertia te manda automáticamente
defineProps({
    auth: Object
});

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        Bienvenido, {{ $page.props.auth.user.name }}
                    </div>
                </div>

                <!-- Dashboard Student -->
                <div v-if="$page.props.auth.user.role === 'student'" class="space-y-6 mt-6">
                    <!-- Préstamos Activos -->
                    <div>
                        <h2 class="text-xl font-bold mb-4">Préstamos Activos</h2>

                        <table class="table-auto w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border">Libro</th>
                                    <th class="p-2 border">Estado</th>
                                    <th class="p-2 border">Fecha Préstamo</th>
                                    <th class="p-2 border">Fecha Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="loan in $page.props.historial" :key="loan.id">
                                    <td class="p-2 border">{{ loan.book.title }}</td>
                                    <td class="p-2 border capitalize">{{ loan.status }}</td>
                                    <td class="p-2 border">{{ loan.fecha_prestamo ?? 'Pendiente' }}</td>
                                    <td class="p-2 border">{{ loan.fecha_vencimiento ?? 'Pendiente' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <p v-if="$page.props.historial.length === 0" class="text-gray-500 mt-4">
                            No tienes préstamos activos.
                        </p>
                    </div>
                </div>

                <!-- Dashboard Admin -->
                <div v-else class="space-y-6 mt-6">
                    <!-- Resumen rápido -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Total de Libros</h2>
                            <div class="h-16 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded">
                                <span class="text-gray-600 dark:text-gray-300">{{ $page.props.totalLibros }}</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Libros Prestados</h2>
                            <div class="h-16 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded">
                                <span class="text-gray-600 dark:text-gray-300">{{ $page.props.librosPrestados }}</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Reservas Pendientes</h2>
                            <div class="h-16 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded">
                                <span class="text-gray-600 dark:text-gray-300">{{ $page.props.reservasPendientes
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para agregar libro -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                        <button class="bg-[#006DFF] text-white px-4 py-2 rounded hover:bg-[#0053cc] transition">
                            Agregar Libro
                        </button>
                    </div>

                    <!-- Últimos préstamos/reservas -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                        <h2 class="font-semibold mb-2 text-gray-800 dark:text-gray-100">Últimos Préstamos / Reservas
                        </h2>
                        <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                            <span class="text-gray-600 dark:text-gray-300">Tabla de últimas reservas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
