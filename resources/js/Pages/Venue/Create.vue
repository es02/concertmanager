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
              <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-200">Name</label>
                <input v-model="form.name" type="text" id="name" class="mt-1 block w-full" required />
              </div>
              <div class="mb-4">
                <label for="email" class="block text-gray-700 dark:text-gray-200">Email</label>
                <input v-model="form.email" type="email" id="email" class="mt-1 block w-full" required />
              </div>
              <div class="mb-4">
                <label for="password" class="block text-gray-700 dark:text-gray-200">Password</label>
                <input v-model="form.password" type="password" id="password" class="mt-1 block w-full" required />
              </div>
              <div class="mb-4">
                <label for="bio" class="block text-gray-700 dark:text-gray-200">Bio</label>
                <textarea v-model="form.bio" id="bio" class="mt-1 block w-full"></textarea>
              </div>
              <div class="mb-4">
                <label for="pic_url" class="block text-gray-700 dark:text-gray-200">Picture URL</label>
                <input v-model="form.pic_url" type="text" id="pic_url" class="mt-1 block w-full" />
              </div>
              <div class="mb-4">
                <label for="location" class="block text-gray-700 dark:text-gray-200">Location</label>
                <input v-model="form.location" type="text" id="location" class="mt-1 block w-full" required />
              </div>
              <div class="mb-4">
                <label for="capacity" class="block text-gray-700 dark:text-gray-200">Capacity</label>
                <input v-model="form.capacity" type="number" id="capacity" class="mt-1 block w-full" required />
              </div>
              <div class="mb-4">
                <label for="standard_fee" class="block text-gray-700 dark:text-gray-200">Standard Fee</label>
                <input v-model="form.standard_fee" type="number" id="standard_fee" class="mt-1 block w-full" required />
              </div>
              <!-- Add other fields as necessary -->
              <div class="mb-4">
                <button type="submit" class="create-venue-button">Create Venue</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { router } from '@inertiajs/vue3'

  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: '',
          bio: '',
          pic_url: '',
          location: '',
          capacity: '',
          standard_fee: '',
          // add other fields as necessary
        }
      };
    },
    methods: {
      createVenue() {
        router.post('/venue/create', this.form);
      }
    },
    components: {
      AppLayout
    }
  };
  </script>

  <style scoped>
  .create-venue-button {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
  }

  .create-venue-button:hover {
    background-color: #0056b3;
  }
  </style>
