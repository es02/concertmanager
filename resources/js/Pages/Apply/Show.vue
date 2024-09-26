<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import applicationForm from '@/Pages/Apply/Partials/applicationForm.vue';


const props = defineProps(['application', 'fields', 'event']);
</script>

<template>
    <AppLayout title="Event Application - Artist">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Apply for {{ event.name }}
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <applicationForm :application="application" :fields="fields"></applicationForm>
        </div>
    </AppLayout>
</template>
