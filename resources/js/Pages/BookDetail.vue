<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import moment from "moment";

const props = defineProps({ 
    book: Object,
    moment: Object,
});
</script>

<template>
    <Head :title="book.title" />

    <div class="hidden lg:flex items-center justify-center bg-danger bg-gradient p-2 text-center text-white text-sm">
        <div class="mt-px ml-1">
            Welcome to the world of book.
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <a class="float-end me-3" :href="route('home')"><b>&lt; Back</b></a>
                <h2 class="text-secondary text-center">{{ book.title }}</h2>
                <div class="text-center mt-2 mb-5" v-if="book.image">
                    <img class="img-fluid" :src="'/storage/images/books/'+ (book.image)" :alt="book.title">
                </div>
                <div class="text-center mt-2 mb-5" v-else>
                    <img class="img-fluid" :src="'/images/no_image.png'" :alt="book.title">
                </div>

                <p>{{ book.description }}</p>

                <div class="mt-4">
                    <p><strong>ISBN:</strong> {{ book.isbn }}</p>
                    <p><strong>Author:</strong> {{ book.author.name }}</p>
                    <p><strong>Genre:</strong> {{ book.genre.name }}</p>
                    <p><strong>Published:</strong> {{ moment(book.published).format("DD/MM/YYYY") }}</p>
                    <p><strong>Publisher:</strong> {{ book.publisher.name }}</p>
                </div>
            </div>

            
        </div>
        
    </div>
</template>
