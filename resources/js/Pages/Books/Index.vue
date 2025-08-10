<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props de Inertia
const { props } = usePage();
console.log(props);
</script>

<template>
    <Head title="Catalogo" />
  <AuthenticatedLayout>
            <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Catalogo completo
            </h2>
        </template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6 dark:text-white">Catálogo de Libros</h1>

      <!-- Recorrer cada grupo por letra -->
      <div
        v-for="(books, letter) in $page.props.groupedBooks"
        :key="letter"
        class="mb-10"
      >
        <!-- Encabezado de letra -->
        <h2 class="text-xl font-bold mb-4 dark:text-gray-200">
          {{ letter }}
        </h2>

        <!-- Grid de cards -->
        <div
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
        >
          <div
            v-for="book in books"
            :key="book.id"
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col"
          >
            <!-- Portada -->
            <img
              :src="book.cover_path"
              alt="Portada del libro"
              class="w-full h-48 object-cover rounded mb-3"
            />

            <!-- Título y autor -->
            <h3 class="font-semibold text-gray-900 dark:text-white truncate">
              {{ book.title }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
              {{ book.author }}
            </p>

            <!-- Info adicional -->
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
              Copias: {{ book.available_copies }}<br>
              Categoría: {{ book.category }}
            </p>

            <!-- Botón detalle -->
            <a
              :href="`/books/${book.id}`"
              class="mt-auto text-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm transition"
            >
              Ver detalle
            </a>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
