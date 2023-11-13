<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <section class="vh-100" style="background-color: #f5f8fa;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center p-2 mb-2 display-6">
                        <Link class="text-dark text-decoration-none" href="/">
                            <b>Book</b>Store
                        </Link>
                    </div>
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Sign in</h3>
                            <form @submit.prevent="submit">

                                <div class="form-outline mb-4">
                                    <TextInput
                                        id="email"
                                        type="email"
                                        placeholder="Email"
                                        class="form-control-lg"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div class="form-outline mb-4">
                                    <TextInput
                                        id="password"
                                        type="password"
                                        placeholder="Password"
                                        class="form-control-lg"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />

                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <PrimaryButton class="col-lg-12 col-md-12 mb-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </PrimaryButton>

                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="btn btn-link"
                                >
                                    Forgot your password?
                                </Link>
                            </form>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</template>
