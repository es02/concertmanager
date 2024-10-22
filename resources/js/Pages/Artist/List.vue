<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Rating from '@/Components/Rating.vue';
import { FwbPagination } from 'flowbite-vue'

const props = defineProps(['artists', 'count']);

const artistsPerPage = 10;
const sortBy = router.page.url.split('/');
const currentPage = ref(sortBy[2]);

const pages = computed(() => Math.ceil(props.count / artistsPerPage));

var sortByURL = computed(() => {
    if (sortBy[3]) {
        return '/'.concat(sortBy[3]);
    } else {
        return '';
    }
});

function changePage(page){
    // console.log(page);
    router.get(`/artists/${page}`);
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="artist in artists" :key="artist.id">
                        <td class="link link-primary">
                            <Link :href="`/artist/${artist.id}`">{{ artist.name }}</Link>
                        </td>
                        <td>{{ artist.genre }}</td>
                        <td>{{ artist.location }}</td>
                        <td v-if="artist.standard_fee !== null" v-html="artist.standard_fee.slice(0,40)"></td>
                        <td><Rating :rating="artist.rating"></Rating></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join flex justify-center fixed w-full p-4">
            <fwb-pagination v-model="sortBy[2]" :total-pages="pages" :slice-length="artistsPerPage" @update:model-value="changePage($event)"></fwb-pagination>
        </div>
    </AppLayout>
</template>
