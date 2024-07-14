<script setup>
    import Artist from '@/Components/Artist.vue';

    const props = defineProps(['artist']);
</script>

<template>
    <Artist :artist="artist" />
</template>
