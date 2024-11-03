<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';
import { FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const props = defineProps(['event', 'stages', 'sets', 'forms', 'venues']);

const isShowModal = ref(false);

var form = {
    id: props.event.id,
    name: props.event.name,
    venue_id: props.event.venue_id,
    description: props.event.description,
    start: props.event.start,
    end: props.event.end,
    ticketing_provider: props.event.ticketing_provider,
    free: props.event.free,
    all_ages: props.event.all_ages,
    event_pic_url: props.event.event_pic_url,
    ticket_url: props.event.ticket_url,
    location: props.event.location,
    state: props.event.state,
};

var venueName = '';
var venueList = [];
for (const [key, venue] of Object.entries(props.venues)) {
    console.log(venue);
    venueList.push({value: venue.id, name: venue.venue_name});
    if(venue.id === props.event.venue_id){venueName = venue.venue_name;}
}

function closeModal () {isShowModal.value = false;}
function showModal () {isShowModal.value = true;}
function deleteForm(id) { router.post(`/event/deleteApplication/${id}`);}
function publishForm(id) { router.post(`/event/publishApplication/${id}`);}
function unPublishForm(id) { router.post(`/event/unpublishApplication/${id}`);}
function destroy(){router.post(`/event/delete/${props.event.id}`);}
function updateEvent() {
    router.post('/event/update', form);
    closeModal();
};
</script>

<template>
    <AppLayout title="Event Details">
        <h1 class="text-2xl font-semibold text-center pt-10">Event Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ event.name }}</h2>
                <span v-if="event.all_ages !== 0" class="font-semibold">ALL AGES</span> <span v-if="event.free !== 0" class="font-semibold">FREE EVENT</span>
                <p class="text-sm text-gray-500">{{ event.venue_name }} - {{ event.location }}</p>
                <p class="mt-4 text-sm" v-html="event.description.replace(/\r?\n/g, '<br />')"></p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Start Date: </span>{{ event.start }}
                </div>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">End Date: </span>{{ event.end }}
                </div>
                <div v-if="event.ticketing_provider !== null"><p><span class="font-semibold">Ticket Provider:</span> {{ event.ticketing_provider }}</p></div>
                <div v-if="event.ticket_url !== null"><p><span class="font-semibold">Ticket Link:</span> <a :href="event.ticket_url">Tickets</a></p></div>

                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="event.event_pic_url" :alt="`${event.name} Picture`"
                    class="h-auto max-w-sm object-cover aspect-ratio" />
            </figure>
        </div>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <a :href="`/event/createApplication/${event.id}`" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Application Form</a>
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Applications</th>
                        <th>Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="form in forms" :key="event.id">
                        <td>{{ form.type }}</td>
                        <td>{{ form.name }}</td>
                        <td class="link link-primary">
                            <a :href="`/event/applications/${form.id}`">{{ form.application_count }}</a> <span v-if="form.new_application_count > 0" class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">New</span>
                        </td>
                        <td class="link link-primary">
                            <a :href="`/apply/${form.name}`">Form Link</a>
                        </td>
                        <td>
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button v-if="form.published === 0" @click="publishForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Publish
                                </button>
                                <button v-else @click="unPublishForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    UnPublish
                                </button>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Edit
                                </button>
                                <button @click="deleteForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        <!-- Event Update Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Event
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.name" label="Event name" required />
                    <fwb-input v-model="form.location" label="Location" />
                    <fwb-select v-model="form.venue_id" :options="venueList" label="Venue" required />
                    <fwb-textarea v-model="form.description" :rows="2" label="description" placeholder="Tell us about your event..." />
                    <fwb-input v-model="form.start" label="Start datetime" required />
                    <fwb-input v-model="form.end" label="End datetime" required />
                    <fwb-checkbox v-model="form.free" label="Is the event free entry?" />
                    <fwb-checkbox v-model="form.all_ages" label="Is this an All Ages event?" />
                    <fwb-input v-model="form.ticket_provider" label="Ticket Provider" />
                    <fwb-input v-model="form.ticket_url" label="Ticket Link" />
                    <fwb-file-input v-model="form.event_pic_url" label="Upload event photo" />
                </p>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeModal" color="alternative">
                        Reset
                    </fwb-button>
                    <fwb-button @click="updateEvent" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>
