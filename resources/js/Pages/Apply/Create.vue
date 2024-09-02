<script setup>
import {ref, computed, reactive} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import 'flowbite';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from '@/Components/LongTextInput.vue';
import NewFormItem from '@/Components/NewFormItem.vue';
import NewQuestionIcon from '@/Components/Icons/NewQuestionIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['event']);

var temp = [];
const entries = reactive(temp);
var formName;
var formDescription;
var formStart;
var formEnd;

var keys = {
    name: '',
    desc: '',
    start: '',
    end: '',
    entries: [],
}

const form = useForm(keys);

const truth = 1;
var entryCount = 0;

var defaultEntry = {
    id: entryCount,
    hasFocus: true,
    entryType: 'text',
    entryName: 'Untitled Question',
    entryDescription: '',
    entryMappedField: '',
    entryMandatory: true,
    entryOptions: {},
};

temp.push(clone(defaultEntry));

function newQuestion() {
    for (const [key, value] of Object.entries(entries)) {
        value.hasFocus = false
    }
    entryCount++;
    defaultEntry.id = entryCount;

    entries.push(clone(defaultEntry));
}

function setHasFocus(id) {
    for (const [key, value] of Object.entries(entries)) {
        value.hasFocus = false
    }
    entries[id].hasFocus = true;
}

function setMandatory(id) {
    if (entries[id].entryMandatory === true){
        entries[id].entryMandatory = false;
    } else {
        entries[id].entryMandatory = true;
    }

}

function deleteEntry(id){
    entries.splice(id, 1);
    entryCount--;
    // re-sync the ids or focus breaks
    for (const [key, value] of Object.entries(entries)) {
        entries[key].id = Number(key);
    }
}

function deleteEntryOption(id,optionId) {
    entries[id].entryOptions.splice(optionId, 1);
}

// I hate this stupid language so much
function clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}
</script>

<template>
    <AppLayout title="Event Application - New">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Application Form for {{ event.name }}
            </h2>
        </template>

        <div class="text-center py-6 ">
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button @click="newQuestion()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    New Question
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    New Image
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                    <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                    </svg>
                    Save
                </button>
            </div>
        </div>
        <div class="max-w-6xl mx-auto py-3 sm:px-6 lg:px-10 grid grid-cols-1 gap-500 sm:grid-cols-1">
            <div></div>

            <div>
                <div class="p-6 col-span-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto py-10  grid grid-cols-1 gap-20 sm:grid-cols-2">
                        <p>
                            <InputLabel for="Name" value="Form Name" :mandatory="truth" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">This is your URL link so please use all lowercase with no spaces or special characters.</p>
                            <TextInput
                                id="Name"
                                ref="formName"
                                :v-model="formName"
                                placeholder="Enter text"
                                type="text"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            />
                        </p>
                        <p>
                            <InputLabel for="formType" value="Form Type" :mandatory="truth"/>
                            <select
                                id="formType"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option value="artist" selected>Artist</option>
                                <option value="volunteer">Volunteer</option>
                                <option value="media">Media</option>
                            </select>
                        </p>
                    </div>

                    <InputLabel for="Description" value="Event Description" :mandatory="truth"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Describe your event to potential applicants.</p>
                    <LongTextInput
                        id="Description"
                        ref="formDescription"
                        :v-model="formDescription"
                        placeholder="Enter text"
                        type="text"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <div class="max-w-7xl mx-auto py-10  grid grid-cols-1 gap-20 sm:grid-cols-2">
                        <p>
                            <InputLabel for="formStart" value="Applications Open" :mandatory="truth" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Form will be live from this date.</p>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input
                                    id="datepicker-Start"
                                    datepicker
                                    datepicker-buttons
                                    datepicker-autoselect-today
                                    datepicker-format="dd/mm/yyyy"
                                    type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date"
                                >
                            </div>
                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input
                                    type="time"
                                    id="timeStart"
                                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    min="09:00"
                                    max="18:00"
                                    value="00:00"
                                    required
                                />
                            </div>
                        </p>
                        <p>
                            <InputLabel for="formEnd" value="Applications Close" :mandatory="truth" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Form will no longer accept submissions from this date.</p>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input
                                    id="datepicker-End"
                                    datepicker
                                    datepicker-buttons
                                    datepicker-autoselect-today
                                    datepicker-format="dd/mm/yyyy"
                                    type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date"
                                >
                            </div>
                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input
                                    type="time"
                                    id="timeEnd"
                                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    min="09:00"
                                    max="18:00"
                                    value="00:00"
                                    required
                                />
                            </div>
                        </p>
                    </div>
                </div>
                <div class="py-3" v-for="entry in entries" :key="entry.id"  >
                    <div>
                        <div :id="entry.id" @click="setHasFocus(entry.id)">
                            <NewFormItem
                                :entry="entry"
                                @on:delete="deleteEntry"
                                @on:deleteEntryOption="deleteEntryOption"
                                @entryMandatory="setMandatory"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
