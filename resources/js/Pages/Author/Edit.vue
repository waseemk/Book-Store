<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({ author: Object });

const page = usePage();

const form = useForm({
    name: props.author.name,
    status: props.author.status,
});


</script>

<template>
    <Head title="Author" />

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
                    <h5 class="float-start">Edit Author</h5>
                    <a class="float-end text-white text-decoration-none me-3" :href="route('authors')"><b>&lt; Back</b></a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <form @submit.prevent="form.patch(route('authors.update', author.id))" class="col-md-6">
                            <div class="mb-3 row">
                                <InputLabel for="name" value="Name" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="form-control-lg"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>
                                
                            </div>

                            <div class="mb-3 row">
                                <InputLabel for="status" value="Status" class="col-md-3 col-form-label" />
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="status1" value="1" v-model="form.status" :checked="form.status == 1">
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
                                    :href="route('authors')"
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