<script setup>
// Search Input component from https://adambailey.io/blog/jetstream-search-input
// Modified for Laravel 11 / Inertia 5

import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { useAttrs } from 'vue'

const attrs = useAttrs()

const props = defineProps({
  routeName: {
      type: String,
      required: true,
  },
});

console.log(attrs.search);

// if(props.jetstream.search){
//     console.log(props.jetstream.search);
// }

let search = ref(null);
let sort = ref(null);
const searchRef = ref(null);

watch(search, () => {
  if (search.value) {
    searchMethod();
  } else {
    router.get(route(props.routeName));
  }
});

const searchMethod = debounce(() => {
    router.get(
        route(props.routeName),
        { search: search.value, sort: sort.value },
        { preserveState: false }
  );
}, 2000);
</script>

<template>
    <div class="w-1/2 bg-white px-4 dark:bg-gray-800">
      <label for="search" class="hidden">Search</label>
      <input
          id="search"
          ref="searchRef"
          v-model="search"
          class="h-10 w-full cursor-pointer rounded-full border border-gray-500 bg-gray-100 px-4 pb-0 pt-px text-gray-700 outline-none transition focus:border-purple-400"
          :class="{ 'transition-border': search }"
          autocomplete="off"
          name="search"
          placeholder="Search"
          type="search"
          @keyup.esc="search = null"
      />
    </div>
  </template>
