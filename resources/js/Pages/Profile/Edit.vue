<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="col-lg-12">
                <div class="panel-heading p-3 mb-2 mt-5 bg-secondary text-white">
                    <h4><b class="whitecopy">Profile</b></h4>
                </div>
            </div>
        </template>

        <div class="col-lg-12">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />

            <UpdatePasswordForm class="max-w-xl mt-3 mb-5" />
        </div>

        
    </AuthenticatedLayout>
</template>
