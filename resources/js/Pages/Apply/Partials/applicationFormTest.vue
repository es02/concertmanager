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

const artist_name = ref(null);
const contact_email = ref(null);
const genres = ref(null);
const location = ref(null);
const location_other = ref(null);
const bio = ref(null);
const socials = ref(null);
const music = ref(null);
const epk = ref(null);
const fee = ref(null);
const tech = ref(null);
const other = ref(null);
const timeslot = ref(null);

const form = useForm({
    Artist_Name:"",
    Contact_Email:"",
    Genres:"",
    Band_Location:"QLD-BNE",
    Location_other:"",
    Artist_Bio:"",
    Social_Media_URLs:"",
    Music_URLs:"",
    EPK:"",
    Expected_renumeration:"",
    Technical_Requirements:"",
    Additional_Requirements:"",
    Preferred_timeslot:"Any",
});

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

            <div v-if="application.state === 'open'"  class="col-span-6 sm:col-span-4">
                <p><br /><img :src="fields[0].expected_value.replace( /(^.*\[|\].*$)/g, '' )" class="h-auto max-w-lg mx-auto"></p>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Fields marked with * are mandatory<br /></p>

                <InputLabel for="artist_name" value="Artist Name" :mandatory="fields[1].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[1].description }}</p>
                <TextInput
                    id="Artist_Name"
                    ref="artist_name"
                    v-model=form.Artist_Name
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Artist_Name" class="mt-2" />

                <InputLabel for="contact_email" value="Contact Email" :mandatory="fields[2].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[2].description }}</p>
                <TextInput
                    id="Contact_Email"
                    ref="contact_email"
                    v-model=form.Contact_Email
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Contact_Email" class="mt-2" />

                <InputLabel for="genres" value="Genre(s)" :mandatory="fields[3].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[3].description }}</p>
                <TextInput
                    id="Genres"
                    ref="genres"
                    v-model=form.Genres
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Genres" class="mt-2" />

                <InputLabel for="location" value="Band Location" :mandatory="fields[4].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[4].description }}</p>
                <EnumInput
                    id="Band_Location"
                    ref="location"
                    :fieldValues="fields[4].expected_value"
                    v-model=form.Band_Location
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Band_Location" class="mt-2" />

                <InputLabel for="location_other" value="Location - other" :mandatory="fields[5].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[5].description }}</p>
                <TextInput
                    id="Location_-_other"
                    ref="location_other"
                    v-model=form.Location_other
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Location_other" class="mt-2" />

                <InputLabel for="bio" value="Artist Bio" :mandatory="fields[6].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[6].description }}</p>

                <LongTextInput
                    id="Artist_Bio"
                    ref="bio"
                    v-model=form.Artist_Bio
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Artist_Bio" class="mt-2" />

                <InputLabel for="socials" value="Social Media URL(s)" :mandatory="fields[7].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[7].description }}</p>

                <LongTextInput
                    id="Social_Media_URLs"
                    ref="socials"
                    v-model=form.Social_Media_URLs
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Social_Media_URLs" class="mt-2" />

                <InputLabel for="music" value="Music URL(s)" :mandatory="fields[8].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[8].description }}</p>

                <LongTextInput
                    id="Music_URLs"
                    ref="music"
                    v-model=form.Music_URLs
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Music_URLs" class="mt-2" />

                <InputLabel for="epk" value="EPK" :mandatory="fields[9].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[9].description }}</p>
                <TextInput
                    id="EPK"
                    ref="epk"
                    v-model=form.EPK
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.EPK" class="mt-2" />

                <InputLabel for="fee" value="Expected renumeration" :mandatory="fields[10].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[10].description }}</p>
                <TextInput
                    id="Expected_renumeration"
                    ref="fee"
                    v-model=form.Expected_renumeration
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Expected_renumeration" class="mt-2" />

                <InputLabel for="tech" value="Technical Requirements" :mandatory="fields[11].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[11].description }}</p>

                <LongTextInput
                    id="Technical_Requirements"
                    ref="tech"
                    v-model=form.Technical_Requirements
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Technical_Requirements" class="mt-2" />

                <InputLabel for="other" value="Additional Requirements" :mandatory="fields[12].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[12].description }}</p>

                <LongTextInput
                    id="Additional_Requirements"
                    ref="other"
                    v-model=form.Additional_Requirements
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Additional_Requirements" class="mt-2" />

                <InputLabel for="timeslot" value="Preferred timeslot" :mandatory="fields[13].mandatory"/>
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ fields[13].description }}</p>
                <EnumInput
                    id="Preferred_timeslot"
                    ref="timeslot"
                    :fieldValues="fields[13].expected_value"
                    v-model=form.Preferred_timeslot
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.Preferred_timeslot" class="mt-2" />
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
