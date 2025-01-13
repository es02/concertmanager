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
};

function createSponsor() {
    router.post('/sponsor/create', form);
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
    <AppLayout title="Create Sponsor">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Create Sponsor
        </h2>
      </template>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form @submit.prevent="createSponsor">
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
                    <button type="submit" @click="createSponsor()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create Sponsor</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
