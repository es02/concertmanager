
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

var form = {
    import_csv: '',
};

const csvInput = ref(null);

function uploadCSV() {
    console.log('uploading csv');
    const csv = csvInput.value.files[0];

    if (! csv) return;

    form.import_csv = csv;

    router.post('/venue/import-csv', form);
    form.import_csv = '';
    csvInput.value.value = null;
};

function downloadCSV() {
    window.location.href = '/venue/export-csv';
};

const selectNewCSV = () => {
    csvInput.value.click();
};

function goToCreateVenue() {
    router.visit(route('venue.create'));
};

function goToEditVenue(id) {
    router.visit(route('venue.edit', { id }));
};

function deleteVenue(id) {
    if (confirm('Are you sure you want to delete this venue?')) {
        router.post(route('venue.destroy', { id }));
    }
};
</script>

<template>
    <AppLayout title="Venues">
        <h1 class="text-2xl font-semibold text-center pt-10">Venues</h1>
        <div class="overflow-x-auto m-10 text-right">
            <button type="submit" @click="goToCreateVenue()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Venue</button>
            <button type="submit" @click="selectNewCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import CSV</button>
            <button type="submit" @click.prevent="downloadCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Export CSV</button>
        </div>
        <form @submit.prevent="uploadCSV">
                <input
                    id="csv"
                    ref="csvInput"
                    type="file"
                    accept=".csv"
                    class="hidden"
                    @change="uploadCSV"
                ></form>
        <div class="overflow-x-auto m-10">

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Location</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="venue in paginatedVenues" :key="venue.id">
                        <td><!-- {{ venue.id }} --></td>
                        <td class="link link-primary">
                            <Link :href="`/venue/${venue.id}`">{{ venue.venue_name }}</Link>
                        </td>
                        <td>{{ venue.capacity }}</td>
                        <td>{{ venue.location }}</td>
                        <td>
                            <!-- <button type="button" @click="goToEditVenue(venue.id)" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Edit
                            </button>
                            <button type="button" @click="deleteVenue(venue.id)" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Delete
                            </button> -->
                        </td>
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
                        <a v-else :href="`/venues/${currentPage - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a v-else :href="`/venues/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else :href="`/venues/${currentPage + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </AppLayout>
</template>
