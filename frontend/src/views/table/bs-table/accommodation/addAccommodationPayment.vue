<template>
  <b-row class="match-height">
    <b-col lg="12">
      <create-purchase-view />
    </b-col>

  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import CreatePurchaseView from './addAccommodationPaymentView.vue'

export default {
  components: {
    BRow,
    BCol,

    CreatePurchaseView,
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>
