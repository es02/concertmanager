<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps(['artist']);
</script>

<template>
    <AppLayout title="Artist Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Artist Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ artist.name }}</h2>
                <p class="text-sm text-gray-500">{{ artist.email }}</p>
                <p>{{ artist.bio }}</p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Location: </span>{{ artist.location }}
                </div>
            </div>
            <figure class="aspect-w-1 aspect-h-1 w-1/2">
                <img :src="artist.pic_url || 'https://i.pravatar.cc/300'" :alt="`${artist.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>
    </AppLayout>
</template>
