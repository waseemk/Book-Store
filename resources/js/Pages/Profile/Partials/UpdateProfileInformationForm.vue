<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <div class="card">
        <div class="card-header bg-primary text-white"><h5>Profile Information</h5></div>
        <div class="card-body">
            <h5 class="card-title">Update your account's profile information and email address.</h5>

            <form @submit.prevent="form.patch(route('profile.update'))" class="mt-4 col-lg-6">
                <div class="mb-3 row">
                    <InputLabel for="name" value="Name" class="col-sm-3 col-form-label" />
                    <div class="col-sm-9">
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
                    <InputLabel for="email" value="Email" class="col-sm-3 col-form-label" />

                    <div class="col-sm-9">
                        <TextInput
                            id="email"
                            type="email"
                            class="form-control-lg"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                </div>

                <div class="mb-3" v-if="mustVerifyEmail && user.email_verified_at === null">
                    <p class="text-sm mt-2 text-gray-800">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="btn btn-success"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-success"
                    >
                        A new verification link has been sent to your email address.
                    </div>
                </div>

                <div class="mt-2">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </div>
    
</template>
