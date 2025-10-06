<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

//const props = defineProps(['application', 'fields', 'event']);
</script>

<template>
    <AppLayout title="Event Application - Artist">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Application submitted!
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex items-center px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <div class="col-span-6 sm:col-span-4 text-left mb-3 text-lg text-gray-500 md:text-xl dark:text-gray-400">
                    <p>Thank you for applying. <br/>
                    We will get back to you shortly.</p>
                    <p>If you do not receive your confirmation email, please check your spam folder.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
