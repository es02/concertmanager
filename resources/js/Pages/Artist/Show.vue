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
                <div class="mt-4 font-normal flex flex-col justify-between p-4 leading-normal">
                    <p><span class="font-semibold">Email:</span> {{ artist.email }}</p>
                    <p><span class="font-semibold">Genre:</span> {{ artist.genre }}</p>
                    <p><span class="font-semibold">Location:</span> {{ artist.location }} </p>
                    <p><span class="font-semibold">Standard guarentee:</span> ${{ artist.standard_fee }} </p>
                    <p><br>{{ artist.bio }}</p>
                    <div v-if="artist.standard_rider !== null"><p><br><span class="font-semibold">Rider:</span> {{ artist.standard_rider }}</p></div>
                </div>
                <div class="overflow-x-auto m-10 text-right"><button @click="update = !update">Update</button></div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="artist.pic_url" :alt="`${artist.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>
    </AppLayout>
</template>
