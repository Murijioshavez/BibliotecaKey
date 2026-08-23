<script setup>
import { computed, ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

const props = usePage().props

const search = ref(props.filters?.search || '')

const exportUrl = computed(() => {
    const query = search.value.trim()

    return `/admin/loans/history/export${
        query ? `?search=${encodeURIComponent(query)}` : ''
    }`
})

function submitSearch(event) {
    if (event.key === 'Enter') {
        const query = encodeURIComponent(search.value.trim())

        window.location.href =
            `/admin/loans/history${query ? `?search=${query}` : ''}`
    }
}

function goToPage(url) {
    if (!url) return

    window.location.href = url
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-10 max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">

            <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100 tracking-wide">
                Historial de Préstamos
            </h1>

            <div class="mb-6 flex flex-col gap-3 sm:flex-row">
                <input
                    v-model="search"
                    @keyup="submitSearch"
                    type="text"
                    placeholder="Buscar por nombre de usuario y presiona Enter"
                    class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                />

                <a
                    :href="exportUrl"
                    class="shrink-0 rounded-md bg-green-600 px-4 py-2 text-center font-semibold text-white transition hover:bg-green-700"
                >
                    Exportar a Excel
                </a>
            </div>

            <!-- Tabla -->

        </div>
    </AuthenticatedLayout>
</template>