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
    books.forEach(book => {
      if (book.category) cats.add(book.category);
    });
  });
  return ['Todas', ...cats];
});

const selectedCategory = ref('Todas');
const searchQuery = ref('');

const normalizedSearch = computed(() => searchQuery.value.trim().toLocaleLowerCase());

// Filtrar libros por categoría y texto, sin recargar la página.
const filteredBooks = computed(() => {
  const filtered = {};
  for (const letter in groupedBooks) {
    const filteredGroup = groupedBooks[letter].filter(book => {
      const matchesCategory = selectedCategory.value === 'Todas' || book.category === selectedCategory.value;
      const searchable = [book.title, book.author, book.isbn, book.category]
        .filter(Boolean)
        .join(' ')
        .toLocaleLowerCase();

      return matchesCategory && (!normalizedSearch.value || searchable.includes(normalizedSearch.value));
    });

    if (filteredGroup.length) filtered[letter] = filteredGroup;
  }
  return filtered;
});

const visibleBookCount = computed(() => Object.values(filteredBooks.value)
  .reduce((total, books) => total + books.length, 0));

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

    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 sm:py-8">
      <div class="mb-5 flex flex-col gap-1 sm:mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl">Catálogo de libros</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ visibleBookCount }} libro<span v-if="visibleBookCount !== 1">s</span> disponibles en el catálogo</p>
      </div>

      <!-- Filtros móviles: compactos y siempre visibles. -->
      <section class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:hidden">
        <label for="book-search" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Buscar</label>
        <input
          id="book-search"
          v-model="searchQuery"
          type="search"
          placeholder="Título, autor o ISBN"
          class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
        />
        <label for="category-filter" class="mb-1 mt-4 block text-sm font-semibold text-gray-700 dark:text-gray-200">Categoría</label>
        <select
          id="category-filter"
          v-model="selectedCategory"
          class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
        >
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
      </section>

      <div class="flex gap-8">
        <!-- Filtros de escritorio -->
        <aside class="sticky top-20 hidden h-fit w-56 shrink-0 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800 lg:block">
          <h2 class="mb-3 font-bold text-gray-700 dark:text-gray-300">Filtros</h2>
          <input
            v-model="searchQuery"
            type="search"
            placeholder="Buscar libro"
            class="mb-4 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
          />
          <h3 class="mb-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Categorías</h3>
          <ul class="max-h-[60vh] space-y-1 overflow-y-auto">
            <li v-for="cat in categories" :key="cat">
              <button
                type="button"
                @click="selectCategory(cat)"
                :class="[
                  'w-full rounded-md px-3 py-2 text-left text-sm transition',
                  selectedCategory === cat
                    ? 'bg-blue-600 font-semibold text-white'
                    : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700'
                ]"
              >
                {{ cat }}
              </button>
            </li>
          </ul>
        </aside>

        <!-- Contenido principal -->
        <div class="min-w-0 flex-1">
          <div v-if="Object.keys(filteredBooks).length === 0" class="rounded-xl border border-dashed border-gray-300 px-4 py-16 text-center text-gray-500 dark:border-gray-700 dark:text-gray-400">
            No hay libros que coincidan con los filtros.
          </div>

        <div
          v-for="(books, letter) in filteredBooks"
          :key="letter"
          class="mb-8 scroll-mt-16 sm:mb-12"
        >
          <h2
            class="mb-4 border-b border-gray-300 pb-2 text-xl font-bold text-gray-900 dark:border-gray-700 dark:text-gray-100 sm:mb-6 sm:text-2xl"
            :id="`letter-${letter}`"
          >
            {{ letter }}
          </h2>

          <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5 xl:grid-cols-4">
            <div
              v-for="book in books"
              :key="book.id"
              class="flex min-w-0 flex-col rounded-xl border border-gray-200 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 sm:p-4"
            >
              <img
                :src="book.cover_path"
                :alt="`Portada de ${book.title}`"
                loading="lazy"
                class="mb-3 aspect-[3/4] w-full rounded-lg border border-gray-200 object-cover dark:border-gray-600"
              />

              <h3
                class="mb-1 truncate text-sm font-semibold text-gray-900 dark:text-white sm:text-base"
                :title="book.title"
              >
                {{ book.title }}
              </h3>
              <p class="mb-2 truncate text-xs text-gray-600 dark:text-gray-400 sm:text-sm" :title="book.author">
                {{ book.author }}
              </p>

              <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">
                <span class="block">Copias: {{ book.available_copies }}</span>
                <span class="block truncate" :title="book.category">{{ book.category }}</span>
              </p>

              <a
                :href="`/books/${book.id}`"
                class="mt-auto inline-block w-full rounded-lg bg-blue-600 px-3 py-2 text-center text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 sm:text-sm"
                role="button"
              >
                Ver libro
              </a>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
