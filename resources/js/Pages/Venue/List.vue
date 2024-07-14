<template>
    <AppLayout title="Venue">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="header-container">
                        <h1 class="title">Venue List</h1>
                        <!-- <button class="add-venue-button" @click="goToCreateVenue">
                            Add Venue
                            <i class="fas fa-plus"></i>
                        </button> -->
                    </div>
                    <div class="overflow-x-auto">
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
                                <tr v-for="venue in venues" :key="venue.id">
                                    <td>{{ venue.id }}</td>
                                    <td>{{ venue.name }}</td>
                                    <td>{{ venue.capacity }}</td>
                                    <td>{{ venue.location }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3'

export default {
    props: {
        venues: {
            type: Array,
            required: true
        }
    },
    methods: {
        goToCreateVenue() {
            router.visit(route('createVenue'));
        },
        goToEditVenue(id) {
            router.visit(route('venue.edit', { id }));
        },
        deleteVenue(id) {
            if (confirm('Are you sure you want to delete this venue?')) {
                router.delete(route('venue.destroy', { id }));
            }
        }
    },
    components: {
        AppLayout
    }
}
</script>

<style scoped>
.header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.title {
    flex: 1;
    text-align: center;
    margin: 0;
    font-size: 2em;
    color: #333;
}

.venue-table {
    width: 100%;
    border-collapse: collapse;
}

.venue-table th,
.venue-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.venue-table th {
    background-color: #f0f0f0;
}
</style>
