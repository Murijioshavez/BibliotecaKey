<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import useFlashMessages from '@/Composables/useFlashMessages.js';

useFlashMessages(); // Esto se encarga de mostrar SweetAlert al recibir flash del backend

const { props } = usePage();
const book = props.book;
const user = props.auth.user;

function eliminarLibro() {
    Swal.fire({
        title: '¿Estás seguro de eliminar este libro?',
        text: 'Esta acción es irreversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            router.delete(route('books.admin.destroy', book.id));
        }
    });
}

function reservarLibro() {
    Swal.fire({
        title: '¿Reservar este libro?',
        text: 'Confirma si deseas realizar la reserva.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, reservar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            router.post(route('books.reserve', book.id));
            Swal.fire({
                title: "¡Reservado!",
                text: "La solicitud de reserva fue enviada exitosamente.",
                icon: "success"
            });
        }
    });
}
</script>


<template>

    <Head :title="book.title" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ book.title }}
            </h2>
        </template>

        <div class="max-w-4xl mt-10 mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow space-y-8">
            <div class="flex flex-col md:flex-row gap-8">
                <img :src="book.cover_path" alt="Portada"
                    class="w-full md:w-64 h-96 object-cover rounded-lg border border-gray-300 dark:border-gray-600"
                    loading="lazy" />

                <div class="flex flex-col flex-grow text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold mb-2">{{ book.title }}</h1>
                    <p class="text-lg italic mb-4">por {{ book.author }}</p>

                    <p class="mb-4 whitespace-pre-wrap">{{ book.description }}</p>

                    <p class="mb-2"><strong>ISBN:</strong> {{ book.isbn }}</p>
                    <p class="mb-2"><strong>Copias disponibles:</strong> {{ book.available_copies }}</p>
                    <p class="mb-2"><strong>Categoría:</strong> {{ book.category }}</p>

                    <div class="mt-auto flex gap-4 pt-6">
                        <!-- Admin: Editar / Eliminar -->
                        <template v-if="user.role === 'admin'">
                            <a :href="`/books/admin/${book.id}/edit`"
                                class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow transition">
                                Editar
                            </a>
                            <button @click="eliminarLibro"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow transition">
                                Eliminar
                            </button>
                        </template>

                        <!-- Usuario normal: Reservar -->
                        <template v-else>
                            <button @click="reservarLibro"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition">
                                Reservar
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
