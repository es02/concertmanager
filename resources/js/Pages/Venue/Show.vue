<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link} from '@inertiajs/vue3';
const props = defineProps(['venue']);
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
                <div class="overflow-x-auto m-10 text-right"><button @click="update = !update">Update</button></div>
            </div>
            <figure class="aspect-w-1 h-auto max-w-sm">
                <img :src="venue.pic_url" :alt="`${venue.venue_name} Profile Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>

    </AppLayout>
</template>
