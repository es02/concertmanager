<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { Modal } from 'flowbite';
import Rating from '@/Components/Rating.vue';

const props = defineProps(['applications', 'count']);

const currentPage = ref(1);
const applicationsPerPage = 10;

const numberOfPages = computed(() => Math.ceil(props.count / applicationsPerPage));

var displayedApplicationID = 2;

var displayedApplication = props.applications[displayedApplicationID];

const $targetEl = document.getElementById('application-modal');

// options with default values
const options = {
    placement: 'top-center',
    backdrop: 'dynamic',
    backdropClasses:
        'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
    closable: true,
    onHide: () => {
        // console.log('modal is hidden');
    },
    onShow: () => {
        // console.log('modal is shown');
        // displayedApplication = props.applications[id];
    },
    onToggle: (id) => {
        // console.log('modal has been toggled');
        // displayedApplication = props.applications[id];
    },
};

// instance options object
const instanceOptions = {
  id: 'application-modal',
  override: true
};

const modal = new Modal($targetEl, options, instanceOptions);

function showModal(id) {
    displayedApplication = props.applications[id];
}

function previous() {
    if (displayedApplicationID !== 1) {
        displayedApplicationID--;
    }
}

function next() {
    if (displayedApplicationID !== props.count) {
        displayedApplicationID++;
    }
}
</script>

<template>
    <AppLayout title="Applications">
        <h1 class="text-2xl font-semibold text-center pt-10">Applications</h1>
        <div class="overflow-x-auto m-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Genre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(application, index) in applications" :key="application.id">
                        <td class="link link-primary">
                            <a @click="modal.show()" data-modal-target="application-modal" data-modal-toggle="application-modal">{{ application.name }}</a>
                        </td>
                        <td>{{ application.location }}</td>
                        <td>{{ application.genre }}</td>
                        <td>
                            <Rating :rating="application.rating"></Rating>
                        </td>
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
        <!-- Application Entry Modal modal -->
        <div id="application-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full p-4 md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-4xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ displayedApplication.name }}
                            <span v-if="displayedApplication.shortlisted" class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Shortlisted</span>
                            <span v-if="displayedApplication.accepted" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Accepted</span>
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="application-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <div v-for="(value, key) in displayedApplication">
                            <div v-if="key !== 'name' && key !== 'shortlisted' && key !== 'accepted' && value !== null"><span class="font-semibold">{{  key }} :</span> {{ value }}</div>
                        </div>
                    </div>
                    <div class="p-4 md:p-5 text-center">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button @click="previous()" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Previous
                            </button>
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Reject
                            </button>
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Shortlist
                            </button>
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Accept
                            </button>
                            <button @click="next()" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
