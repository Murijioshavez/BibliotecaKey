// useFlashMessages.js
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default function useFlashMessages() {
  const page = usePage();

  watch(
    () => page.props.flash,
    (flash) => {
      if (flash?.success) {
        Swal.fire('¡Éxito!', flash.success, 'success');
      }
      if (flash?.error) {
        Swal.fire('Error', flash.error, 'error');
      }
      if (flash?.info) {
        Swal.fire('Información', flash.info, 'info');
      }
    },
    { deep: true, immediate: true }
  );
}
