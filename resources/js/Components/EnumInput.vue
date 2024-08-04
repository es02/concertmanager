<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    fieldValues: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

// expected format "enum[FOO, Bar, Baz]"
// strip everything outside of the square brackets, remove whitespace, and split on ','

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        class="block w-full p-2.5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <template v-for="(value, index) in fieldValues.replace( /(^.*\[|\].*$)/g, '' ).replace(/ /g,'').split(',')">
            <option v-if="index === 0" selected> {{ value }}</option>
            <option :value="value"> {{ value }}</option>
        </template>
    </select>
</template>
