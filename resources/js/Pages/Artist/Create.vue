<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { FwbButton, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const ratings = [
  { value: '0', name: '0 Stars' },
  { value: '1', name: '1 Star' },
  { value: '2', name: '2 Stars' },
  { value: '3', name: '3 Stars' },
  { value: '4', name: '4 Stars' },
  { value: '5', name: '5 Stars' },
]

var form = {
    name: '',
    email: '',
    genre: '',
    rating: '',
    bio: '',
    pic_url: '',
    location: '',
    booked_previously: false,
    standard_fee: '',
    standard_rider: '',
    epk_url: '',
    tech_specs: '',
    blacklisted: false,
    formed: '',
    notes: '',
};

function createArtist() {
    router.post('/artist/create', this.form);
};
</script>

<template>
    <AppLayout title="Create Artist">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Create Artist
        </h2>
      </template>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- <form @submit.prevent="createArtist"> -->
                <fwb-input v-model="form.name" label="Artist name" required />
                <fwb-select v-model="form.rating" :options="ratings" label="Rate artist" />
                <fwb-input v-model="form.email" label="Contact email" required />
                <fwb-checkbox v-model="form.booked_previously" label="Booked previously?" />
                <fwb-input v-model="form.genre" label="Genre(s)" required />
                <fwb-input v-model="form.location" label="Location"  />
                <fwb-input v-model="form.standard_fee" label="Fee" />
                <fwb-textarea v-model="form.bio" :rows="2" label="Bio" placeholder="Tell us about your band..." />
                <fwb-textarea v-model="form.standard_rider" :rows="2" label="Hospitality Rider" placeholder="Any rider requirements?" />
                <fwb-textarea v-model="form.tech_specs" :rows="2" label="Tech Rider" placeholder="Any tech requirements, eg: DI, IEM, etc" />
                <fwb-input v-model="form.epk_url" label="EPK link"  />
                <fwb-file-input v-model="form.pic_url" label="Upload band photo" />
                <fwb-checkbox v-model="form.blacklisted" label="Blacklisted?" />
                <fwb-textarea v-model="form.notes" :rows="2" label="Notes" />
                <div class="mb-4">
                    <fwb-button type="submit" @click="createArtist()">Create Artist</fwb-button>
                </div>
            <!-- </form> -->
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
