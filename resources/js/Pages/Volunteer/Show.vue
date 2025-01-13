<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const props = defineProps(['volunteer']);

const isShowModal = ref(false);

var form = {
    id: props.volunteer.id,
    name: props.volunteer.name,
    email: props.volunteer.email,
    bio: props.volunteer.bio,
    pic_url: props.volunteer.pic_url,
    location: props.volunteer.location,
    state: props.volunteer.state,
};

function closeModal () {
  isShowModal.value = false
}
function showModal () {
  isShowModal.value = true
}

function updateVolunteer() {
    router.post('/volunteer/update', form);
    closeModal();
};

function destroy(){
    router.post(`/volunteer/delete/${props.volunteer.id}`);
}
</script>

<template>
    <AppLayout title="Volunteer Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Volunteer Details</h1>
        <div class="card lg:card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ volunteer.name }}</h2>
                <div class="mt-3 font-normal flex flex-col justify-between p-3 leading-normal">
                    <p><span class="font-semibold">Email:</span> {{ volunteer.email }}</p>
                    <p><span class="font-semibold">Location:</span> {{ volunteer.location }} </p>
                    <p><br /></p>
                    <p class="italic" v-if="volunteer.bio !== '' && volunteer.bio !== null" v-html="volunteer.bio.replace(/\r?\n/g, '<br />')"></p>
                    <p><br /></p>
                    <!--<p><span class="font-semibold">Notes: </span> <span v-if="volunteer.notes !== '' && volunteer.notes !== null" v-html="volunteer.notes.replace(/\r?\n/g, '<br />')"></span></p>-->
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="volunteer.pic_url" :alt="`${volunteer.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

        <!-- Volunteer Update Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Volunteer
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.name" label="Volunteer name" required />
                    <fwb-input v-model="form.email" label="Contact email" required />
                    <fwb-input v-model="form.location" label="Location" required />
                    <fwb-textarea v-model="form.bio" :rows="2" label="Bio" placeholder="Tell us about your organisation..." />
                    <!--<fwb-textarea v-model="form.notes" :rows="2" label="Notes" />-->
                </p>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeModal" color="alternative">
                        Reset
                    </fwb-button>
                    <fwb-button @click="updateVolunteer" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>