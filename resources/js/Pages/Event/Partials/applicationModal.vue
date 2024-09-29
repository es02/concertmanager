<script setup>
import Rating from '@/Components/Rating.vue';

const props = defineProps(['displayedApplication']);

</script>

<template>
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ displayedApplication.name }}&nbsp;
            </h3>
            <Rating :rating="displayedApplication.rating"></Rating>&nbsp;&nbsp;
            <span v-if="displayedApplication.shortlisted" class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Shortlisted</span>&nbsp;
            <span v-if="displayedApplication.accepted" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Accepted</span>&nbsp;
            <span v-if="displayedApplication.rejected" class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rejected</span>&nbsp;
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
                <div v-if="key !== 'name' && key !== 'shortlisted' && key !== 'accepted' && key !== 'rejected' && key !== 'rating' && key !== 'reason' && key !== 'application_id' && value !== null">
                    <span class="font-semibold">{{  key }} :</span>
                    <span v-if="key.replace(/(\(s\))/, '').slice(-3) === 'URL'">
                        <ul v-for="link in value.split(/\r?\n/g)" class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                            <li><a :href="link">{{ link }}</a></li>
                        </ul>
                    </span>
                    <span v-else v-html="value.replace(/\r?\n/g, '<br />')"></span>
                </div>
            </div>
            <div v-show="displayedApplication.rejected"><span class="font-semibold">Rejection Reason :</span> {{ displayedApplication.reason }}</div>
        </div>
    </div>
</template>
