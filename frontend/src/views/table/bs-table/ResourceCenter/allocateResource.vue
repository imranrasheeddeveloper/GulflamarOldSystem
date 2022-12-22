<template>
  <b-row class="match-height">
    <b-col lg="12">
      <allocate-resource-view />
    </b-col>

  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import AllocateResourceView from './allocateResourceView.vue'

export default {
  components: {
    BRow,
    BCol,

    AllocateResourceView,
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>
