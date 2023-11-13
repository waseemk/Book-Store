<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="hidden lg:flex items-center justify-center bg-danger bg-gradient p-2 text-center text-white text-sm">
        <div class="mt-px ml-1">
            Welcome to the world of book.
        </div>
    </div>
</template>