
<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    venues: {
        type: Array,
        required: true
    },
    count: {
        type: Number,
        required: true
    }
});

const currentPage = ref(1);
const venuesPerPage = 10;

const numberOfPages = computed(() => Math.ceil(props.count / venuesPerPage));

const paginatedVenues = computed(() => {
    const start = (currentPage.value - 1) * venuesPerPage;
    const end = start + venuesPerPage;
    return props.venues.slice(start, end);
});

const goToCreateVenue = () => {
    router.visit(route('venue.create'));
};

const goToEditVenue = (id) => {
    router.visit(route('venue.edit', { id }));
};

const deleteVenue = (id) => {
    if (confirm('Are you sure you want to delete this venue?')) {
        router.delete(route('venue.destroy', { id }));
    }
};
</script>

<template>
    <AppLayout title="Venues">
        <h1 class="text-2xl font-semibold text-center pt-10">Venues</h1>
        <div class="overflow-x-auto m-10 text-right"><Link :href="route('venue.create')">New Venue</Link></div>
        <div class="overflow-x-auto m-10">

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="venue in paginatedVenues" :key="venue.id">
                        <td>{{ venue.id }}</td>
                        <td class="link link-primary">
                            <Link :href="`/venue/${venue.id}`">{{ venue.venue_name }}</Link>
                        </td>
                        <td>{{ venue.capacity }}</td>
                        <td>{{ venue.location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <div class="join flex justify-center fixed bottom-0 w-full p-4">
            <Link
                v-for="page in numberOfPages"
                :key="page"
                class="join-item btn"
                :href="`/events/${page - 1}`"
                as="button"
                type="button"
            >
                {{ page }}
            </Link>
        </div> -->
        <div class="join flex justify-center fixed bottom-0 w-full p-4">
            <button v-for="page in numberOfPages" :key="page" class="join-item btn" @click="currentPage = page">
                {{ page }}
            </button>
        </div>
    </AppLayout>
</template>
