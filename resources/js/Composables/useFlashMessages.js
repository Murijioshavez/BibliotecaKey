import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default function useFlashMessages() {
    const { props } = usePage();

    onMounted(() => {
        if (props.flash?.success) {
            Swal.fire('¡Éxito!', props.flash.success, 'success');
        }

        if (props.flash?.error) {
            Swal.fire('Error', props.flash.error, 'error');
        }

        if (props.flash?.info) {
            Swal.fire('Información', props.flash.info, 'info');
        }
    });
}
