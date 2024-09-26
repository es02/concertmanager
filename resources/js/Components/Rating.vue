<script setup>
import FilledStar from '@/Components/Icons/FilledStar.vue';
import UnFilledStar from '@/Components/Icons/UnFilledStar.vue';

defineProps(['rating']);
</script>

<template>
    <div class="flex items-center">
        <FilledStar v-if="rating > 0"></FilledStar>
        <UnFilledStar v-else></UnFilledStar>
        <FilledStar v-if="rating > 1"></FilledStar>
        <UnFilledStar v-else></UnFilledStar>
        <FilledStar v-if="rating > 2"></FilledStar>
        <UnFilledStar v-else></UnFilledStar>
        <FilledStar v-if="rating > 3"></FilledStar>
        <UnFilledStar v-else></UnFilledStar>
        <FilledStar v-if="rating > 4"></FilledStar>
        <UnFilledStar v-else></UnFilledStar>
    </div>
</template>
