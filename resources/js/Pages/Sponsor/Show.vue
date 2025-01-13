<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const props = defineProps(['sponsor']);

const isShowModal = ref(false);

var form = {
    id: props.sponsor.id,
    name: props.sponsor.name,
    email: props.sponsor.email,
    bio: props.sponsor.bio,
    pic_url: props.sponsor.pic_url,
    location: props.sponsor.location,
    state: props.sponsor.state,
};

function closeModal () {
  isShowModal.value = false
}
function showModal () {
  isShowModal.value = true
}

function updateSponsor() {
    router.post('/sponsor/update', form);
    closeModal();
};

function destroy(){
    router.post(`/sponsor/delete/${props.sponsor.id}`);
}
</script>

<template>
    <AppLayout title="Sponsor Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Sponsor Details</h1>
        <div class="card lg:card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ sponsor.name }}</h2>
                <div class="mt-3 font-normal flex flex-col justify-between p-3 leading-normal">
                    <p><span class="font-semibold">Email:</span> {{ sponsor.email }}</p>
                    <p><span class="font-semibold">Location:</span> {{ sponsor.location }} </p>
                    <p><br /></p>
                    <p class="italic" v-if="sponsor.bio !== '' && sponsor.bio !== null" v-html="sponsor.bio.replace(/\r?\n/g, '<br />')"></p>
                    <p><br /></p>
                    <!--<p><span class="font-semibold">Notes: </span> <span v-if="sponsor.notes !== '' && sponsor.notes !== null" v-html="sponsor.notes.replace(/\r?\n/g, '<br />')"></span></p>-->
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="sponsor.pic_url" :alt="`${sponsor.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

        <!-- Sponsor Update Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Sponsor
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.name" label="Sponsor name" required />
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
                    <fwb-button @click="updateSponsor" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>