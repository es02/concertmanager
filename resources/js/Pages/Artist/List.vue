<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Rating from '@/Components/Rating.vue';
import { FwbPagination } from 'flowbite-vue'
import SearchInput from "@/Components/SearchInput.vue";

const truth = true;

const props = defineProps(['artists', 'count']);

const sortBy = router.page.url.split('/');

var sortByURL = computed(() => {
    if (sortBy[3]) {
        return '/'.concat(sortBy[3]);
    } else {
        return '';
    }
});

const currentPage = ref(sortBy[2]);
const artistsPerPage = 10;
const numberOfPages = Math.ceil(props.count / artistsPerPage);

function changePage(page){
    console.log(page);
    var pageSort = sortByURL.value;
    if (props.artists.path && props.artists.path.split('/').slice(-1)[0] == 'artist') {
        // assume search
        router.get(props.artists.path.concat(`?page=${page}`));
    } else {
        router.get(`/artists/${page}${pageSort}`);
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

    router.post('/artists/import-csv', form);
    form.import_csv = '';
    csvInput.value.value = null;
};

function downloadCSV() {
    window.location.href = '/artists/export-csv';
};

const selectNewCSV = () => {
    csvInput.value.click();
};

function goToCreateArtist() {
    router.visit(route('artist.create'));
};

</script>

<template>
    <AppLayout title="Artists">
        <h1 class="text-2xl font-semibold text-center pt-10">Artists</h1>
        <div class="overflow-x-auto m-10 text-right">
            <search-input route-name="artist.search" />
            <button type="submit" @click="goToCreateArtist()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Artist</button>
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
                        <th><a :href="`/artists/${currentPage}/name`">Name</a></th>
                        <th><a :href="`/artists/${currentPage}/genre`">Genre</a></th>
                        <th><a :href="`/artists/${currentPage}/location`">Location</a></th>
                        <th><!--<a :href="`/event/applications/${eventID}/sort/fee/${currentPage}`">-->Fee<!--</a>--></th>
                        <th><a :href="`/artists/${currentPage}/rating`">Rating</a></th>
                        <td></td>
                    </tr>
                </thead>
                <tbody v-if="artists.data">
                    <tr v-for="artist in artists.data" :key="artist.id">
                        <td class="link link-primary">
                            <Link :href="`/artist/${artist.id}`">{{ artist.name }}</Link>
                        </td>
                        <td>{{ artist.genre }}</td>
                        <td>{{ artist.location }}</td>
                        <td v-if="artist.standard_fee !== null" v-html="artist.standard_fee.slice(0,40)"></td>
                        <td v-else></td>
                        <td><Rating :rating="artist.rating"></Rating></td>
                        <td>
                            <span v-if="artist.booked_previously === 1" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Previously Booked</span>
                            <span v-if="artist.blacklisted === 1 || artist.blacklisted === 'Yes'" class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Blacklisted</span>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="artist in artists" :key="artist.id">
                        <td class="link link-primary">
                            <Link :href="`/artist/${artist.id}`">{{ artist.name }}</Link>
                        </td>
                        <td>{{ artist.genre }}</td>
                        <td>{{ artist.location }}</td>
                        <td v-if="artist.standard_fee !== null" v-html="artist.standard_fee.slice(0,40)"></td>
                        <td v-else></td>
                        <td><Rating :rating="artist.rating"></Rating></td>
                        <td>
                            <span v-if="artist.booked_previously === 1" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Previously Booked</span>
                            <span v-if="artist.blacklisted === 1 || artist.blacklisted === 'Yes'" class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Blacklisted</span>
                        </td>
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
                    :slice-length="artistsPerPage"
                    @update:model-value="changePage($event)"
                ></fwb-pagination> -->
                <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
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
            <br/><br/>
        </div>
    </AppLayout>
</template>
