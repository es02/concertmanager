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


const props = defineProps(['application', 'fields']);

var keys = {};
props.fields.forEach((field) =>{
    keys[field.name.replace(/ /g,'_')] = '';
});

const form = useForm(keys);

// Really bad hack until we can figure out dynamic references
const refStr = "ref";
const ref1 = ref(null);
const ref2 = ref(null);
const ref3 = ref(null);
const ref4 = ref(null);
const ref5 = ref(null);
const ref6 = ref(null);
const ref7 = ref(null);
const ref8 = ref(null);
const ref9 = ref(null);
const ref10 = ref(null);
const ref11 = ref(null);
const ref12 = ref(null);
const ref13 = ref(null);
const ref14 = ref(null);
const ref15 = ref(null);
const ref16 = ref(null);
const ref17 = ref(null);
const ref18 = ref(null);
const ref19 = ref(null);
const ref20 = ref(null);

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
            <!-- <div v-if="application.state === 'open'" class="mb-3 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300"><p><h3>Fields marked with * are mandatory and must be filled in.</h3><br /></p></div> -->

            <div v-if="application.state === 'open'" v-for="field in fields" class="col-span-6 sm:col-span-4">
                <div v-if="field.expected_value === 'string'">
                    <InputLabel :for="field.name.replace(/ /g,'_')" :value="field.name" :mandatory="field.mandatory"/>
                    <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ field.description }}</p>
                    <TextInput
                        :id="field.name.replace(/ /g,'_')"
                        :ref="refStr.concat(field.order_id)"
                        v-model=field.vmodel
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
                        :ref="refStr.concat(field.order_id)"
                        v-model=field.vmodel
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
                        :ref="refStr.concat(field.order_id)"
                        :fieldValues="field.expected_value"
                        v-model=field.vmodel
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.current_password" class="mt-2" />
                </div>
                <div v-if="field.expected_value.slice(0, 3) === 'img'">
                    <p><br /><img :src="field.expected_value.replace( /(^.*\[|\].*$)/g, '' )" class="h-auto max-w-lg mx-auto"></p>
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
