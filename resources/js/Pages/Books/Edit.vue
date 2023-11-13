<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
    book: Object,
    authors: Object,
    genres: Object,
    publishers: Object,
});

const form = useForm({
    title: props.book.title,
    isbn: props.book.isbn,
    author_id: props.book.author_id,
    genre_id: props.book.genre_id,
    publisher_id: props.book.publisher_id,
    description: props.book.description,
    image: '',
    published: props.book.published,
    status: props.book.status,
});


</script>

<template>
    <Head title="Edit Book" />

    <AuthenticatedLayout>
        <div class="col-lg-12 mt-5">
            <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $page.props.flash.error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="float-start">Edit Book</h5>
                    <a class="float-end text-white text-decoration-none me-3" :href="route('books')"><b>&lt; Back</b></a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <form @submit.prevent="form.post(route('books.update', book.id))" class="col-md-6">
                            
                            <div class="mb-3 row">
                                <InputLabel for="title" value="Title" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <TextInput
                                        id="title"
                                        type="text"
                                        class="form-control-lg"
                                        v-model="form.title"
                                        required
                                        autofocus
                                        autocomplete="title"
                                    />
                                    <InputError :message="form.errors.title" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="isbn" value="ISBN" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <TextInput
                                        id="isbn"
                                        type="text"
                                        class="form-control-lg"
                                        v-model="form.isbn"
                                        required
                                        autofocus
                                        autocomplete="isbn"
                                    />
                                    <InputError :message="form.errors.isbn" class="mt-2" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="image" value="Image" class="col-md-3 col-form-label" />
                                <div :class="book.image ? 'col-md-6' : 'col-md-9'">
                                    <input class="form-control" type="file" id="image" name="image" @input="form.image = $event.target.files[0]">
                                    <InputError :message="form.errors.image" class="mt-2" />
                                </div>
                                <div v-if="book.image" class="col-md-3">
                                    <img class="img-thumbnail" :src="'/storage/images/books/'+ (book.image)" alt="">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="author_id" value="Author" class="col-md-3 col-form-label required" />
                                <div class="col-md-9">
                                    <select v-model="form.author_id" class="form-select" name="author_id" id="author_id" aria-label="Author">
                                        <option value="">Select Author</option>
                                        <option v-for="author in authors" :value="author.id">{{ author.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.author_id" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="genre_id" value="Genre" class="col-md-3 col-form-label required" />
                                <div class="col-md-9">
                                    <select v-model="form.genre_id" class="form-select" name="genre_id" id="genre_id" aria-label="Genre">
                                        <option value="">Select Genre</option>
                                        <option v-for="genre in genres" :value="genre.id">{{ genre.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.genre_id" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="publisher_id" value="Publisher" class="col-md-3 col-form-label required" />
                                <div class="col-md-9">
                                    <select v-model="form.publisher_id" class="form-select" name="publisher_id" id="publisher_id" aria-label="Publisher">
                                        <option value="">Select Publisher</option>
                                        <option v-for="publisher in publishers" :value="publisher.id">{{ publisher.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.publisher_id" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="description" value="Description" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <textarea class="form-control form-control-lg" id="description" name="description" rows="3" v-model="form.description"></textarea>
                                    <InputError :message="form.errors.description" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="published" value="Published Date" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <VueDatePicker id="published" :model-value="form.published" v-model="form.published" @update:model-value="setDate" auto-apply required :enable-time-picker="false" :format="format" model-type="yyyy-MM-dd"></VueDatePicker>
                                    <InputError :message="form.errors.published" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="status" value="Status" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="status1" v-model="form.status" value="1"  :checked="form.status == 1">
                                        <label class="form-check-label" for="status1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="status0" value="0" v-model="form.status" :checked="form.status == 0">
                                        <label class="form-check-label" for="status0">In Active</label>
                                    </div>
                                    <InputError :message="form.errors.status" class="mt-2" :class="{ 'opacity-25': form.processing }" />
                                </div>
                                
                            </div>

                            <div class="mt-2">
                                <PrimaryButton :disabled="form.processing" class="col-3 mx-auto">Save</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                </Transition>

                                <Link
                                    :href="route('books')"
                                    as="button"
                                    class="btn btn-danger col-3 mx-auto ms-2"
                                >
                                    Close
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>