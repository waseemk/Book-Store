<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    status: 1,
});
const submit = () => {
    form.post(route('publishers.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Publisher" />

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
                    <h5 class="float-start">Create Publisher</h5>
                    <a class="float-end text-white text-decoration-none me-3" :href="route('publishers')"><b>&lt; Back</b></a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <form @submit.prevent="submit" class="col-md-6">
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
                                        <input class="form-check-input" type="radio" id="status1" v-model="form.status" value="1" checked>
                                        <label class="form-check-label" for="status1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="status0" value="0" v-model="form.status">
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
                                    :href="route('publishers')"
                                    as="button"
                                    class="btn btn-danger col-3 mx-auto ms-2"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>