<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import Rating from '@/Components/Rating.vue';
import { router, useForm  } from '@inertiajs/vue3';
import LongTextInput from '@/Components/LongTextInput.vue';
import { FwbButton, FwbModal } from 'flowbite-vue'

const props = defineProps(['applications', 'count']);
// console.log('number of entries: ' + props.count)
// console.log('applications: ');
// console.log(props.applications);

const isShowModal = ref(false)

function closeModal () {
    isShowModal.value = false
}
function showModal (index) {
    displayedApplicationID=index;
    seen(props.applications[index].application_id);
    isShowModal.value = true
}

// [0] 'example.com' [1] 'event' [2] 'applications' [3] 'eventID' [4] '$pageNum'/'filter'/'sort'/'search' [5] '$term' [6] '$pageNum'/'sort' [7] '$pageNum'/'$term' [8] '$pageNum'
const urlPath = router.page.url.split('/');
const eventID = urlPath[3];

var sortByURL = computed(() => {
    if (isNaN(urlPath[4])) {
        if (urlPath[4] === 'sort'){
            return '/'.concat(urlPath[4]) + '/'.concat(urlPath[5]);
        } else if(isNaN(urlPath[6])){
            return '/'.concat(urlPath[5]) + '/'.concat(urlPath[6]) + '/'.concat(urlPath[7]);
        }
    } else {
        return '';
    }
});

var sortBy = computed(() => {
    if (isNaN(urlPath[4])) {
        if (urlPath[4] === 'sort'){
            return urlPath[5];
        } else if(isNaN(urlPath[6])){
            return urlPath[7];
        } else {
            return '';
        }
    } else {
        return '';
    }
});

const sortby = ref();

var filterBy = computed(() => {
    if (isNaN(urlPath[4])) {
        if (urlPath[4] === 'filter'){
            return urlPath[5];
        } else {
            return '';
        }
    } else {
        return '';
    }
});

const currentPage = ref(1);
const applicationsPerPage = 10;

const rejectForm = useForm({
    _method: 'POST',
    reason: ''
});

const rateForm = useForm({
    _method: 'POST',
    rating: '',
});

const numberOfPages = computed(() => Math.ceil(props.count / applicationsPerPage));

var displayedApplicationID = ref(0);

function accept(id) {
    router.post(`/event/applications/accept/${id}`);
}

function reject(id) {
    rejectForm.post(route('application.reject', {id: id}), {
        preserveScroll: true,
        onSuccess: () => rejectForm.reset(),
    });
}

function shortlist(id) {
    router.post(`/event/applications/shortlist/${id}`);
}

function destroy(id) {
    router.post(`/event/applications/delete/${id}`);
}

function rate(id) {
    rateForm.post(route('artist.rate', {id: id, via: 'application'}), {
        preserveScroll: true,
        onSuccess: () => rateForm.reset(),
    });
}

function seen(id) {
    if(props.applications[id].new === 1) {
        router.post(`/event/applications/seen/${id}`);
    }
}

function filter() {
    // console.log('filtering by: ' + sortby.value)
    if (sortby.value !== 'none') {
        if (sortBy.value !== '' && sortBy.value !== 'undefined') {
            router.get(`/event/applications/${eventID}/filter/${sortby.value}/sort/${sortBy.value}/${currentPage.value}`);
        } else {
            router.get(`/event/applications/${eventID}/filter/${sortby.value}/${currentPage.value}`);
        }
    } else {
        router.get(`/event/applications/${eventID}/sort/${sortBy.value}/${currentPage.value}`);
    }
}

function sort(sort) {
    console.log('sorting by: ' + sort)
    if (filterBy.value !== '') {
        router.get(`/event/applications/${eventID}/filter/${filterBy.value}/sort/${sort}/${currentPage.value}`);
    } else {
        router.get(`/event/applications/${eventID}/sort/${sort}/${currentPage.value}`);
    }
}

function downloadCSV() {
    window.location.href = `/applications/${eventID}/export-csv`;
};
</script>

<template>
    <AppLayout title="Applications">
        <h1 class="text-2xl font-semibold text-center pt-10">Applications</h1>
        <div class="overflow-x-auto m-10">
            <div class="overflow-x-auto m-10 text-right grid grid-cols-4">
                <p></p>
                <p></p>
                <p>
                    <button type="submit" @click.prevent="downloadCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Export CSV</button></p>
                <p>
                    <select
                        id="filter"
                        v-model="sortby"
                        @click="filter()"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="none" selected></option>
                        <option value="new">New</option>
                        <option value="accepted">Accepted</option>
                        <option value="shortlisted">Shortlisted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </p>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <!-- <th><a :href="`/event/applications/${eventID}/sort/name/${currentPage}`">Name</a></th> -->
                        <th><a @click="sort('name')" href="#">Name</a></th>
                        <th><a :href="`/event/applications/${eventID}/sort/location/${currentPage}`">Location</a></th>
                        <th><a :href="`/event/applications/${eventID}/sort/genre/${currentPage}`">Genre</a></th>
                        <th><!--<a :href="`/event/applications/${eventID}/sort/fee/${currentPage}`">-->Fee<!--</a>--></th>
                        <th><a :href="`/event/applications/${eventID}/sort/rating/${currentPage}`">Rating</a></th>
                        <th><a :href="`/event/applications/${eventID}/sort/status/${currentPage}`">Status</a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(application, index) in applications" :key="application.id">
                        <td class="link link-primary">
                            <a @click="showModal(index)" data-modal-target="application-modal" data-modal-toggle="application-modal">{{ application.name }}</a>
                        </td>
                        <td>{{ application.location }}</td>
                        <td>{{ application.genre }}</td>
                        <td v-if="application.standard_fee !== null"  v-html="application.standard_fee.slice(0,40)"></td>
                        <td v-else></td>
                        <td><Rating :rating="application.rating"></Rating></td>
                        <td>
                            <span v-if="application.new" class="badge badge-neutral gap-2">New</span>
                            <span v-if="application.accepted" class="badge badge-success gap-2">Accepted</span>
                            <span v-if="application.shortlisted" class="badge badge-warning gap-2">Shortlisted</span>
                            <span v-if="application.rejected" class="badge badge-error gap-2">Rejected</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <div class="join flex justify-center fixed w-full p-4">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
                        <a v-else :href="`/event/applications/${eventID}${sortByURL}/${currentPage - 1}`" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li v-for="page in numberOfPages">
                        <a v-if="currentPage === numberOfPages" href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ page }}</a>
                        <a  v-else :href="`/event/applications/${eventID}${sortByURL}/${page}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                    </li>
                    <li>
                        <span v-if="currentPage === numberOfPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</span>
                        <a v-else :href="`/event/applications/${eventID}${sortByURL}/${currentPage + 1}`" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
            </nav>
        </div> -->

        <!-- Application Entry Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal">
            <template #header>
            <div class="flex items-center text-lg">
                <a :href="`/artist/${applications[displayedApplicationID].artist}`">{{ applications[displayedApplicationID].name }}</a>&nbsp;
                <Rating :rating="applications[displayedApplicationID].rating"></Rating>&nbsp;&nbsp;
                <span v-if="applications[displayedApplicationID].new" class="badge badge-neutral gap-2">New</span>&nbsp;
                <span v-if="applications[displayedApplicationID].accepted" class="badge badge-success gap-2">Accepted</span>&nbsp;
                <span v-if="applications[displayedApplicationID].shortlisted" class="badge badge-warning gap-2">Shortlisted</span>&nbsp;
                <span v-if="applications[displayedApplicationID].rejected" class="badge badge-error gap-2">Rejected</span>&nbsp;
            </div>
            </template>
            <template #body>
                <div v-for="(value, key) in applications[displayedApplicationID]">
                    <div class="py-1" v-if="key !== 'name' && key !== 'shortlisted' && key !== 'accepted' && key !== 'rejected' && key !== 'rating' && key !== 'reason' && key !== 'application_id' && key !== 'new' && key !== 'artist' && value !== null && value !== ''">
                        <span class="font-semibold">{{  key }} :</span>
                        <span v-if="key.replace(/(\(s\))/, '').slice(-3) === 'URL'">
                            <ul v-for="link in value.split(/\r?\n/g)" class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                                <li><a :href="link">{{ link }}</a></li>
                            </ul>
                        </span>
                        <span v-else-if="key === 'bio' || key === 'tech_specs' || key == 'standard_rider'" v-html="value.replace(/\r?\n/g, '<br />')"></span>
                        <span v-else >{{ value }}</span>
                    </div>
                </div>
                <span v-if="applications[displayedApplicationID]['rejected']"><span class="font-semibold">Rejection Reason: </span>{{ applications[displayedApplicationID].reason }}</span>
                <p>
                    <select
                        id="rating"
                        v-model="rateForm.rating"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </p>
                <p>
                    <LongTextInput
                        id="rejectionReason"
                        ref="rejectionReason"
                        type="text"
                        v-model="rejectForm.reason"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                </p>
            </template>
            <template #footer>
                <div class="inline-flex rounded-md shadow-sm " role="group">
                    <div class="p-4 md:p-5 text-center">
                        <fwb-button @click="reject(applications[displayedApplicationID].application_id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Reject
                        </fwb-button>
                        <fwb-button @click="shortlist(applications[displayedApplicationID].application_id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Shortlist
                        </fwb-button>
                        <fwb-button @click="accept(applications[displayedApplicationID].application_id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Accept
                        </fwb-button>
                        <fwb-button @click="rate(applications[displayedApplicationID].application_id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Rate
                        </fwb-button>
                        <fwb-button @click="destroy(applications[displayedApplicationID].application_id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Delete
                        </fwb-button>
                    </div>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>
