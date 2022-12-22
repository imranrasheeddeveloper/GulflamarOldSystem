<template>
  <section id="dashboard-ecommerce">
    <b-row class="match-height" 
        v-if="$ability.can('Empty', 'dashboard')"
    >

      <b-col
        
      >
        <h1 class="text-center set_height">Welcome to Gulf Lamar System</h1>
      </b-col>

    </b-row>
    <b-row class="match-height" v-if="$ability.can('Master', 'dashboard')">
      <b-col xl="4" md="6">
        <ecommerce-statistics :data="data.statisticsItems" />
      </b-col>
      <b-col
        v-if="$ability.can('Master', 'dashboard')"
        xl="8"
        md="6"
      >
        <chartjs-bar-chart />
      </b-col>
        <b-col
        v-if="$ability.can('Master', 'dashboard')"
        lg="12"
      >
        <ecommerce-rent-table />   
      </b-col>

       <b-col
        v-if="$ability.can('Master', 'dashboard')"
        lg="12"
      >
        <ecommerce-company-table />
      </b-col>

    </b-row>


    <b-row class="match-height" v-if="$ability.can('Admin', 'dashboard')">
      <b-col
        xl="4"
        md="6"
      >
        <ecommerce-statistics :data="data.statisticsItems" />
      </b-col>
      <b-col
        v-if="$ability.can('Admin', 'dashboard')"
        lg="12"
      >
        <ecommerce-rent-table />   
      </b-col>
    </b-row>
    
    <!-- Operation -->
    
    <b-row class="match-height" v-if="$ability.can('Operation', 'dashboard')">
      <b-col
        v-if="$ability.can('Operation', 'dashboard')"
        lg="12"
      >
        <ecommerce-rent-table />   
      </b-col>

       <b-col
        v-if="$ability.can('Operation', 'dashboard')"
        lg="12"
      >
        <ecommerce-company-table />
      </b-col>
    </b-row>
  </section>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import axios from '@axios'
import store from '@/store'

import { getUserData } from '@/auth/utils'
import EcommerceMedal from './EcommerceMedal.vue'
import EcommerceStatistics from './EcommerceStatistics.vue'
import EcommerceRevenueReport from './EcommerceRevenueReport.vue'
import EcommerceOrderChart from './EcommerceOrderChart.vue'
import EcommerceProfitChart from './EcommerceProfitChart.vue'
import EcommerceEarningsChart from './EcommerceEarningsChart.vue'
import EcommerceCompanyTable from './EcommerceCompanyTable.vue'
import EcommerceRentTable from './EcommerceRentTable.vue'
import EcommerceMeetup from './EcommerceMeetup.vue'
import EcommerceBrowserStates from './EcommerceBrowserStates.vue'
import EcommerceGoalOverview from './EcommerceGoalOverview.vue'
import EcommerceTransactions from './EcommerceTransactions.vue'

import ChartjsBarChart from './ChartjsBarChart.vue'

export default {
  components: {
    BRow,
    BCol,

    EcommerceMedal,
    EcommerceStatistics,
    EcommerceRevenueReport,
    EcommerceOrderChart,
    EcommerceProfitChart,
    EcommerceEarningsChart,
    EcommerceCompanyTable,
    EcommerceMeetup,
    EcommerceBrowserStates,
    EcommerceGoalOverview,
    EcommerceTransactions,
    ChartjsBarChart,
    EcommerceRentTable,
  },
  data() {
    return {
      data: {},
    }
  },
  created() {
    // data
    this.$http.get('/ecommerce/data')
      .then(response => {
        this.data = response.data

        // ? Your API will return name of logged in user or you might just directly get name of logged in user
        // ? This is just for demo purpose
        const userData = getUserData()
        this.data.congratulations.name = userData.fullName.split(' ')[0] || userData.username
      })
  },
    mounted() {
    this.setPermissions()
  },
  methods: {

  setPermissions() {
    axios.get('/getPermissions')

      .then(response => {
        console.log('response', response.data.data)
        const user = response.data.data
        const userData = user
        this.$ability.update(userData.ability)
      }).catch(error => {
        console.error(error)
      })
  }

  },

}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/dashboard-ecommerce.scss';
@import '@core/scss/vue/libs/chart-apex.scss';
.set_height{
  padding-top:20%;
}
</style>
