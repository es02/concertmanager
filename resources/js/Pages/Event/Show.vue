<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps(['event']);
</script>

<template>
    <AppLayout title="Event Details">
        <h1 class="text-2xl font-semibold text-center pt-10">Event Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ event.name }}</h2>
                <p class="text-sm text-gray-500">{{ event.venue_name }}</p>
                <p>{{ event.description }}</p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Start Date: </span>{{ event.start }}
                </div>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">End Date: </span>{{ event.end }}
                </div>
            </div>
            <figure class="aspect-w-1 aspect-h-1 w-1/2">
                <img :src="event.event_pic_url || 'https://i.pravatar.cc/300'" :alt="`${event.name} Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>
    </AppLayout>
</template>
