<script setup>
import {ref, computed, reactive} from 'vue';
import { useForm } from '@inertiajs/vue3';
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
    completed: 0
};

var taskList = useForm({
    tasks: []
});

var form = useForm({
    _method: 'POST',
    name: 'Task Name',
    due: '',
    order_id: props.count,
    completed: 0
})

for (const [key, value] of Object.entries(props.tasks)) {
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

function addTask() {
    taskList.tasks.push(clone(defaultTask));
    form.post(route('tasks.new'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
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
        item-key="order_id">
        <template #item="{element}">
            <div class="block max-w-2xl p-3 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="entryName" class="block text-gray-700 dark:text-gray-200">Task</label>
                        <TextInput
                            id="entryName"
                            ref="entryName"
                            placeholder="Untitled Question"
                            type="text"
                            v-model="element.name"
                            @input="updateEntry"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        />
                    </p>
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="due" class="block text-gray-700 dark:text-gray-200">Due by</label>
                        <VueDatePicker id="due" v-model="element.due" dark />
                    </p>
                    <p class="mb-3 text-gray-500 dark:text-gray-400">
                        <label for="completed" class="block text-gray-700 dark:text-gray-200">Completed?</label>
                        <input id="completed" type="checkbox" class="sr-only peer" :value="element.completed">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </p>
                </div>
            </div>
        </template>
        <template #header>
            <button @click="addTask()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add</button>
        </template>
        <template #footer>
            <button @click="addTask()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add</button>
        </template>
        </draggable>
    </AppLayout>
</template>
