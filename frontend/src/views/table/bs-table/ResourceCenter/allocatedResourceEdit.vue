<template>
  <b-row class="match-height">
    <b-col lg="12">
      <allocate-resource-edit-view />
    </b-col>

  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import AllocateResourceEditView from './allocateResourceEditView.vue'

export default {
  components: {
    BRow,
    BCol,

    AllocateResourceEditView,
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>
