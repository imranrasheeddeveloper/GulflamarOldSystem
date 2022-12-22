<template>
  <b-card
    title="Upcoming Rents"

    class="card-company-table"
  >
    <b-table
      :items="tableData"
      responsive
      :fields="fields"
      class="mb-0"
    />
  </b-card>
</template>

<script>
import {
  BCard, BTable, BAvatar, BImg,
} from 'bootstrap-vue'

import axios from '@axios'

export default {
  components: {
    BCard,
    BTable,
    BAvatar,
    BImg,
  },

  data() {
    return {
      tableData: [],
      fields: [
        { key: 'id', label: 'ID' },
        { key: 'name', label: 'Name' },
        { key: 'dueDate', label: 'Due Date' },
      ],
    }
  },
  mounted() {
    this.getProductStat()
  },
  methods: {

    getProductStat() {
      axios.get('/dashboardUpcomingRents')

        .then(response => {
          console.log('response', response.data.data)
          this.tableData = response.data.data

          console.log(this.tableData)

          // TODO
        }).catch(error => {
          console.error(error)
        })
    },

  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/base/bootstrap-extended/include';
@import '~@core/scss/base/components/variables-dark';

.card-company-table ::v-deep td .b-avatar.badge-light-company {
  .dark-layout & {
    background: $theme-dark-body-bg !important;
  }
}
</style>
