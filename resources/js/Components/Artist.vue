<script setup>
    const props = defineProps(['artist']);
</script>

<template>
    <h1><b>Name: </b>Hello, {{ artist.name }}!</h1>
    <p><b>Bio: </b>{{ artist.bio }}</p>
    <p><b>Location: </b>{{ artist.location }}</p>
    <br>
</template>
