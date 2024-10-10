<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps(['venues']);

const photoInput = ref(null);

var form = {
    name: '',
    venue_id: '',
    start: '',
    end: '',
    description: '',
    ticketing_provider: '',
    free: '',
    all_ages: '',
    pic_url: '',
    ticket_url: '',
    location: '',
};

function createEvent() {
    router.post('/event/create', this.form);
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    form.pic_url = photo;
};
</script>

<template>
    <AppLayout title="Create Event">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Create Event
        </h2>
      </template>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form @submit.prevent="createVenue">
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-200">Name</label>
                    <input v-model="form.name" type="text" id="name" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="venue_id" class="block text-gray-700 dark:text-gray-200">Venue</label>
                    <select
                        id="venue_id"
                        v-model="form.venue_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option v-for="venue in venues" :value="venue.id">{{ venue.venue_name }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="start" class="block text-gray-700 dark:text-gray-200">Event start</label>
                    <VueDatePicker v-model="form['start']" dark></VueDatePicker>
                </div>
                <div class="mb-4">
                    <label for="end" class="block text-gray-700 dark:text-gray-200">Event end</label>
                    <VueDatePicker v-model="form['end']" dark></VueDatePicker>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-200">Description</label>
                    <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="pic_url" class="block text-gray-700 dark:text-gray-200">Event Pic</label>
                    <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                        Select A New Photo
                    </SecondaryButton>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-gray-700 dark:text-gray-200">Location</label>
                    <input v-model="form.location" type="text" id="location" class="mt-1 block w-full" />
                </div>
                <div class="mb-4">
                    <label for="ticketing_provider" class="block text-gray-700 dark:text-gray-200">Ticketing Provider</label>
                    <select
                        id="ticketing_provider"
                        v-model="form.ticketing_provider"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="" selected></option>
                        <option value="oztix">Oztix</option>
                        <option value="humanitix">Humanitix</option>
                        <option value="eventbrite">EventBrite</option>
                        <option value="stickytickets">Sticky Tickets</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="ticket_url" class="block text-gray-700 dark:text-gray-200">Ticket URL</label>
                    <input v-model="form.ticket_url" type="text" id="location" class="mt-1 block w-full"  />
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <label id="free" for="free" class="block text-gray-700 dark:text-gray-200">Free Event?</label>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <label for="all_ages" class="block text-gray-700 dark:text-gray-200">All Ages?</label>
                        <input id="all_ages" type="checkbox" class="sr-only peer" :value="form.all_ages">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div class="mb-4">
                    <button type="submit" @click="createEvent()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create Event</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
