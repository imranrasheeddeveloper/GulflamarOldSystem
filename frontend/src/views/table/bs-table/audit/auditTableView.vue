<template>

  <div>

    <!-- search input -->
    <div class="custom-search d-flex justify-content-between">
      <b-form-group>
        <div class="d-flex align-items-center">
          <!-- <label class="mr-1">Search</label> -->

          <b-input-group>
            <b-input-group-prepend>
              <b-button
                variant="outline-primary"
                @click="page=1, getEmployees()"
              >
                <feather-icon icon="SearchIcon" />
              </b-button>
            </b-input-group-prepend>
            <b-form-input
              v-model="searchTerm"
              placeholder="Search"
              type="text"
              class=" d-inline-block"
              @keyup="searchTimeOut()"
            />

            <!-- <b-input-group-append>
                <b-button
                :to="{ name: 'create-new-invoice'}"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary">
                  ADD +
                </b-button>
              </b-input-group-append> -->
          </b-input-group>

        </div>
      </b-form-group>
    </div>

    <b-table
      :items="items"
      :fields="fields"
      responsive
      class="mb-0 bg-white"
      :busy="isBusy"
    >
      <!-- <template #cell(Options)="row">
          <div >

              <b-form-checkbox
                v-model="row.detailsShowing"
                @change="row.toggleDetails"
              >
                {{ row.detailsShowing ? 'Hide' : 'View Details' }}
              </b-form-checkbox>
              <div class="d-flex justify-content-start">

              </div>
          </div>
        </template> -->

      <!-- full detail on click -->
      <!-- <template #row-details="row">
          <b-card no-body class="px-1 py-1">
            <b-row >
                <b-col cols="12">

                        <b-card-text>
                        <b-row class="border-bottom ">
                          <b-col cols="2">
                            <strong>ID</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.id}}
                          </b-col>
                        </b-row>
                        <b-row class="border-bottom " >
                          <b-col cols="2">
                            <strong>Name</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.name}}
                          </b-col>
                        </b-row>
                        <b-row class="border-bottom " >
                          <b-col cols="2">
                            <strong>Email</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.email}}
                          </b-col>
                        </b-row>

                        <b-row class="border-bottom " >
                          <b-col cols="2">
                            <strong>User Name</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.user_name}}
                          </b-col>
                        </b-row>

                        <b-row class="border-bottom " >
                          <b-col cols="2">
                            <strong>Mobile#</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.mobile}}
                          </b-col>
                        </b-row>

                        <b-row class="border-bottom " >
                          <b-col cols="2">
                            <strong>Role</strong>
                          </b-col>
                          <b-col cols="10">
                              {{row.item.role}}
                          </b-col>
                        </b-row>
                      </b-card-text>

                </b-col>
            </b-row>
          </b-card>
        </template> -->

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle" />
          <strong>Loading...</strong>
        </div>
      </template>

      <template #cell(date)="data">
        {{ data.value | moment("DD/MM/YYYY") }}
      </template>

    </b-table>

    <div class="d-flex justify-content-end">
      <b-pagination
        v-model="page"
        :total-rows="count"
        :per-page="pageSize"
        pills
        @change="handlePageChange"
      />
    </div>
  </div>

</template>

<script>
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import axios from '@axios'
import vSelect from 'vue-select'
import {
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile, BFormTextarea, BFormRadio, BForm, BFormCheckboxGroup, BFormRating, BListGroupItem, BListGroup, BModal, BSpinner,
} from 'bootstrap-vue'

import Ripple from 'vue-ripple-directive'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'
import pdf from 'vue-pdf'

import VuePdfApp from 'vue-pdf-app'

import 'vue-pdf-app/dist/icons/main.css'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BCardCode,
    BTable,
    BButton,
    BFormCheckbox,
    BCard,
    BRow,
    BCol,
    BBadge,
    BAvatar,
    BImg,
    EnlargeableImage,
    BTabs,
    BTab,
    BCardText,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BInputGroup,
    BInputGroupAppend,
    BInputGroupPrepend,
    BFormDatepicker,
    BFormFile,
    BFormTextarea,
    BFormRadio,
    BForm,
    vSelect,
    BFormCheckboxGroup,
    BFormRating,
    BListGroup,
    BListGroupItem,
    BModal,
    pdf,
    VuePdfApp,
    BSpinner,
  },
  directives: {
    Ripple,
  },

  errorCaptured() {
    return false
  },

  data() {
    return {
      isBusy: true,
      fields: [{
        key: 'id', label: 'ID', sortable: true, thClass: 'customHead',
      }, {
        key: 'detail', label: 'Detail', sortable: true, thClass: 'customHead',
      }, {
        key: 'date', label: 'Date', sortable: true, thClass: 'customHead',
      }],
      items: [
      ],
      emp_detail: [
        { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
      ],
      /* eslint-disable global-require */
      status: [{
        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',
      },
      {
        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',
      }],

      searchTerm: '',
      pageSize: 10,
      page: 1,
      count: 0,

    }
  },

  mounted() {
    this.getEmployees()
  },
  methods: {

    isPdf(url) {
      if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
        return true
      }

      return false
    },

    deletePurchase(id) {
      this.$swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-danger',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if (result.value) {
          axios.get('/deleteInvoice', {
            params: {
              invoice_number: id,
            },
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getEmployees()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Invoice Deleted',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Vendor deleted')
              } else {
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Error',
                    icon: 'AlertCircleIcon',
                    variant: 'danger',
                    text: 'Something went wrong, try again later',
                  },
                })
              }
            } else {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Error',
                  icon: 'AlertCircleIcon',
                  variant: 'danger',
                  text: 'Something went wrong, try again later',
                },
              })
            }
          }).catch(error => {
            console.error(error)
          })
        }
      })
    },

    getEmployees() {
      this.isBusy = true
      axios.get('/getAuditTrail', {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,

        },
      })

        .then(response => {
          console.log('response', response.data.data)
          this.items = response.data.data
          this.count = response.data.totalRows
          this.isBusy = false

          // TODO
        }).catch(error => {
          console.error(error)
          this.httpHelper(error)
        })
    },

    httpHelper(error){
    console.log(error.response.status)

    if(error.response.status === 403){
       this.$router.replace('/pages/miscellaneous/not-authorized')
       }
    else if(error.response.status === 500){
       console.log("error axios" , error.response.status)
         this.$toast({
                component: ToastificationContent,
                position: "top-right",
               props: {
                  title: "Error",
                 icon: "AlertCircleIcon",
                 variant: "danger",
                 text: "Something went wrong, try again later",
                },
              })
        }
     },

    handlePageChange(value) {
      this.page = value
      this.isBusy = true
      this.getEmployees()
    },

    searchTimeOut() {
      let timeout = null
      clearTimeout(timeout)
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1
        this.getEmployees()
      }, 800)
    },

  },
}
</script>

<style lang="css" >

.enlargeable-image .full  {
  z-index:9999 !important;

  background-color: rgba(0,0,0,0.5) !important;
}

.customHead{

  background-color: #06608f !important;
  color:#fff;
}

</style>
