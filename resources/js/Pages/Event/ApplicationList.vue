<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, reactive } from 'vue';
import { Modal } from 'flowbite';
import Rating from '@/Components/Rating.vue';
import { Link, router, useForm  } from '@inertiajs/vue3';
import applicationModal from '@/Pages/Event/Partials/applicationModal.vue';
import LongTextInput from '@/Components/LongTextInput.vue';

const props = defineProps(['applications', 'count']);

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

var displayedApplicationID = ref(1);

const $targetEl = document.getElementById('application-modal');

// options with default values
const options = {
    placement: 'top-center',
    backdrop: 'dynamic',
    backdropClasses:
        'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
    closable: true,
};

// instance options object
const instanceOptions = {
  id: 'application-modal',
  override: true
};

const modal = new Modal($targetEl, options, instanceOptions);

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

function rate(id) {
    rateForm.post(route('artist.rate', {id: id, via: 'application'}), {
        preserveScroll: true,
        onSuccess: () => rateForm.reset(),
    });
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(application, index) in applications" :key="application.id">
                        <td class="link link-primary">
                            <a @click="displayedApplicationID=application.application_id; modal.show()" data-modal-target="application-modal" data-modal-toggle="application-modal">{{ application.name }}</a>
                        </td>
                        <td>{{ application.location }}</td>
                        <td>{{ application.genre }}</td>
                        <td>
                            <Rating :rating="application.rating"></Rating>
                        </td>
                        <td>
                            <span v-if="application.shortlisted" class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Shortlisted</span>
                            <span v-if="application.accepted" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Accepted</span>
                            <span v-if="application.rejected" class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rejected</span>

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
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="p-4 md:p-5">
                        <applicationModal :displayedApplication="applications[displayedApplicationID]" />
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
                    </div>
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <div class="p-4 md:p-5 text-center">
                            <button @click="reject(displayedApplicationID)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Reject
                            </button>
                            <button @click="shortlist(displayedApplicationID)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Shortlist
                            </button>
                            <button @click="accept(displayedApplicationID)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Accept
                            </button>
                            <button @click="rate(displayedApplicationID)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                Rate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
