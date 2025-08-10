<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const { props } = usePage();
const errors = ref({});
const form = reactive({
  title: props.book.title || '',
  author: props.book.author || '',
  isbn: props.book.isbn || '',
  available_copies: props.book.available_copies || 1,
  category: props.book.category || '',
  description: props.book.description || '',
  cover: null, // Para subir nueva portada
});

function handleFileChange(e) {
  form.cover = e.target.files[0];
}

function submit() {
  router.post(`/books/admin/${props.book.id}`, form, {
    onSuccess: () => {
      Swal.fire('¡Actualizado!', 'El libro fue actualizado correctamente.', 'success');
    },
    onError: (err) => {
      errors.value = err.props?.errors || {};
      Swal.fire('Error', 'No se pudo actualizar el libro.', 'error');
    }
  });
}

function cancelar() {
  router.get('/books/admin');
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Editar Libro
      </h2>
    </template>

    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">
        <div>
          <label for="title" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Título</label>
          <input v-model="form.title" id="title" type="text" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100" />
          <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title[0] }}</p>
        </div>

        <div>
          <label for="author" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Autor</label>
          <input v-model="form.author" id="author" type="text" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100" />
          <p v-if="errors.author" class="text-red-600 text-sm mt-1">{{ errors.author[0] }}</p>
        </div>

        <div>
          <label for="isbn" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">ISBN</label>
          <input v-model="form.isbn" id="isbn" type="text" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100" />
          <p v-if="errors.isbn" class="text-red-600 text-sm mt-1">{{ errors.isbn[0] }}</p>
        </div>

        <div>
          <label for="available_copies" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Copias Disponibles</label>
          <input v-model.number="form.available_copies" id="available_copies" type="number" min="0" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100" />
          <p v-if="errors.available_copies" class="text-red-600 text-sm mt-1">{{ errors.available_copies[0] }}</p>
        </div>

        <div>
          <label for="category" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Categoría</label>
          <input v-model="form.category" id="category" type="text" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100" />
          <p v-if="errors.category" class="text-red-600 text-sm mt-1">{{ errors.category[0] }}</p>
        </div>

        <div>
          <label for="description" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Descripción</label>
          <textarea v-model="form.description" id="description" rows="4" class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-gray-100"></textarea>
          <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description[0] }}</p>
        </div>

        <div>
          <label for="cover" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Portada (opcional)</label>
          <input id="cover" type="file" @change="handleFileChange" accept="image/*" class="text-gray-900 dark:text-gray-100" />
          <p v-if="errors.cover" class="text-red-600 text-sm mt-1">{{ errors.cover[0] }}</p>
        </div>

        <div class="flex gap-4 mt-6 justify-end">
          <button type="button" @click="cancelar" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-gray-900 dark:text-gray-100 rounded transition">
            Cancelar
          </button>
          <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
