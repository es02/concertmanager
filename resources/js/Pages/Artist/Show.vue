<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Rating from '@/Components/Rating.vue';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { FwbCard, FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const props = defineProps(['artist']);

const photoInput = ref(null);

const isShowModal = ref(false)

const ratings = [
  { value: '0', name: '0 Stars' },
  { value: '1', name: '1 Star' },
  { value: '2', name: '2 Stars' },
  { value: '3', name: '3 Stars' },
  { value: '4', name: '4 Stars' },
  { value: '5', name: '5 Stars' },
]

var form = {
    id: props.artist.id,
    name: props.artist.name,
    email: props.artist.email,
    genre: props.artist.genre,
    rating: props.artist.rating,
    bio: props.artist.bio,
    pic_url: props.artist.pic_url,
    location: props.artist.location,
    booked_previously: props.artist.booked_previously,
    blacklisted: props.artist.blacklisted,
    standard_fee: props.artist.standard_fee,
    standard_rider: props.artist.standard_rider,
    epk_url: props.artist.epk_url,
    tech_specs: props.artist.tech_specs,
    blacklisted: props.artist.blacklisted,
    formed: props.artist.formed,
    notes: props.artist.notes,
    state: props.artist.state,
};

if (form.blacklisted === '') {
    form.blacklisted = 0;
}

function closeModal () {
  isShowModal.value = false
}
function showModal () {
  isShowModal.value = true
}

function updateArtist() {
    router.post('/artist/update', form);
    closeModal();
};

function destroy(){
    router.post(`/artist/delete/${props.artist.id}`);
}


</script>

<template>
    <AppLayout title="Artist Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Artist Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ artist.name }}
                    <Rating :rating="artist.rating"></Rating>
                    <span v-if="artist.booked_previously === 1" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Previously Booked</span>
                    <span v-if="artist.blacklisted === 1 || artist.blacklisted === 'Yes'" class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Blacklisted</span>
                </h2>
                <div class="mt-4 font-normal flex flex-col justify-between p-4 leading-normal">
                    <p v-if="artist.formed !== null && artist.formed !== ''"><span class="font-semibold">Formed:</span> {{ artist.formed }}</p>
                    <p><span class="font-semibold">Email:</span> {{ artist.email }}</p>
                    <p><span class="font-semibold">Genre:</span> {{ artist.genre }}</p>
                    <p><span class="font-semibold">Location:</span> {{ artist.location }} </p>
                    <p><span class="font-semibold">Fee:</span> <span v-if="!isNaN(artist.standard_fee)">$</span>{{ artist.standard_fee }} </p>
                    <p><br /></p>
                    <p class="italic" v-if="artist.bio !== '' && artist.bio !== null" v-html="artist.bio.replace(/\r?\n/g, '<br />')"></p>
                    <div v-if="artist.standard_rider !== null"><p><br><span class="font-semibold">Hospitality Rider:</span> {{ artist.standard_rider }}</p></div>
                    <p v-if="artist.tech_specs !== ''"><span class="font-semibold">Tech Rider:</span> {{ artist.tech_specs }}</p>
                    <p v-if="artist.epk_url !== ''"><span class="font-semibold">EPK:</span> <a :href="artist.epk_url">{{ artist.epk_url }}</a></p>
                    <p><br /></p>
                    <p><span class="font-semibold">Notes:</span> {{ artist.notes }} </p>
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="artist.pic_url" :alt="`${artist.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

        <!-- <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
        <fwb-card
            :img-alt="`${artist.name} Profile Picture`"
            :img-src="artist.pic_url"
            variant="horizontal"
        >
            <div class="p-5">
                <h2 class="card-title">{{ artist.name }}
                    <Rating :rating="artist.rating"></Rating>
                    <span v-if="artist.booked_previously === 1" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Previously Booked</span>
                    <span v-if="artist.blacklisted === 1 || artist.blacklisted === 'Yes'" class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Blacklisted</span>
                </h2>
                <div class="mt-4 font-normal flex flex-col justify-between p-4 leading-normal">
                    <p v-if="artist.formed !== null && artist.formed !== ''"><span class="font-semibold">Formed:</span> {{ artist.formed }}</p>
                    <p><span class="font-semibold">Email:</span> {{ artist.email }}</p>
                    <p><span class="font-semibold">Genre:</span> {{ artist.genre }}</p>
                    <p><span class="font-semibold">Location:</span> {{ artist.location }} </p>
                    <p><span class="font-semibold">Fee:</span> <span v-if="!isNaN(artist.standard_fee)">$</span>{{ artist.standard_fee }} </p>
                    <p><br /></p>
                    <p class="italic" v-if="artist.bio !== ''" v-html="artist.bio.replace(/\r?\n/g, '<br />')"></p>
                    <div v-if="artist.standard_rider !== null"><p><br><span class="font-semibold">Hospitality Rider:</span> {{ artist.standard_rider }}</p></div>
                    <p v-if="artist.tech_specs !== ''"><span class="font-semibold">Tech Rider:</span> {{ artist.tech_specs }}</p>
                    <p v-if="artist.epk_url !== ''"><span class="font-semibold">EPK:</span> <a :href="artist.epk_url">{{ artist.epk_url }}</a></p>
                    <p><br /></p>
                    <p><span class="font-semibold">Notes:</span> {{ artist.notes }} </p>
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
        </fwb-card></div> -->

        <!-- Application Entry Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Artist
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.name" label="Artist name" required />
                    <fwb-select v-model="form.rating" :options="ratings" label="Rate artist" />
                    <fwb-input v-model="form.email" label="Contact email" required />
                    <fwb-checkbox v-model="form.booked_previously" label="Booked previously?" />
                    <fwb-input v-model="form.genre" label="Genre(s)" required />
                    <fwb-input v-model="form.location" label="Location" required />
                    <fwb-input v-model="form.standard_fee" label="Fee" required />
                    <fwb-textarea v-model="form.bio" :rows="2" label="Bio" placeholder="Tell us about your band..." />
                    <fwb-textarea v-model="form.standard_rider" :rows="2" label="Hospitality Rider" placeholder="Any rider requirements?" />
                    <fwb-textarea v-model="form.tech_specs" :rows="2" label="Tech Rider" placeholder="Any tech requirements, eg: DI, IEM, etc" />
                    <fwb-input v-model="form.epk_url" label="EPK link" required />
                    <fwb-file-input v-model="form.pic_url" label="Upload band photo" />
                    <fwb-checkbox v-model="form.blacklisted" label="Blacklisted?" />
                    <fwb-textarea v-model="form.notes" :rows="2" label="Notes" />
                </p>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeModal" color="alternative">
                        Reset
                    </fwb-button>
                    <fwb-button @click="updateArtist" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>
