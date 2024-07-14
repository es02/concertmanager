<template>
    <AppLayout title="Venue">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Venue
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="header-container">
                        <h1 class="title">Venue List</h1>
                        <button class="add-venue-button" @click="goToCreateVenue">
                            Add Venue
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <ul class="venue-list">
                        <li v-for="venue in venues" :key="venue.id" class="venue-item">
                            <div class="venue-pic">
                                <img :src="venue.pic_url" alt="Venue Picture" />
                            </div>
                            <div class="venue-details">
                                <h2 class="venue-name">{{ venue.name }}</h2>
                                <p class="venue-location"><strong>Location:</strong> {{ venue.location }}</p>
                                <p class="venue-email"><strong>Email:</strong> {{ venue.email }}</p>
                                <p class="venue-fee"><strong>Standard Fee:</strong> ${{ venue.standard_fee }}</p>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                                Edit
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded"
                                v-on:click="removeElement(item.id)">
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    props: {
        venues: {
            type: Array,
            required: true
        }
    },
    methods: {
        goToCreateVenue() {
            this.$inertia.visit(route('venue.create'));
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
    justify-content: center;
}

.venue-list-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.title {
    text-align: center;
    font-size: 2em;
    margin-bottom: 20px;
    color: #333;
}

.venue-list {
    list-style: none;
    padding-left: 0;
}

.venue-item {
    display: flex;
    align-items: center;
    background-color: #fff;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.venue-item:hover {
    transform: translateY(-2px);
}

.venue-pic img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 8px;
    margin-right: 20px;
}

.venue-details {
    flex: 1;
}

.venue-name {
    font-size: 1.5em;
    margin: 0;
    color: #333;
}

.venue-location,
.venue-email,
.venue-fee {
    margin: 5px 0;
    color: #666;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination-button {
    padding: 10px 15px;
    margin: 0 5px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.pagination-button:hover {
    background-color: #0056b3;
}
</style>
