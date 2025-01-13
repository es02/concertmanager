<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { FwbPagination } from 'flowbite-vue'
import SearchInput from "@/Components/SearchInput.vue";

const truth = true;

const props = defineProps(['volunteers', 'count']);

const sortBy = router.page.url.split('/');

var sortByURL = computed(() => {
    if (sortBy[3]) {
        return '/'.concat(sortBy[3]);
    } else {
        return '';
    }
});

const currentPage = ref(sortBy[2]);
const volunteersPerPage = 10;
const numberOfPages = Math.ceil(props.count / volunteersPerPage);

const paginatedVolunteers = computed(() => {
    var start = (currentPage.value - 1) * volunteersPerPage;
    if (currentPage.value === undefined) {
        start = parseInt("0")
    }
    const end = start + volunteersPerPage;
    if (props.volunteers === undefined) {
        return {};
    }
    if (props.volunteers.data) {
        return props.volunteers.data;
    }
    return props.volunteers.slice(start, end);
});

function changePage(page){
    console.log(page);
    var pageSort = sortByURL.value;
    if (props.volunteers.path && props.volunteers.path.split('/').slice(-1)[0] == 'volunteer') {
        // assume search
        router.get(props.volunteers.path.concat(`?page=${page}`));
    } else {
        router.get(`/volunteers/${page}${pageSort}`);
    }
}

var form = {
    import_csv: '',
};

const csvInput = ref(null);

function uploadCSV() {
    console.log('uploading csv');
    const csv = csvInput.value.files[0];

    if (! csv) return;

    form.import_csv = csv;

    router.post('/volunteers/import-csv', form);
    form.import_csv = '';
    csvInput.value.value = null;
};

function downloadCSV() {
    window.location.href = '/volunteers/export-csv';
};

const selectNewCSV = () => {
    csvInput.value.click();
};

function goToCreateVolunteer() {
    router.visit(route('volunteer.create'));
};

</script>

<template>
    <AppLayout title="Volunteers">
        <h1 class="text-2xl font-semibold text-center pt-10">Volunteers</h1>
        <div class="overflow-x-auto m-10 text-right">
            <!--<search-input route-name="volunteer.search" />-->
            <button type="submit" @click="goToCreateVolunteer()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Volunteer</button>
            <button type="submit" @click="selectNewCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import CSV</button>
            <button type="submit" @click.prevent="downloadCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Export CSV</button>
        </div>
        <div class="overflow-x-auto m-10">
            <form @submit.prevent="uploadCSV">
                <input
                    id="csv"
                    ref="csvInput"
                    type="file"
                    accept=".csv"
                    class="hidden"
                    @change="uploadCSV"
                ></form>
            <table class="table">
                <thead>
                    <tr>
                        <th><a :href="`/volunteers/${currentPage}/name`">Name</a></th>
                        <th><a :href="`/volunteers/${currentPage}/location`">Location</a></th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="volunteer in paginatedVolunteers" :key="volunteer.id">
                        <td class="link link-primary">
                            <Link :href="`/volunteer/${volunteer.id}`">{{ volunteer.name }}</Link>
                        </td>
                        <td>{{ volunteer.location }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="join flex justify-center fixed w-full p-4">
                <!-- Broken for numbers below 10 -->
                <!-- <fwb-pagination
                    v-model="currentPage"
                    :enableFirstAndLastButtons="truth"
                    nextLabel="Next"
                    previousLabel="Prev"
                    :total-pages="numberOfPages"
                    :total-items="count"
                    :slice-length="volunteersPerPage"
                    @update:model-value="changePage($event)"
                ></fwb-pagination> -->
                <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
                        <a v-else :href="`/volunteers/${currentPage - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a v-else :href="`/volunteers/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else :href="`/volunteers/${currentPage + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
            </div>
            <br/><br/>
        </div>
    </AppLayout>
</template>
