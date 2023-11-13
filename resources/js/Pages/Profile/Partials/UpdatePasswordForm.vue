<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <div class="card">
        <div class="card-header bg-primary text-white"><h5>Update Password</h5></div>
        <div class="card-body">
            <h5 class="card-title">Ensure your account is using a long, random password to stay secure</h5>

            <form @submit.prevent="updatePassword" class="mt-4 col-lg-6">
                <div class="mb-3 row">
                    <InputLabel for="current_password" value="Current Password" class="col-sm-3 col-form-label" />
                    <div class="col-sm-9">
                        <TextInput
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="form-control-lg"
                            autocomplete="current-password"
                        />
                        <InputError :message="form.errors.current_password" class="mt-2" />
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <InputLabel for="password" value="New Password" class="col-sm-3 col-form-label" />
                    <div class="col-sm-9">
                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="form-control-lg"
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <InputLabel for="password_confirmation" value="Confirm Password" class="col-sm-3 col-form-label" />
                    <div class="col-sm-9">
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="form-control-lg"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password_confirmation" class="mt-2" />
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
