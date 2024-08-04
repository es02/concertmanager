<script setup>
import { ref, reactive, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import EnumInput from '@/Components/EnumInput.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from '@/Components/LongTextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


const props = defineProps(['application', 'fields', 'event']);

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

var keys = {};
props.fields.forEach((field) =>{
    keys[field.name.replace(/ /g,'_')] = '';
});

const form = useForm(keys);

const applyForEvent = () => {
    form.post(route('new.application'), {
        // errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        // onError: () => {
        //     if (form.errors.password) {
        //         form.reset('password', 'password_confirmation');
        //         passwordInput.value.focus();
        //     }

        //     if (form.errors.current_password) {
        //         form.reset('current_password');
        //         currentPasswordInput.value.focus();
        //     }
        // },
    });
};
</script>

<template>
    <AppLayout title="Event Application - Artist">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Apply for {{ event.name }}
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <FormSection @submitted="applyForEvent">
                <template #description>
                    <p v-html="application.description.replace(/\r?\n/g, '<br />')"></p>
                </template>

                <template #form>
                    <div v-if="application.state === 'closed'" class="col-span-6 sm:col-span-4"><p><span class="font-semibold">Applications for this event are currently closed.</span><br /> </p></div>
                    <div v-if="application.state === 'open'" class="mb-3 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300"><p><h3>Fields marked with * are mandatory and must be filled in.</h3><br /></p></div>

                    <div v-if="application.state === 'open'" v-for="field in fields" class="col-span-6 sm:col-span-4">
                        <div v-if="field.expected_value === 'string'">
                            <InputLabel :for="field.name.replace(/ /g,'_')" :value="field.name" :mandatory="field.mandatory"/>
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                            <TextInput
                                :id="field.name.replace(/ /g,'_')"
                                :ref="field.name.replace(/ /g,'_')"
                                :v-model="form[field.name.replace(/ /g,'_')]"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.current_password" class="mt-2" />
                        </div>
                        <div v-if="field.expected_value === 'longText'">
                            <InputLabel :for="field.name.replace(/ /g,'_')" :value="field.name" :mandatory="field.mandatory"/>
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                            <LongTextInput
                                :id="field.name.replace(/ /g,'_')"
                                :ref="field.name.replace(/ /g,'_')"
                                :v-model="form[field.name.replace(/ /g,'_')]"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.current_password" class="mt-2" />
                        </div>
                        <div v-if="field.expected_value.slice(0, 4) === 'enum'">
                            <InputLabel :for="field.name.replace(/ /g,'_')" :value="field.name" :mandatory="field.mandatory"/>
                            <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                            <EnumInput
                                :id="field.name.replace(/ /g,'_')"
                                :ref="field.name.replace(/ /g,'_')"
                                :fieldValues="field.expected_value"
                                :v-model="form[field.name.replace(/ /g,'_')]"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.current_password" class="mt-2" />
                        </div>
                        <div v-if="field.expected_value.slice(0, 3) === 'img'">
                        <p><br /><img :src="field.expected_value.replace( /(^.*\[|\].*$)/g, '' )" class="h-auto max-w-lg mx-auto"></p>
                        </div>
                    </div>
                </template>

                <template #actions v-if="application.state !== 'closed'">
                    <ActionMessage :on="form.recentlySuccessful" class="me-3">
                        Applied.
                    </ActionMessage>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Apply
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>
    </AppLayout>
</template>
