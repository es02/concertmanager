<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps(['application', 'fields']);
</script>

<template>
    <AppLayout title="Artist Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Apply for event</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">Apply</h2>
            </div>
        </div>
    </AppLayout>
</template>
