<template>
  <b-row>
    <b-col cols="12">
      <employee-edit-view />
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import employeeEditView from './employeeEditView.vue'

export default {
  components: {
    BRow,
    BCol,
    employeeEditView,
  },

}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-wizard.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>
