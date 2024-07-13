// VenueList.vue

<template>
  <div>
    <h1>Venue List</h1>
    <ul>
      <li v-for="venue in venues" :key="venue.id">
        {{ venue.name }}
      </li>
    </ul>
    <pagination
      v-if="venues.length > 0"
      :total-pages="Math.ceil($page.props.count / 10)"
      :current-page="$page.url.query.page"
      @page-change="changePage"
    />
  </div>
</template>

<script>
export default {
  props: {
    venues: {
      type: Array,
      required: true
    }
  },
  methods: {
    changePage(page) {
      this.$inertia.visit(this.$route('venue.list'), {
        method: 'get',
        data: {
          pagenum: page - 1
        },
        preserveState: true
      })
    }
  }
}
</script>
