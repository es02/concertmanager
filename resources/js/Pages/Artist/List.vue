<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps(['artists', 'count']);

const currentPage = ref(1);

const artistsPerPage = 10;

const numberOfPages = Math.ceil(props.count / artistsPerPage);
</script>

<template>
    <AppLayout title="Artists">
        <h1 class="text-2xl font-semibold text-center pt-10">Artists</h1>
        <div class="overflow-x-auto m-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Genre</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="artist in artists" :key="artist.id">
                        <td class="link link-primary">
                            <Link :href="`/artist/${artist.id}`">{{ artist.name }}</Link>
                        </td>
                        <td>{{ artist.genre }}</td>
                        <td>{{ artist.location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join flex justify-center fixed w-full p-4">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === 1" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
                        <a v-else :href="`/artists/${currentPage - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a v-else :href="`/artists/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else :href="`/artists/${currentPage + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </AppLayout>
</template>
