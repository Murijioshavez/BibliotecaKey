<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
</script>

<template>
  <Head title="Catálogo" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Catálogo completo
      </h2>
    </template>

    <div class="p-6 max-w-7xl mx-auto">
      <h1 class="text-3xl font-extrabold mb-10 dark:text-white select-none">Catálogo de Libros</h1>

      <div
        v-for="(books, letter) in props.groupedBooks"
        :key="letter"
        class="mb-12 scroll-mt-16"
      >
        <h2
          class="text-2xl font-bold mb-6 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 select-none"
          :id="`letter-${letter}`"
        >
          {{ letter }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="book in books"
            :key="book.id"
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md hover:shadow-xl transition-transform duration-300 transform hover:-translate-y-1 flex flex-col p-5"
          >
            <img
              :src="book.cover_path"
              alt="Portada del libro"
              loading="lazy"
              class="w-full h-52 object-cover rounded-lg mb-4 border border-gray-300 dark:border-gray-600"
            />

            <h3
              class="font-semibold text-lg text-gray-900 dark:text-white truncate mb-1"
              :title="book.title"
            >
              {{ book.title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 truncate" :title="book.author">
              {{ book.author }}
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 leading-relaxed select-none">
              <span class="block">📚 Copias disponibles: {{ book.available_copies }}</span>
              <span class="block">🏷️ Categoría: {{ book.category }}</span>
            </p>

            <a
              :href="`/books/${book.id}`"
              class="mt-auto inline-block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition"
              role="button"
            >
              Ver detalle
            </a>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
