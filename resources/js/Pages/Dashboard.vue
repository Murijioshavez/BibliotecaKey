<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold leading-tight text-gray-900 dark:text-gray-100">
                Dashboard
            </h2>
        </template>

        <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

                <!-- Bienvenida -->
                <section
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center text-gray-900 dark:text-gray-100 text-xl font-semibold select-none">
                    Bienvenido, <span class="font-extrabold">{{ props.auth.user.name }}</span>
                </section>

                <!-- Dashboard Student -->
                <section v-if="props.auth.user.role === 'student'" class="space-y-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 select-none">Préstamos Activos</h2>

                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-700 dark:text-gray-300 font-semibold select-none">
                                        Libro
                                    </th>
                                    <th scope="col"
                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-700 dark:text-gray-300 font-semibold capitalize select-none">
                                        Estado
                                    </th>
                                    <th scope="col"
                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-700 dark:text-gray-300 font-semibold select-none">
                                        Fecha Préstamo
                                    </th>
                                    <th scope="col"
                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-700 dark:text-gray-300 font-semibold select-none">
                                        Fecha Vencimiento
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="loan in props.historial" :key="loan.id"
                                    class="bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-white">
                                    <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 truncate max-w-xs">
                                        {{ loan.book.title }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 capitalize">
                                        {{ loan.status }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 whitespace-nowrap ">
                                        {{ loan.fecha_prestamo ?? 'Pendiente' }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-700 px-4 py-3 whitespace-nowrap">
                                        {{ loan.fecha_vencimiento ?? 'Pendiente' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p v-if="props.historial.length === 0"
                            class="mt-6 text-center text-gray-500 dark:text-gray-400 italic select-none">
                            No tienes préstamos activos.
                        </p>
                    </div>
                </section>

                <!-- Dashboard Admin -->
                <section v-else class="space-y-10">
                    <!-- Resumen rápido -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center flex flex-col justify-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 select-none">
                                Total de Libros
                            </h3>
                            <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 select-none">
                                {{ props.totalLibros }}
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center flex flex-col justify-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 select-none">
                                Libros Prestados
                            </h3>
                            <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 select-none">
                                {{ props.librosPrestados }}
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center flex flex-col justify-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 select-none">
                                Reservas Pendientes
                            </h3>
                            <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 select-none">
                                {{ props.reservasPendientes }}
                            </div>
                        </div>
                    </div>

                    <!-- Botón para agregar libro -->
                    <div class="text-center">
                        <button type="button"
                            class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50 transition">
                            Agregar Libro
                        </button>
                    </div>

                    <!-- Últimos préstamos/reservas -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100 select-none">
                            Últimos Préstamos / Reservas
                        </h2>
                        <div
                            class="h-32 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-gray-600 dark:text-gray-300 italic select-none">
                            Tabla de últimas reservas
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
