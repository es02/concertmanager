<script setup>
import {ref, computed, reactive} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from '@/Components/LongTextInput.vue';
import NewFormImage from '@/Components/NewFormImage.vue';
import NewFormItem from '@/Components/NewFormItem.vue';
import NewQuestionIcon from '@/Components/Icons/NewQuestionIcon.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps(['event']);

const form = useForm({
    name: '',
    type: 'artist',
    desc: '',
    start: '',
    end: '',
    entries: [],
});

const truth = 1;
var entryCount = 0;

var defaultEntry = {
    id: entryCount,
    hasFocus: true,
    entryType: 'text',
    entryName: 'Untitled Question',
    entryDescription: '',
    entryMappedField: '',
    entryMandatory: false,
    entryOptions: [],
};

var defaultImage = {
    id: entryCount,
    hasFocus: true,
    entryType: 'image',
    entryDescription: '',
    entryImage: '',
};

// pre-seed the form with a header image and a single question
form.entries.push(clone(defaultImage));
entryCount++;
defaultEntry.id = entryCount;
defaultImage.id = entryCount;
form.entries.push(clone(defaultEntry));

function newQuestion() {
    for (const [key, value] of Object.entries(form.entries)) {
        value.hasFocus = false
    }
    entryCount++;
    defaultEntry.id = entryCount;
    defaultImage.id = entryCount;

    form.entries.push(clone(defaultEntry));
}

function newImage() {
    for (const [key, value] of Object.entries(form.entries)) {
        value.hasFocus = false
    }
    entryCount++;
    defaultEntry.id = entryCount;
    defaultImage.id = entryCount;

    form.entries.push(clone(defaultImage));
}

function setHasFocus(id) {
    for (const [key, value] of Object.entries(form.entries)) {
        value.hasFocus = false
    }
    form.entries[id].hasFocus = true;
}

function setMandatory(id) {
    if (form.entries[id].entryMandatory === true){
        form.entries[id].entryMandatory = false;
    } else {
        form.entries[id].entryMandatory = true;
    }

}

function deleteEntry(id){
    form.entries.splice(id, 1);
    entryCount--;
    // re-sync the ids or focus breaks
    for (const [key, value] of Object.entries(form.entries)) {
        form.entries[key].id = Number(key);
    }
}

function updateEntry(id, entry){
    if (form.entries[id].entryType === 'image') {
        form.entries[id].entryDescription = entry.entryDescription;
    } else {
        form.entries[id].entryType = entry.entryType;
        form.entries[id].entryName = entry.entryName;
        form.entries[id].entryDescription = entry.entryDescription;
        form.entries[id].entryMappedField = entry.entryMappedField;
        form.entries[id].entryOptions = entry.entryOptions;
    }
}

function addEntryOption(id, option) {
    form.entries[id].entryOptions.push(option);
}

function updateEntryOption(id, optionId, option){
    form.entries[id].entryOptions[optionId] = option;
}

function deleteEntryOption(id,optionId) {
    form.entries[id].entryOptions.splice(optionId, 1);
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

const createForm = () => {
    form.post(route('event.form.new', props.event.id), {
        // errorBag: 'applyForEvent',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        // onError: () => {
            //
        // },
    });
};
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
                <button @click="newImage()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    New Image
                </button>
                <PrimaryButton @click="createForm()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                        <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                    </svg>
                    Save
                </PrimaryButton>
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
                                ref="name"
                                v-model="form['name']"
                                placeholder="Enter text"
                                type="text"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            />
                        </p>
                        <p>
                            <InputLabel for="formType" value="Form Type" :mandatory="truth"/>
                            <select
                                id="formType"
                                v-model="form['type']"
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
                        v-model="form['desc']"
                        placeholder="Enter text"
                        type="text"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <div class="max-w-7xl mx-auto py-10  grid grid-cols-1 gap-20 sm:grid-cols-2">
                        <p>
                            <InputLabel for="formStart" value="Applications Open" :mandatory="truth" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Form will be live from this date.</p>
                            <VueDatePicker v-model="form['start']" dark></VueDatePicker>
                        </p>
                        <p>
                            <InputLabel for="formEnd" value="Applications Close" :mandatory="truth" />
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Form will no longer accept submissions from this date.</p>
                            <VueDatePicker v-model="form['end']" dark></VueDatePicker>
                        </p>
                    </div>
                </div>
                <div class="py-3" v-for="entry in form.entries" :key="entry.id"  >
                    <div v-if="entry.entryType === 'image'">
                        <div :id="entry.id" @click="setHasFocus(entry.id)">
                            <NewFormImage
                                :entry="entry"
                                @delete="deleteEntry"
                                @updateEntry="updateEntry"
                                v-model="form.entries[entry.id].entryImage"
                            />
                        </div>
                    </div>
                    <div v-else>
                        <div :id="entry.id" @click="setHasFocus(entry.id)">
                            <NewFormItem
                                :entry="entry"
                                @addEntryOption="addEntryOption"
                                @delete="deleteEntry"
                                @deleteEntryOption="deleteEntryOption"
                                @entryMandatory="setMandatory"
                                @updateEntry="updateEntry"
                                @updateEntryOption="updateEntryOption"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
