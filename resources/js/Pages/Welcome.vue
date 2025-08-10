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
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-4xl">

                <!-- Header -->
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <ApplicationLogo class="h-20 w-20"/>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                              class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')"
                                  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')"
                                  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <!-- Main Card -->
                <main class="mt-6">
                    <div class="rounded-lg bg-white p-6 shadow-lg ring-1 ring-white/[0.05] transition duration-300 dark:bg-zinc-900 dark:ring-zinc-800">
                        <div class="flex items-start gap-4">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#006DFF]/10 sm:size-16">
                                <svg class="size-5 sm:size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path fill="#006DFF" d="M4 4h16v16H4z"/>
                                </svg>
                            </div>

                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-black dark:text-white">
                                    Biblioteca Universitaria
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Bienvenido a la biblioteca universitaria. Aquí podrás consultar,
                                    reservar y gestionar libros fácilmente. Nuestro objetivo es
                                    ofrecerte acceso rápido y ordenado a todos nuestros recursos en Key Institute.
                                </p>

                                <div class="mt-6">
                                    <a href="/REGLAMENTO_DE_BIBLIOTECA_KEY.pdf" download>
                                        <button
                                            class="rounded-md bg-[#006DFF] px-4 py-2 text-white hover:bg-[#31F483] hover:text-black transition">
                                            Descargar reglamento de reservas
                                        </button>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>
</template>
