
<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    venues: {
        type: Array,
        required: true
    },
    count: {
        type: Number,
        required: true
    }
});

const currentPage = ref(1);
const venuesPerPage = 10;

const numberOfPages = computed(() => Math.ceil(props.count / venuesPerPage));

const paginatedVenues = computed(() => {
    const start = (currentPage.value - 1) * venuesPerPage;
    const end = start + venuesPerPage;
    return props.venues.slice(start, end);
});

const goToCreateVenue = () => {
    router.visit(route('venue.create'));
};

const goToEditVenue = (id) => {
    router.visit(route('venue.edit', { id }));
};

const deleteVenue = (id) => {
    if (confirm('Are you sure you want to delete this venue?')) {
        router.delete(route('venue.destroy', { id }));
    }
};
</script>

<template>
    <AppLayout title="Venues">
        <h1 class="text-2xl font-semibold text-center pt-10">Venues</h1>
        <div class="overflow-x-auto m-10 text-right"><Link :href="route('venue.create')">New Venue</Link></div>
        <div class="overflow-x-auto m-10">

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="venue in paginatedVenues" :key="venue.id">
                        <td>{{ venue.id }}</td>
                        <td class="link link-primary">
                            <Link :href="`/venue/${venue.id}`">{{ venue.venue_name }}</Link>
                        </td>
                        <td>{{ venue.capacity }}</td>
                        <td>{{ venue.location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <div class="join flex justify-center fixed bottom-0 w-full p-4">
            <Link
                v-for="page in numberOfPages"
                :key="page"
                class="join-item btn"
                :href="`/events/${page - 1}`"
                as="button"
                type="button"
            >
                {{ page }}
            </Link>
        </div> -->
        <div class="join flex justify-center fixed w-full p-4">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
                        <a v-else href="`/venue/${page - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a v-else href="`/venue/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else href="`/venue/${page + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </AppLayout>
</template>
