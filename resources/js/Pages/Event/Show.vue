<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3'

const props = defineProps(['event', 'stages', 'sets', 'forms']);

function deleteForm(id) { router.post(`/event/deleteApplication/${id}`);}
function publishForm(id) { router.post(`/event/publishApplication/${id}`);}
function unPublishForm(id) { router.post(`/event/unpublishApplication/${id}`);}
</script>

<template>
    <AppLayout title="Event Details">
        <h1 class="text-2xl font-semibold text-center pt-10">Event Details</h1>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <h2 class="card-title">{{ event.name }}</h2>
                <span v-if="event.all_ages !== 0" class="font-semibold">ALL AGES</span> <span v-if="event.free !== 0" class="font-semibold">FREE EVENT</span>
                <p class="text-sm text-gray-500">{{ event.venue_name }} - {{ event.location }}</p>
                <p v-html="event.description.replace(/\r?\n/g, '<br />')"></p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Start Date: </span>{{ event.start }}
                </div>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">End Date: </span>{{ event.end }}
                </div>
                <div v-if="event.ticketing_provider !== null"><p><span class="font-semibold">Ticket Provider:</span> {{ event.ticketing_provider }}</p></div>
                <div v-if="event.ticket_url !== null"><p><span class="font-semibold">Ticket Link:</span> <a :href="event.ticket_url">Tickets</a></p></div>

                <div class="overflow-x-auto m-10 text-right"><button @click="update = !update">Update</button></div>
            </div>
            <figure class="aspect-w-1 aspect-h-1 w-1/2">
                <img :src="event.event_pic_url" :alt="`${event.name} Picture`"
                    class="h-full object-cover aspect-ratio" />
            </figure>
        </div>
        <div class="card card-side bg-base-100 shadow-xl border m-10 max-w-screen">
            <div class="card-body">
                <a :href="`/event/createApplication/${event.id}`" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">New Application Form</a>
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Applications</th>
                        <th>Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="form in forms" :key="event.id">
                        <td>{{ form.type }}</td>
                        <td>{{ form.name }}</td>
                        <td class="link link-primary">
                            <a :href="`/event/applications/${form.id}`">{{ form.application_count }}</a>
                        </td>
                        <td class="link link-primary">
                            <a :href="`/apply/${form.name}`">Form Link</a>
                        </td>
                        <td>
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button v-if="form.published === 0" @click="publishForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Publish
                                </button>
                                <button v-else @click="unPublishForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    UnPublish
                                </button>
                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Edit
                                </button>
                                <button @click="deleteForm(form.id)" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </AppLayout>
</template>
