<script setup>
import {ref, computed, reactive} from 'vue';
import { useForm, router  } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import draggable from 'vuedraggable'
import TextInput from '@/Components/TextInput.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    },
    count: {
        type: Number,
        required: true
    }
});

var defaultTask = {
    name: 'Task Name',
    due: '',
    order_id: props.count,
    completed: 'new'
};

var taskList = useForm({
    tasks: []
});

var addForm = useForm({
    _method: 'POST',
    name: 'Task Name',
    due: '',
    order_id: props.count,
    completed: 'new'
})

var updateForm = useForm({
    _method: 'POST',
    id: 0,
    name: '',
    due: '',
    completed: ''
})

// build tasklist on pageload
for (const [key, value] of Object.entries(props.tasks)) {
    console.log('rebuilding list');
    taskList.tasks.push(clone(value));
}

const components = {
    draggable,
};
const data = function () {
    return {
    drag: false,
    }
};

var form = {
    import_csv: '',
};

const csvInput = ref(null);

function uploadCSV() {
    console.log('uploading csv');
    const csv = csvInput.value.files[0];

    if (! csv) return;

    form.import_csv = csv;

    router.post('/tasks/import-csv', form);
    form.import_csv = '';
    csvInput.value.value = null;
};

function downloadCSV() {
    window.location.href = '/tasks/export-csv';
};

const selectNewCSV = () => {
    csvInput.value.click();
};


function addTask() {
    taskList.tasks.push(clone(defaultTask));
    addForm.post(route('tasks.new'), {
        preserveScroll: true,
        onSuccess: () => addForm.reset(),
    });
}

function updateEntry(updateTask) {
    updateForm.id = updateTask.id;
    updateForm.name = updateTask.name;
    updateForm.due = updateTask.due;
    updateForm.completed = updateTask.completed;    // for some stupid reason this holds the previous value, despite updatetask showing the correct one in console.log

    updateForm.post(route('tasks.update'), {
        preserveScroll: true,
        onSuccess: () => updateForm.reset(),
    });
}

function reorderTasks() {
    taskList.post(route('tasks.reorder'), {
        preserveScroll:true
    })
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
    <AppLayout title="Tasks">
        <h1 class="text-2xl font-semibold text-center pt-10">Tasks</h1>
        <draggable
        v-model="taskList.tasks"
        group="tasks"
        @start="drag=true"
        @end="drag=false"
        item-key="order_id"
        :move="reorderTasks">
        <template #item="{element}">
            <div class="block max-w-2xl p-3 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="entryName" class="block text-gray-700 dark:text-gray-200">Task <span v-if="element.overdue == true" class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Overdue</span></label>
                        <TextInput
                            id="entryName"
                            ref="entryName"
                            placeholder="Untitled Question"
                            type="text"
                            v-model="element.name"
                            @input="updateEntry(element)"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        />
                    </p>
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="due" class="block text-gray-700 dark:text-gray-200">Due by</label>
                        <VueDatePicker id="due" v-model="element.due" dark format="dd/MM/yyyy HH:mm" />
                    </p>
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="completed" class="block text-gray-700 dark:text-gray-200">Completed?</label>
                        <select
                            id="completed"
                            v-model="element.completed"
                            @input="updateEntry(element)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="new">New</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </p>
                </div>
            </div>
        </template>
        <template #header>
            <div class="overflow-x-auto m-10 text-right">
                <button @click="addTask()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add</button>
                <button type="submit" @click="selectNewCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import CSV</button>
                <button type="submit" @click.prevent="downloadCSV()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Export CSV</button>
            </div>
            <form @submit.prevent="uploadCSV">
                    <input
                        id="csv"
                        ref="csvInput"
                        type="file"
                        accept=".csv"
                        class="hidden"
                        @change="uploadCSV"
                    ></form>
            </template>
        </draggable>
    </AppLayout>
</template>
