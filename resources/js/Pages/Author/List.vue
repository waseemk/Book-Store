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

const props = defineProps({ 
    authors: Object,
    filters: Object,
});
</script>

<template>
    <Head title="Authors" />

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
                <div class="card-header bg-primary text-white"><h5>Authors</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start"><h5>All Authors</h5></div>
                            <div class="float-end">
                                <Link
                                    :href="route('authors.create')"
                                    as="button"
                                    class="btn btn-secondary"
                                >
                                    Create
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <InputLabel for="filter_status" value="Filter" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <select v-model="form.status" class="form-select" name="filter_status" id="filter_status" aria-label="Filter by status">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">In Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3 row">
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

                        <div class="col-md-2">
                            <a href="javascript:void(0);" @click="reset()" as="button" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="authors.data.length == 0">
                                        <td colspan="3" class="text-center">No record found</td>
                                    </tr>
                                    <tr v-else v-for="(author, index) in authors.data" v-bind:index="index">
                                        <td>{{ author.name }}</td>
                                        <td class="text-center">
                                            <a v-if="author.status === 1" href="javascript:void(0);" @click="changeStatus(author)" as="button" class="btn btn-success btn-sm" style="width:30%">Active</a>
                                            <a v-else href="javascript:void(0);" @click="changeStatus(author)" as="button" class="btn btn-danger btn-sm" style="width:30%">In Active</a>
                                        </td>
                                        <td class="text-center">
                                            <a :href="route('authors.edit', author.id)" as="button" class="btn btn-warning btn-sm me-1 text-white" style="width:20%">Edit</a>
                                            <a href="javascript:void(0);" as="button" class="btn btn-danger btn-sm" style="width:20%" @click="deleteAuthor(author, index)">Delete</a>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                            <pagination class="mt-6" :links="authors.links" />
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
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/authors', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        changeStatus(author) {
            console.log(author.id);
            if(confirm("Do you really want to change status?")) {
                this.isLoading = true;
                axios.post('/authors/'+author.id+'/update-status').then(resp => {
                    this.isLoading = false;
                    if(resp.data.success) {
                        this.toastr.show = true;
                        this.toastr.success = true;
                        this.toastr.error = false;
                        this.toastr.message = 'Status has been change successfully.';
                        author.status = resp.data.status;
                    } else {
                        this.toastr.show = true;
                        this.toastr.success = false;
                        this.toastr.error = true;
                        this.toastr.message = 'Error Occurred: Status not changed. Please try again.';
                    }
                })
            }
        },
        deleteAuthor(author,index) {
            if(confirm("Do you really want to Delete?")) {
                this.isLoading = true;
                axios.post('/authors/'+author.id+'/delete').then(resp => {
                    this.isLoading = false;
                    if(resp.data.success) {
                        this.toastr.show = true;
                        this.toastr.success = true;
                        this.toastr.error = false;
                        this.toastr.message = 'Author has been deleted successfully.';
                        this.authors.data.splice(index, 1);
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