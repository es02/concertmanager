<script setup>
import { onMounted, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LongTextInput from './LongTextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps(['entry']);

const photoPreview = ref(null);
const photoInput = ref(null);

const truth = 1;

const returnEntry = {
    entryDescription: '',
    entryImage: '',
}

const emit = defineEmits(['update:modelValue', 'updateEntry']);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    returnEntry.entryImage = photo;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);

    emit('update:modelValue', photo);
    emit('updateEntry', props.entry.id, returnEntry);
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

function updateEntry() {
    emit('updateEntry', props.entry.id, returnEntry);
}

</script>

<template>
    <div class="p-3 col-span-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="col-span-6 sm:col-span-4">
            <!-- Profile Photo File Input -->
            <input
                id="photo"
                ref="photoInput"
                type="file"
                class="hidden"
                @change="updatePhotoPreview"
            >

            <InputLabel for="photo" value="Photo" />

            <!-- New Profile Photo Preview -->
            <div v-show="photoPreview" class="mt-2">
                <span
                    class="block w-250 h-80 bg-cover bg-no-repeat bg-center"
                    :style="'background-image: url(\'' + photoPreview + '\');'"
                />
            </div>
            <div v-if="entry.hasFocus" div class="p-3">
                <p>
                    <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                        Select A New Photo
                    </SecondaryButton>
                </p>
                <p>
                    <InputLabel for="entryDescription" value="Image Description (Alt Text)" :mandatory="truth"/>
                    <LongTextInput
                        id="entryDescription"
                        ref="entryDescription"
                        type="text"
                        @input="updateEntry"
                        v-model="returnEntry.entryDescription"
                        class="block p-2.5 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    />
                </p>
            </div>
            <div v-else>
                <p>{{ entry.entryDescription }}</p>
            </div>
        </div>
    </div>
</template>
