<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router} from '@inertiajs/vue3';
import { ref } from 'vue';
import { FwbButton, FwbModal, FwbFileInput, FwbInput, FwbTextarea, FwbSelect } from 'flowbite-vue'

const props = defineProps(['venue']);

const isShowModal = ref(false);

var form = {
    id: props.venue.id,
    venue_name: props.venue.venue_name,
    email: props.venue.email,
    bio: props.venue.bio,
    pic_url: props.venue.pic_url,
    location: props.venue.location,
    capacity: props.venue.capacity,
    standard_fee: props.venue.standard_fee,         // in dollars - may not match negotiated fee for an event
    ticket_cut: props.venue.ticket_cut,             // in dollars
    fee_type: props.venue.fee_type,                 // Is this a total price, or a minimum backed with a ticket cut?
    cut_type: props.venue.cut_type,                 // If a ticket cut, is it per ticket cost, or a percentage?
    additional_fees: props.venue.additional_fees,   // eg: sound/lighting tech, door person, backline, etc
    tech_specs: props.venue.tech_specs,
    backline: props.venue.backline,                 // if provided
    state: props.venue.state,
};

const cutTypes = [
    {value: '', name: 'N/A',},
    {value: 'per_ticket', name: 'Per Ticket',},
    {value: 'percentage', name: 'Percentage',},
];
const feeTypes = [
    {value: '', name: 'No fee',},
    {value: 'total', name: 'Total',},
    {value: 'minimum', name: 'Minimum',},
];

function closeModal () {isShowModal.value = false;}
function showModal () {isShowModal.value = true;}
function destroy(){router.post(`/venue/delete/${props.venue.id}`);}
function updateVenue() {
    router.post('/venue/update', form);
    closeModal();
};
</script>

<template>
    <AppLayout>
        <h1 class="text-2xl font-semibold text-center pt-10">Venue Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">Name: {{ venue.venue_name }}</h2>
                <div class="mt-4 font-normal flex flex-col justify-between p-4 leading-normal">
                    <p><span class="font-semibold">Email:</span> {{ venue.email }}</p>
                    <p><span class="font-semibold">Location:</span> {{ venue.location }} </p>
                    <p v-if="venue.bio !== null"><br><span v-html="venue.bio.replace(/\r?\n/g, '<br />')"></span></p>
                    <p><br><span class="font-semibold">Capacity:</span> {{ venue.capacity || 0 }} </p>
                    <p><span class="font-semibold">Fee:</span> ${{ venue.standard_fee || "Free" }} {{  venue.fee_type }}</p>
                    <div v-if="venue.ticket_cut !== null"><p><span class="font-semibold">Fee:</span> <span v-if="venue.cut_type == 'per_ticket'">$</span>{{ venue.ticket_cut}} {{ venue.cut_type }}</p></div>
                    <div v-if="venue.additional_fees !== null"><p><span class="font-semibold">Additional Fees:</span> <span v-html="venue.additional_fees.replace(/\r?\n/g, '<br />')"></span> </p></div>
                    <div v-if="venue.tech_specs !== null"><p><span class="font-semibold">Tech Specs:</span> <span v-html="venue.tech_specs.replace(/\r?\n/g, '<br />')"></span> </p></div>
                    <div v-if="venue.backline !== null"><p><span class="font-semibold">Backline:</span> <span v-html="venue.backline.replace(/\r?\n/g, '<br />')"></span> </p></div>
                </div>
                <div class="overflow-x-auto m-10 text-right">
                    <fwb-button @click="showModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update</fwb-button>
                    <button @click="destroy()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                </div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="venue.pic_url" :alt="`${venue.venue_name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>
        <!-- Venue Update Modal modal -->
        <fwb-modal v-if="isShowModal" @close="closeModal" position="center">
            <template #header>
                <div class="flex items-center text-lg">
                    Update Venue
                </div>
            </template>
            <template #body>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    <fwb-input v-model="form.venue_name" label="Venue name" required />
                    <fwb-input v-model="form.email" label="Email" required />
                    <fwb-input v-model="form.location" label="Location" />
                    <fwb-textarea v-model="form.bio" :rows="2" label="Description" placeholder="Tell us about your venue..." />
                    <fwb-file-input v-model="form.pic_url" label="Upload venue photo" />
                    <fwb-input v-model="form.capacity" label="Capacity" required />
                    <fwb-input v-model="form.standard_fee" label="Standard fee" required />
                    <fwb-select v-model="form.fee_type" :options="feeTypes" label="Fee type" required />
                    <fwb-input v-model="form.ticket_cut" label="Ticket Cut" required />
                    <fwb-select v-model="form.cut_type" :options="cutTypes" label="Cut type" required />
                    <fwb-textarea v-model="form.additional_fees" :rows="2" label="Additional Fees" placeholder="List any additional fees here..." />
                    <fwb-textarea v-model="form.tech_specs" :rows="2" label="tech Specs" placeholder="FoH & Lighting details..." />
                    <fwb-textarea v-model="form.backline" :rows="2" label="Backline" placeholder="Backline details..." />
                </p>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeModal" color="alternative">
                        Reset
                    </fwb-button>
                    <fwb-button @click="updateVenue" color="green">
                        Submit
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AppLayout>
</template>
