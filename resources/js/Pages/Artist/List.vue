<script setup>
    import Artist from '@/Components/Artist.vue';

    const props = defineProps(['artists']);
</script>

<template>
    <Artist v-for="artist in artists" :artist="artist" :key="artist.id" />
</template>
