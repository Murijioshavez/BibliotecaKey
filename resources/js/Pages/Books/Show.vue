<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Swal from 'sweetalert2';
// Obtenemos el libro desde las props de Inertia
const { props } = usePage();
const book = props.book;
console.log(book);

const reservar = reactive({
    book_id: book.id,
})

function submit (){
    router.post(`/books/${book.id}/reserve`, reservar, {
        onSuccess: () => {
            Swal.fire({
                title: '¡Reserva exitosa!',
                text: 'El libro ha sido reservado con éxito',
                icon: 'success',
                confirmButtonColor: '#206CE6',
            });
            book.available_copies = book.available_copies - 1
        },
        onError: (errors) => {
            Swal.fire({
                title: 'Error',
                text: Object.values(errors)[0],
                icon: 'error',
                confirmButtonColor: '#206CE6',
            })
        }
    })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-6 max-w-5xl mx-auto">
      <!-- Botón volver -->
      <div class="mb-6">
        <Link
          href="/books"
          class="inline-flex items-center px-3 py-2 rounded bg-gray-200 hover:bg-gray-300
                 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 transition"
        >
          ← Volver al catálogo
        </Link>
      </div>

      <!-- Card principal -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700
               overflow-hidden flex flex-col md:flex-row"
      >
        <!-- Portada -->
        <div class="md:w-1/3">
          <img
            :src="book.cover_path"
            alt="Portada del libro"
            class="w-full h-80 object-cover md:h-full"
          />
        </div>

        <!-- Información del libro -->
        <div class="p-6 flex-1 flex flex-col">
          <h1 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">
            {{ book.title }}
          </h1>
          <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
            {{ book.author }}
          </p>

          <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Categoría: {{ book.category }}<br>
            Copias disponibles: {{ book.available_copies }}<br>
            ISBN: {{ book.isbn }}
          </p>

          <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
            {{ book.description || 'Sin descripción disponible.' }}
          </p>

          <!-- Botón de acción -->
          <div class="mt-auto">
            <form @submit.prevent="submit">

                <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                >
                Reservar
            </button>
        </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
