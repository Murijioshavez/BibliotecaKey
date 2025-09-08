<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>

    <Head title="Biblioteca" />

    <div class="bg-gradient-to-b from-gray-100 to-gray-50 dark:from-gray-900 dark:to-black min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="w-full flex items-center justify-between p-6 lg:px-20">
            <ApplicationLogo class="h-16 w-16" />

            <nav class="flex gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="px-4 py-2 rounded-md font-medium bg-blue-600 text-white hover:bg-blue-700 transition">
                Dashboard
                </Link>

                <template v-else>
                    <Link :href="route('login')"
                        class="px-4 py-2 rounded-md font-medium bg-blue-600 text-white hover:bg-blue-700 transition">
                    Log in
                    </Link>

                    <Link v-if="canRegister" :href="route('register')"
                        class="px-4 py-2 rounded-md font-medium bg-green-600 text-white hover:bg-green-700 transition">
                    Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero / Welcome Card -->
        <main class="flex-grow flex items-center justify-center px-6 lg:px-20">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-10 max-w-4xl w-full flex flex-col lg:flex-row gap-8 lg:gap-12 transition-colors">

                <!-- Icon -->
                <div
                    class="flex items-center justify-center shrink-0 w-28 h-28 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        class="w-16 h-16 text-blue-600 dark:text-blue-400" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z" />
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                        <path
                            d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                    </svg>
                </div>

                <!-- Text Content -->
                <div class="flex-1 flex flex-col justify-center">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Biblioteca Universitaria
                    </h1>
                    <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                        Bienvenido a la biblioteca universitaria. Aquí podrás consultar, reservar y gestionar libros
                        fácilmente.
                        Nuestro objetivo es ofrecerte acceso rápido y ordenado a todos nuestros recursos en Key
                        Institute.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="/REGLAMENTO_DE_BIBLIOTECA_KEY.pdf" download>
                            <button
                                class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                                Descargar reglamento
                            </button>
                        </a>
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')">
                        <button
                            class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium">
                            Ir al Dashboard
                        </button>
                        </Link>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
            &copy; 2025 Key Institute. Todos los derechos reservados.
        </footer>
    </div>
</template>
