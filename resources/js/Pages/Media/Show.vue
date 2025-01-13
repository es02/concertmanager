<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbCheckbox, FwbSelect } from 'flowbite-vue'

const props = defineProps(['media']);

const isShowModal = ref(false);

var form = {
    id: props.media.id,
    name: props.media.name,
    email: props.media.email,
    bio: props.media.bio,
    pic_url: props.media.pic_url,
    location: props.media.location,
    state: props.media.state,
};

function closeModal () {
  isShowModal.value = false
}
function showModal () {
  isShowModal.value = true
}

function updateMedia() {
    router.post('/media/update', form);
    closeModal();
};

function destroy(){
    router.post(`/media/delete/${props.media.id}`);
}
</script>

<template>
    <AppLayout title="Media Profile">
        <h1 class="text-2xl font-semibold text-center pt-10">Media Details</h1>
        <div class="card lg:card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ media.name }}</h2>
                <div class="mt-3 font-normal flex flex-col justify-between p-3 leading-normal">
                    <p><span class="font-semibold">Email:</span> {{ media.email }}</p>
                    <p><span class="font-semibold">Location:</span> {{ media.location }} </p>
                    <p><br /></p>
                    <p class="italic" v-if="media.bio !== '' && media.bio !== null" v-html="media.bio.replace(/\r?\n/g, '<br />')"></p>
                    <p><br /></p>
                    <!--<p><span class="font-semibold">Notes: </span> <span v-if="media.notes !== '' && media.notes !== null" v-html="media.notes.replace(/\r?\n/g, '<br />')"></span></p>-->
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="media.pic_url" :alt="`${media.name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

        <!-- Media Update Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Media
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.name" label="Media name" required />
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
                    <fwb-button @click="updateMedia" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>