<script setup>
import { ref, computed } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import useFlashMessages from '@/Composables/useFlashMessages.js';

useFlashMessages();

const { props } = usePage();
const groupedBooks = props.groupedBooks || {};

// Extraemos todas las categorías únicas para la sidebar
const categories = computed(() => {
  const cats = new Set();
  Object.values(groupedBooks).forEach(books => {
    books.forEach(book => cats.add(book.category));
  });
  return ['Todas', ...cats];
});

const selectedCategory = ref('Todas');

// Filtrar libros por categoría
const filteredBooks = computed(() => {
  if (selectedCategory.value === 'Todas') {
    return groupedBooks;
  }

  const filtered = {};
  for (const letter in groupedBooks) {
    const filteredGroup = groupedBooks[letter].filter(book => book.category === selectedCategory.value);
    if (filteredGroup.length) filtered[letter] = filteredGroup;
  }
  return filtered;
});

function selectCategory(cat) {
  selectedCategory.value = cat;
}
</script>

<template>
  <Head title="Catálogo" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Catálogo completo
      </h2>
    </template>

    <div class="flex max-w-7xl mx-auto p-6 gap-8">
      <!-- Sidebar categorías -->
      <nav class="w-48 sticky top-20 self-start bg-gray-50 dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
        <h3 class="font-bold mb-4 text-gray-700 dark:text-gray-300 select-none">Categorías</h3>
        <ul class="space-y-2">
          <li
            v-for="cat in categories"
            :key="cat"
            @click="selectCategory(cat)"
            :class="[
              'cursor-pointer px-3 py-1 rounded select-none',
              selectedCategory === cat ? 'bg-blue-600 text-white' : 'hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-white'
            ]"
          >
            {{ cat }}
          </li>
        </ul>
      </nav>

      <!-- Contenido principal: libros -->
      <div class="flex-1 overflow-auto max-h-[80vh]">
        <h1 class="text-3xl font-extrabold mb-10 dark:text-white select-none">Catálogo de Libros</h1>

        <div v-if="Object.keys(filteredBooks).length === 0" class="text-center py-20 text-gray-500 dark:text-gray-400">
          No hay libros para esta categoría.
        </div>

        <div
          v-for="(books, letter) in filteredBooks"
          :key="letter"
          class="mb-12 scroll-mt-16"
        >
          <h2
            class="text-2xl font-bold mb-6 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2 select-none"
            :id="`letter-${letter}`"
          >
            {{ letter }}
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 capitalize">
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
                <span class="block"> Copias disponibles: {{ book.available_copies }}</span>
                <span class="block"> Categoría: {{ book.category }}</span>
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
    </div>
  </AuthenticatedLayout>
</template>
