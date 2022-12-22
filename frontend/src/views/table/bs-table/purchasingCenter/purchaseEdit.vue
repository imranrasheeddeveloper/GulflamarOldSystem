<template>
  <b-row class="match-height">
    <b-col lg="12">
      <purchase-edit-view />
    </b-col>

  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import purchaseEditView from './purchaseEditView.vue'

export default {
  components: {
    BRow,
    BCol,

    purchaseEditView,
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>
