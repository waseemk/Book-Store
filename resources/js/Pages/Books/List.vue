<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from "@/Components/Pagination.vue";
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import { Head, Link } from '@inertiajs/vue3';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

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
    <Head title="Books" />

    <AuthenticatedLayout>

        <loading v-model:active="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>

        <div class="col-lg-12 mt-5">

            <div class="toast align-items-center text-white border-0 top-0 end-0 p-2" :class="{ 'show': toastr.show, 'bg-success': toastr.success, 'text-danger': toastr.error }" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ toastr.message }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>

            <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $page.props.flash.error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white"><h5>Books</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start"><h5>All Books</h5></div>
                            <div class="float-end">
                                <Link
                                    :href="route('books.import')"
                                    as="button"
                                    class="btn btn-success btn-sm me-2"
                                >
                                Import Data
                                </Link>
                                
                                <Link
                                    :href="route('books.create')"
                                    as="button"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Create
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
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
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <InputLabel for="per_page" value="Record Per Page" class="col-md-3 col-form-label" />
                                <div class="col-md-4">
                                    <select v-model="form.per_page" class="form-select" name="per_page" id="per_page" aria-label="Record Per Page">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        
                        <div class="col-md-3">
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <select v-model="form.status" class="form-select" name="filter_status" id="filter_status" aria-label="Filter by status">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">In Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>

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

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>ISBN</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Published</th>
                                        <th>Publisher</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="books.data.length == 0">
                                        <td colspan="9" class="text-center">No record found</td>
                                    </tr>
                                    <tr v-else v-for="(book, index) in books.data" v-bind:index="index">
                                        <td>{{ book.title }}</td>
                                        <td>{{ book.isbn }}</td>
                                        <td>{{ book.author.name }}</td>
                                        <td>{{ book.genre.name }}</td>
                                        <td>{{ book.published }}</td>
                                        <td>{{ book.publisher.name }}</td>
                                        <td class="text-center">
                                            <a v-if="book.status === 1" href="javascript:void(0);" @click="changeStatus(book)" as="button" class="btn btn-success btn-sm">Active</a>
                                            <a v-else href="javascript:void(0);" @click="changeStatus(book)" as="button" class="btn btn-danger btn-sm">In Active</a>
                                        </td>
                                        <td class="text-center">
                                            <a :href="route('books.edit', book.id)" as="button" class="btn btn-warning btn-sm me-1 text-white">Edit</a>
                                            <a href="javascript:void(0);" as="button" class="btn btn-danger btn-sm" @click="deleteBook(book, index)">Delete</a>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                            <pagination class="mt-6" :links="books.links" />
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>

export default {
    
    data() {
        return {
            isLoading: false,
            fullPage: true,
            toastr: {
                show: false,
                success: false,
                error: false,
                message: '',
            },
            form: {
                search: this.filters.search,
                status: this.filters.status,
                author_id: this.filters.author_id,
                genre_id: this.filters.genre_id,
                publisher_id: this.filters.publisher_id,
                per_page: this.filters.per_page,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/books', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        changeStatus(book) {
            if(confirm("Do you really want to change status?")) {
                this.isLoading = true;
                axios.post('/books/'+book.id+'/update-status').then(resp => {
                    this.isLoading = false;
                    if(resp.data.success) {
                        this.toastr.show = true;
                        this.toastr.success = true;
                        this.toastr.error = false;
                        this.toastr.message = 'Status has been change successfully.';
                        book.status = resp.data.status;
                    } else {
                        this.toastr.show = true;
                        this.toastr.success = false;
                        this.toastr.error = true;
                        this.toastr.message = 'Error Occurred: Status not changed. Please try again.';
                    }
                })
            }
        },
        deleteBook(book,index) {
            if(confirm("Do you really want to Delete?")) {
                this.isLoading = true;
                axios.post('/books/'+book.id+'/delete').then(resp => {
                    this.isLoading = false;
                    if(resp.data.success) {
                        this.toastr.show = true;
                        this.toastr.success = true;
                        this.toastr.error = false;
                        this.toastr.message = 'Author has been deleted successfully.';
                        this.books.data.splice(index, 1);
                    } else {
                        this.toastr.show = true;
                        this.toastr.success = false;
                        this.toastr.error = true;
                        this.toastr.message = 'Error Occurred: Author not deleted. Please try again.';
                    }
                })
            }
        },
        reset() {
            this.form = mapValues(this.form, () => '');
        },
        onCancel() {
            console.log('User cancelled the loader.');
            this.isLoading = false;
        }
    },
  
}
</script>