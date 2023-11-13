<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="col-lg-12">
                <div class="panel-heading p-3 mb-2 mt-5 bg-secondary text-white">
                    <h4><b class="whitecopy">Welcome {{ $page.props.auth.user.name }}</b></h4>
                </div>
            </div>
            
        </template>

        <div class="col-lg-12">
            <div class="card-body mt-3">
                <h5 class="card-title">You're logged in!</h5>
            </div>
            
        </div>
    </AuthenticatedLayout>
</template>
