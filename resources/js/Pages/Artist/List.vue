<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['artists', 'count']);

const numberOfPages = Math.ceil(props.count / 10);
</script>

<template>
    <AppLayout title="Artists">
        <h1 class="text-2xl font-semibold text-center pt-10">Artists</h1>
        <div class="overflow-x-auto m-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="artist in artists" :key="artist.id">
                        <td class="link link-primary">
                            <Link :href="`/artist/${artist.id}`">{{ artist.name }}</Link>
                        </td>
                        <td>{{ artist.email }}</td>
                        <td>{{ artist.location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join flex justify-center fixed bottom-0 w-full p-4">
            <Link v-for="page in numberOfPages" :key="page" class="join-item btn" :href="`/artists/${page - 1}`"
                as="button" type="button">
            {{ page }}
            </Link>
        </div>
    </AppLayout>
</template>
