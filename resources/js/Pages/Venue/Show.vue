<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
const props = defineProps(['venue']);
</script>

<template>
    <AppLayout>
        <h1 class="text-2xl font-semibold text-center pt-10">Venue Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">Name: {{ venue.venue_name }}</h2>
                <p class="text-sm text-gray-500">Email: {{ venue.email }}</p>
                <p> Capacity: {{ venue.capacity || 0 }} </p>
                <p> Fee: {{ venue.fee || 0 }}</p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Location: </span>{{ venue.location }}
                </div>
            </div>
            <figure class="aspect-w-1 aspect-h-1 w-1/2">
                <img :src="venue.pic_url || 'https://i.pravatar.cc/300'" :alt="`${venue.venue_name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

    </AppLayout>
</template>
