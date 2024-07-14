<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps(['events', 'count']);

const numberOfPages = Math.ceil(props.count / 10);
</script>

<template>
    <AppLayout title="Events">
        <h1 class="text-2xl font-semibold text-center pt-10">Events</h1>
        <div class="overflow-x-auto m-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>Start End</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.id">
                        <td class="link link-primary">
                            <a :href="`/event/${event.id}`">{{ event.name }}</a>
                        </td>
                        <td>{{ event.location }}</td>
                        <td>{{ event.start }}</td>
                        <td>{{ event.end }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join flex justify-center fixed bottom-0 w-full p-4">
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
        </div>
    </AppLayout>
</template>
