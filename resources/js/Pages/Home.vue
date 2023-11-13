<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import moment from "moment";

import { ref } from 'vue';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const published_date = ref();
const format = (date) => {
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();

  return `${year}-${month}-${day}`;
}
const setDate = (value) => {
    published_date.value = value;
}

const props = defineProps({ 
    books: Object,
    filters: Object,
    authors: Object,
    genres: Object,
    publishers: Object,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="hidden lg:flex items-center justify-center bg-danger bg-gradient p-2 text-center text-white text-sm">
        <div class="mt-px ml-1">
            Welcome to the world of book.
        </div>
    </div>

    <div class="container mt-5">
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <TextInput
                            id="search"
                            name="search"
                            type="text"
                            class="form-control"
                            placeholder="Search..."
                            v-model="form.search"
                        />
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <a href="javascript:void(0);" @click="reset()" as="button" class="btn btn-secondary">Reset</a>
            </div>
        </div>

        <div class="row mt-3">

            <div class="col-md-3">
                <div class="col-md-12">
                    <select v-model="form.author_id" class="form-select" name="author_id" id="author_id" aria-label="Author">
                        <option value="">Select Author</option>
                        <option v-for="author in authors" :value="author.id">{{ author.name }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="col-md-12">
                    <select v-model="form.genre_id" class="form-select" name="genre_id" id="genre_id" aria-label="Genre">
                        <option value="">Select Genre</option>
                        <option v-for="genre in genres" :value="genre.id">{{ genre.name }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="col-md-12">
                    <select v-model="form.publisher_id" class="form-select" name="publisher_id" id="publisher_id" aria-label="Publisher">
                        <option value="">Select Publisher</option>
                        <option v-for="publisher in publishers" :value="publisher.id">{{ publisher.name }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="col-md-12">
                    <VueDatePicker id="published" :model-value="form.published" v-model="form.published" @update:model-value="setDate" auto-apply required :enable-time-picker="false" :format="format" model-type="yyyy-MM-dd" placeholder="Select Published Date"></VueDatePicker>
                </div>
            </div>

        </div>
        <hr>
        <div class="row">
            <div v-if="books.data.length == 0" class="alert alert-danger alert-dismissible fade show" role="alert">
                No book found.
            </div>
            <div v-else class="col-3 mb-4" v-for="(book, index) in books.data" v-bind:index="index">
                <div class="card">
                    <div class="card-header"><b>Genre:</b> {{ book.genre.name }}</div>
                    <span v-if="book.image">
                        <img class="card-img-top" :src="'/storage/images/books/'+ (book.image)" :alt="book.title">
                    </span>
                    <span v-else>
                        <img class="card-img-top" :src="'/images/no_image.png'" :alt="book.title">
                    </span>
                    <div class="card-body">
                        <a :href="route('bookDetail', book.isbn)" class="text-primary text-decoration-none"><h5 class="card-title">{{ book.title }}</h5></a>
                        
                        <p class="card-text">{{ book.description.slice(0,300) }}</p>
                        <p class="card-text"><small class="text-muted"><b>Published: </b> {{ moment(book.published).format("DD/MM/YYYY") }}</small></p>
                    </div>

                    <div class="card-footer text-muted">
                        <b>Author:</b> {{ book.author.name }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <pagination class="mt-6" :links="books.links" />
        </div>
    </div>
</template>

<script>

export default {
    
    data() {
        return {
            isLoading: false,
            fullPage: true,
            moment: moment,
            form: {
                search: this.filters.search,
                author_id: this.filters.author_id,
                genre_id: this.filters.genre_id,
                publisher_id: this.filters.publisher_id,
                published: this.filters.published,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => '');
        },
        onCancel() {
            this.isLoading = false;
        }
    },
  
}
</script>