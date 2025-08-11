<script setup>
import { reactive, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Swal from 'sweetalert2';

const { props } = usePage();
const errors = ref({});

// Usamos useForm para manejar PATCH con Inertia
const form = useForm({
  title: props.book.title || '',
  author: props.book.author || '',
  isbn: props.book.isbn || '',
  available_copies: props.book.available_copies || 1,
  category: props.book.category || '',
  description: props.book.description || '',
  cover: null, // Para nueva portada
});

function handleFileChange(e) {
  form.cover = e.target.files[0];
}

function submit() {
  form.patch(route('books.admin.update', props.book.id), {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('¡Actualizado!', 'El libro fue actualizado correctamente.', 'success');
    },
    onError: (err) => {
      errors.value = err;
      Swal.fire('Error', 'No se pudo actualizar el libro.', 'error');
    }
  });
}

function cancelar() {
  Swal.fire({
    title: '¿Cancelar cambios?',
    text: 'Los cambios no guardados se perderán',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'Seguir editando'
  }).then((result) => {
    if (result.isConfirmed) {
      window.history.back();
    }
  });
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Editar Libro
      </h2>
    </template>

    <div class="max-w-3xl mt-10 mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
      <form @submit.prevent="submit" enctype="multipart/form-data">

        <div>
          <label for="title" class="block font-semibold mb-1">Título</label>
          <input v-model="form.title" id="title" type="text" class="input-text" />
          <p v-if="errors.title" class="error-text">{{ errors.title[0] }}</p>
        </div>

        <div>
          <label for="author" class="block font-semibold mb-1">Autor</label>
          <input v-model="form.author" id="author" type="text" class="input-text" />
          <p v-if="errors.author" class="error-text">{{ errors.author[0] }}</p>
        </div>

        <div>
          <label for="isbn" class="block font-semibold mb-1">ISBN</label>
          <input v-model="form.isbn" id="isbn" type="text" class="input-text" />
          <p v-if="errors.isbn" class="error-text">{{ errors.isbn[0] }}</p>
        </div>

        <div>
          <label for="available_copies" class="block font-semibold mb-1">Copias Disponibles</label>
          <input v-model.number="form.available_copies" id="available_copies" type="number" min="0" class="input-text" />
          <p v-if="errors.available_copies" class="error-text">{{ errors.available_copies[0] }}</p>
        </div>

        <div>
          <label for="category" class="block font-semibold mb-1">Categoría</label>
          <input v-model="form.category" id="category" type="text" class="input-text" />
          <p v-if="errors.category" class="error-text">{{ errors.category[0] }}</p>
        </div>

        <div>
          <label for="description" class="block font-semibold mb-1">Descripción</label>
          <textarea v-model="form.description" id="description" rows="4" class="input-text"></textarea>
          <p v-if="errors.description" class="error-text">{{ errors.description[0] }}</p>
        </div>

        <div>
          <label for="cover" class="block font-semibold mb-1">Portada (opcional)</label>
          <input id="cover" type="file" @change="handleFileChange" accept="image/*" />
          <p v-if="errors.cover" class="error-text">{{ errors.cover[0] }}</p>
        </div>

        <div class="flex gap-4 mt-6 justify-end">
          <button type="button" @click="cancelar" class="btn-cancel">
            Cancelar
          </button>
          <button type="submit" class="btn-primary" :disabled="form.processing">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.input-text {
  width: 100%;
  border-radius: 4px;
  border: 1px solid #ccc;
  padding: 0.5rem;
}
.error-text {
  color: red;
  font-size: 0.85rem;
}
.btn-primary {
  background-color: #2563eb;
  color: white;
  padding: 0.5rem 1.5rem;
  border-radius: 4px;
}
.btn-cancel {
  background-color: #9ca3af;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 4px;
}
</style>
