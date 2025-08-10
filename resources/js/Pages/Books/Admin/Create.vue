<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';


const coverPreview = ref(null);
const errors = ref({});
const form = reactive({
  title: '',
  author: '',
  isbn: '',
  available_copies: 1,
  category: '',
  description: '',
  cover_path: null,
});

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file && file.type.startsWith('image/')) {
    form.cover = file;
    coverPreview.value = URL.createObjectURL(file); // genera la URL de previsualización
  } else {
    form.cover = null;
    coverPreview.value = null;
  }
}


function submit() {
  errors.value = {}; // reset errores antes de enviar

  // Construir formData para enviar archivo
  const formData = new FormData();
  for (const key in form) {
    if (form[key] !== null) {
      formData.append(key, form[key]);
    }
  }

  router.post('/books/admin', formData, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'El libro se ha guardado correctamente.',
        timer: 2000,
        showConfirmButton: false,

    });
        coverPreview.value = null;
      // Limpiar formulario si quieres
      Object.keys(form).forEach(k => {
        if (k === 'available_copies') form[k] = 1;
        else form[k] = k === 'cover' ? null : '';
      });
    },
    onError: (errs) => {
      errors.value = errs;
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor revisa los campos y corrige los errores.',
      });
    }
  });
}
</script>

<template>
    <Head title="Nuevo Libro"/>
  <AuthenticatedLayout>
    <template #header class="mb-10">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Agregar un nuevo libro
            </h2>
    </template>
    <div class="mt-10 max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
      <h1 class="text-3xl font-extrabold mb-8 text-gray-900 dark:text-gray-100">Agregar Nuevo Libro</h1>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label for="title" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Título</label>
          <input
            v-model="form.title"
            id="title"
            type="text"
            placeholder="Ejemplo: Cien años de soledad"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
          />
          <p v-if="errors.title" class="text-red-600 mt-1 text-sm">{{ errors.title[0] }}</p>
        </div>

        <div>
          <label for="author" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Autor</label>
          <input
            v-model="form.author"
            id="author"
            type="text"
            placeholder="Ejemplo: Gabriel García Márquez"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
          />
          <p v-if="errors.author" class="text-red-600 mt-1 text-sm">{{ errors.author[0] }}</p>
        </div>

        <div>
          <label for="isbn" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">ISBN</label>
          <input
            v-model="form.isbn"
            id="isbn"
            type="text"
            placeholder="Ejemplo: 978-3-16-148410-0"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
          />
          <p v-if="errors.isbn" class="text-red-600 mt-1 text-sm">{{ errors.isbn[0] }}</p>
        </div>

        <div>
          <label for="available_copies" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Copias Disponibles</label>
          <input
            v-model.number="form.available_copies"
            id="available_copies"
            type="number"
            min="0"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
          />
          <p v-if="errors.available_copies" class="text-red-600 mt-1 text-sm">{{ errors.available_copies[0] }}</p>
        </div>

        <div>
          <label for="category" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Categoría</label>
          <input
            v-model="form.category"
            id="category"
            type="text"
            placeholder="Ejemplo: Novela"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
          />
          <p v-if="errors.category" class="text-red-600 mt-1 text-sm">{{ errors.category[0] }}</p>
        </div>

        <div>
          <label for="description" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Descripción</label>
          <textarea
            v-model="form.description"
            id="description"
            rows="4"
            placeholder="Una breve descripción del libro..."
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 resize-none"
          ></textarea>
          <p v-if="errors.description" class="text-red-600 mt-1 text-sm">{{ errors.description[0] }}</p>
        </div>

        <div>
          <label for="cover" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Portada (imagen)</label>
          <input
            id="cover"
            type="file"
            accept="image/*"
            @change="handleFileChange"
            class="block w-full text-gray-700 dark:text-gray-300"
          />
          <img v-if="coverPreview" :src="coverPreview" alt="Previsualización" class="mb-4 max-h-40 object-contain" />
          <p v-if="errors.cover" class="text-red-600 mt-1 text-sm">{{ errors.cover[0] }}</p>
        </div>

        <button
          type="submit"
          class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition"
        >
          Guardar Libro
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
