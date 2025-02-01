<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import EnumInput from '@/Components/EnumInput.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from '@/Components/LongTextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { FwbAlert } from 'flowbite-vue'

const props = defineProps(['application', 'fields']);

var keys = {};
props.fields.forEach((field) =>{
    keys[field.vmodel] = '';
});

const form = useForm(keys);
var formErrors = false;

const applyForEvent = () => {
    formErrors = false;

    props.fields.forEach((field) =>{
        if (field.mandatory && form[field.vmodel] == ''){
            form.errors[field.vmodel] = 'Please provide an answer.';
            formErrors = true;
        } else {
            form.errors[field.vmodel] = '';
        }
    });
    if (formErrors === false){
        form.post(route('new.application', props.application.name), {
        // errorBag: 'applyForEvent',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        // onError: () => {
            //
        // },
    });
    }

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
                    <TextInput
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
                    <LongTextInput
                        :id="field.vmodel"
                        :ref="field.vmodel"
                        v-model="form[field.vmodel]"
                        type="text"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <InputError :message="form.errors[field.vmodel]" class="mt-2" />
                </div>
                <div v-if="field.expected_value.slice(0, 4) === 'enum'">
                    <InputLabel :for="field.vmodel" :value="field.name" :mandatory="field.mandatory"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                    <EnumInput
                        :id="field.vmodel"
                        :ref="field.vmodel"
                        :fieldValues="field.expected_value"
                        v-model="form[field.vmodel]"
                        class="block w-full p-2.5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                    <InputError :message="form.errors[field.vmodel]" class="mt-2" />
                </div>
                <div v-if="field.expected_value.slice(0, 3) === 'img'">
                    <p><br /><img :src="field.expected_value.replace( /(^.*\[|\].*$)/g, '' )" :alt="field.description" class="h-auto max-w-lg mx-auto"></p>
                </div>
            </div>
        </template>
        <template #actions>
            <fwb-alert v-show="formErrors" type="danger">
                Please ensure all mandatory fields are filled before submitting.
            </fwb-alert>

            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Applied.
            </ActionMessage>

            <PrimaryButton v-if="application.state === 'open'" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Apply
            </PrimaryButton>
        </template>
    </FormSection>
</template>
