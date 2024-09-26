<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps(['events', 'count']);

const currentPage = ref(1);
const eventsPerPage = 10;

const numberOfPages = computed(() => Math.ceil(props.count / eventsPerPage));
</script>

<template>
    <AppLayout title="Events">
        <h1 class="text-2xl font-semibold text-center pt-10">Events</h1>
        <div class="overflow-x-auto m-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>Start End</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.id">
                        <td class="link link-primary">
                            <a :href="`/event/${event.id}`">{{ event.name }}</a>
                        </td>
                        <td>{{ event.location }}</td>
                        <td>{{ event.start }}</td>
                        <td>{{ event.end }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join flex justify-center fixed w-full p-4">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
                        <a v-else href="`/event/${page - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a v-else href="`/event/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else href="`/event/${page + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </AppLayout>
</template>
