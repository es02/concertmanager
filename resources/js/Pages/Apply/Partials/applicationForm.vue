<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps(['application', 'fields']);

var keys = {};
props.fields.forEach((field) =>{
    keys[field.vmodel] = '';
});

const form = useForm(keys);

const applyForEvent = () => {
    form.post(route('new.application', props.application.name), {
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
    <FormSection @submitted="applyForEvent">
        <template #description>
            <p v-html="application.description.replace(/\r?\n/g, '<br />')"></p>
        </template>

        <template #form>
            <div v-if="application.state === 'closed'" class="col-span-6 sm:col-span-4"><p><span class="font-semibold">Applications for this event are currently closed.</span><br /> </p></div>
            <div v-if="application.state === 'open'" v-for="field in fields" class="col-span-6 sm:col-span-4">
                <div v-if="field.expected_value === 'string'">
                    <InputLabel :for="field.vmodel" :value="field.name" :mandatory="field.mandatory"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                    <input
                        :id="field.vmodel"
                        :ref="keys[field.vmodel]"
                        v-model="form[field.vmodel]"
                        placeholder="Enter text"
                        type="text"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <InputError :message="form.errors[field.vmodel]" class="mt-2" />
                </div>
                <div v-if="field.expected_value === 'longText'">
                    <InputLabel :for="field.vmodel" :value="field.name" :mandatory="field.mandatory"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                    <textarea
                        :id="field.vmodel"
                        :ref="field.vmodel"
                        v-model="form[field.vmodel]"
                        type="text"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    ></textarea>
                    <InputError :message="form.errors[field.vmodel]" class="mt-2" />
                </div>
                <div v-if="field.expected_value.slice(0, 4) === 'enum'">
                    <InputLabel :for="field.vmodel" :value="field.name" :mandatory="field.mandatory"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                    <select
                        :id="field.vmodel"
                        :ref="field.vmodel"
                        v-model="form[field.vmodel]"
                        class="block w-full p-2.5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <template v-for="(value, index) in field.expected_value.replace( /(^.*\[|\].*$)/g, '' ).replace(/ /g,'').split(',')">
                            <option v-if="index === 0" selected> {{ value }}</option>
                            <option v-else :value="value"> {{ value }}</option>
                        </template>
                    </select>
                    <InputError :message="form.errors[field.vmodel]" class="mt-2" />
                </div>
                <div v-if="field.expected_value.slice(0, 3) === 'img'">
                    <p><br /><img :src="field.expected_value.replace( /(^.*\[|\].*$)/g, '' )" :alt="field.description" class="h-auto max-w-lg mx-auto"></p>
                </div>
            </div>
        </template>
        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Applied.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Apply
            </PrimaryButton>
        </template>
    </FormSection>
</template>
