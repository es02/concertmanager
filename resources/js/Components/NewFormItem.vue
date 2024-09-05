<script setup>
import {ref} from 'vue';
import BinIcon from './Icons/BinIcon.vue';
import DraggableIcon from './Icons/DraggableIcon.vue';
import DropdownIcon from './Icons/DropdownIcon.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from './LongTextInput.vue';
import LongTextInputIcon from './Icons/LongTextInputIcon.vue';
import TextInput from './TextInput.vue';
import TextInputIcon from './Icons/TextInputIcon.vue';

const props = defineProps(['entry']);

const emit = defineEmits(['delete','addEntryOption','deleteEntryOption','updateEntryOption','updateEntry','entryMandatory','click']);

const truth = 1;
const lie = 0;

const mandatory = ref(props.entry.entryMandatory);

const returnEntry = {
    entryName: '',
    entryType: 'text',
    entryDescription: '',
    entryMappedField: '',
}

var options = [];

function addEntryOption(entry) {
    options.push(entry);
    let id = options.findLastIndex(x => x === entry);
    emit('addEntryOption', props.entry.id, id, entry);
}

function updateEntry() {
    emit('updateEntry', props.entry.id, returnEntry);
}

function updateEntryOption(id, entry) {
    emit('updateEntryOption', props.entry.id, id, entry);
}

function deleteEntryOption(id) {
    options.splice(id, 1);
}
</script>

<template>
    <div class="p-3 col-span-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="text-center">:::</div>
        <div v-if="entry.hasFocus" div class="p-3">
            <div class="max-w-7xl mx-auto py-3  grid grid-cols-1 gap-10 sm:grid-cols-2">
                <p>
                    <InputLabel for="entryName" value="Question" :mandatory="truth"/>
                    <TextInput
                        id="entryName"
                        ref="entryName"
                        placeholder="Untitled Question"
                        type="text"
                        v-model="returnEntry.entryName"
                        @input="updateEntry"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                </p>
                <p>
                    <InputLabel for="entryType" value="Question Type" :mandatory="truth"/>
                    <select
                        id="entryType"
                        v-model="returnEntry.entryType"
                        @input="updateEntry"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="text" selected><TextInputIcon />Short Answer</option>
                        <option value="longtext"><LongTextInputIcon />Long Answer</option>
                        <option value="dropdown"><DropdownIcon />Dropdown</option>
                    </select>
                </p>
                <p>
                    <InputLabel for="entryDescription" value="Question Description" :mandatory="truth"/>
                    <LongTextInput
                        id="entryDescription"
                        ref="entryDescription"
                        type="text"
                        v-model="returnEntry.entryDescription"
                        @input="updateEntry"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                </p>
                <p>
                    <InputLabel for="entryMap" value="Map to field" :mandatory="lie"/>
                    <select
                        id="entryMap"
                        v-model="returnEntry.entryMappedField"
                        @input="updateEntry"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option value="none" selected></option>
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="bio">Bio</option>
                        <option value="location">Location</option>
                        <option value="fee">Fee</option>
                        <option value="rider">Hospitality Rider</option>
                        <option value="tech">Technical Rider</option>
                        <option value="epk">EPK URL</option>
                        <option value="genre">Genre(s)</option>
                        <option value="formed">Date Formed</option>
                        <option value="img">Image URL</option>
                    </select>
                </p>
            </div>
            <div>

            </div>
            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <div class="text-right">
                <button type="button" @click="$emit('delete', entry.id)" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                | <label class="inline-flex items-center cursor-pointer">
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Required</span>
                    <input type="checkbox" class="sr-only peer" :value="mandatory" @click="$emit('entryMandatory', entry.id)">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
        <div v-else class="flex items-center px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <p><span class="font-semibold">{{ entry.entryName }}</span> <span class="text-red-600" v-if="mandatory">*</span></p>
            <p>{{ entry.entryDescription }}</p>
        </div>
    </div>
</template>


