<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const photoInput = ref(null);

var form = {
    name: '',
    email: '',
    bio: '',
    pic_url: '',
    location: '',
    capacity: '',
    standard_fee: '',
    ticket_cut: '',
    cut_type: '', // 'per_ticket', 'percentage'
    fee_type: '', // 'total', 'minimum'
    additional_fees: '',
    tech_specs: '',
    backline: '',
};

function createVenue() {
    router.post('/venue/create', form);
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
    <AppLayout title="Create Venue">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Create Venue
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
                    <label for="email" class="block text-gray-700 dark:text-gray-200">Email</label>
                    <input v-model="form.email" type="email" id="email" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 dark:text-gray-200">Bio</label>
                    <textarea v-model="form.bio" id="bio" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="pic_url" class="block text-gray-700 dark:text-gray-200">Picture</label>
                    <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                        Select A New Photo
                    </SecondaryButton>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-gray-700 dark:text-gray-200">Location</label>
                    <input v-model="form.location" type="text" id="location" class="mt-1 block w-full"  />
                </div>
                <div class="mb-4">
                    <label for="capacity" class="block text-gray-700 dark:text-gray-200">Capacity</label>
                    <input v-model="form.capacity" type="number" id="capacity" class="mt-1 block w-full"  />
                </div>
                <div class="mb-4">
                    <label for="standard_fee" class="block text-gray-700 dark:text-gray-200">Standard Fee</label>
                    <input v-model="form.standard_fee" type="number" id="standard_fee" class="mt-1 block w-full"  />
                </div>
                <div class="mb-4">
                    <label for="fee_type" class="block text-gray-700 dark:text-gray-200">Fee Type</label>
                    <select
                        id="fee_type"
                        v-model="form.fee_type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="" selected></option>
                        <option value="total">Total</option>
                        <option value="minimum">Minimum</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="ticket_cut" class="block text-gray-700 dark:text-gray-200">Ticket Cut</label>
                    <input v-model="form.ticket_cut" type="number" id="ticket_cut" class="mt-1 block w-full"  />
                </div>
                <div class="mb-4">
                    <label for="cut_type" class="block text-gray-700 dark:text-gray-200">Cut Type</label>
                    <select
                        id="cut_type"
                        v-model="form.cut_type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="" selected></option>
                        <option value="per_ticket">Per Ticket</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="additional_fees" class="block text-gray-700 dark:text-gray-200">Additional Fees</label>
                    <textarea v-model="form.additional_fees" id="additional_fees" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="tech_specs" class="block text-gray-700 dark:text-gray-200">Tech Specs</label>
                    <textarea v-model="form.tech_specs" id="tech_specs" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="backline" class="block text-gray-700 dark:text-gray-200">Backline</label>
                    <textarea v-model="form.backline" id="backline" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <button type="submit" @click="createVenue()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create Venue</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
