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

            <!--  <b-input-group-append>
                <b-button
                :to="{ name: 'create-new-vendor'}"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary">
                  ADD +
                </b-button>
              </b-input-group-append> -->
          </b-input-group>

        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'vendor')">
        <div class="d-flex align-items-center">

          <b-input-group>

            <b-input-group-prepend>

              <div id="my-container">

                <b-button
                  id="popover-reactive-1"
                  ref="button"
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  title="Export"
                  variant="outline-primary"
                >
                  <feather-icon icon="DownloadIcon" />
                </b-button>

                <b-popover
                  ref="popover"
                  target="popover-reactive-1"
                  triggers="click"
                  :show.sync="popoverShow"
                  placement="auto"
                  container="my-container"
                >
                  <template #title>
                    <div class="d-flex justify-content-between align-items-center">
                      <span>Export Table</span>
                    </div>
                  </template>

                  <div>
                    <b-form-group
                      label="Search Filter"
                    >
                      <b-form-input
                        v-model="searchTerm"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <b-form-group
                      label="Records Limit"
                    >
                      <b-form-input
                        v-model="exportLimit"
                        type="text"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <!-- button -->
                    <b-row>
                      <b-col cols="6">
                        <b-button
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          size="sm"
                          variant="danger"
                          class="mr-1"
                          @click="onClose"
                        >
                          <feather-icon icon="XIcon" />
                        </b-button>
                      </b-col>

                      <b-col cols="6">
                        <b-button
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          size="sm"
                          variant="primary"
                          :href="'/api/vendorCsv?searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
                          target="_blank"
                        >
                          <feather-icon icon="DownloadIcon" />
                        </b-button>
                      </b-col>
                    </b-row>
                  </div>
                </b-popover>

              </div>

            </b-input-group-prepend>

            <b-input-group-prepend>

              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"

                :to="{ name: 'create-new-vendor'}"

                title="Add Vendor"
              >
                ADD+
              </b-button>
            </b-input-group-prepend>

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
      <template #cell(Options)="row">
          <div>
            <div class="d-flex align-items-center w-fit-content">
              <b-input-group>
                <b-input-group-prepend>
                  <b-button
                    size="sm"
                    v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                    variant="outline-primary"
                    v-bind:class="row.detailsShowing ? 'active' : ''"
                    @click="row.toggleDetails"
                  >
                    <feather-icon
                      icon="ChevronUpIcon"
                      v-if="row.detailsShowing"
                    />

                    <feather-icon icon="ChevronDownIcon" v-else />
                  </b-button>
                </b-input-group-prepend>

                <b-input-group-append v-if="$ability.can('edit', 'vendor')">
                  <b-button
                    size="sm"
                    :to="{  name: 'edit-vendor', params: { id: row.item.id } }"
                    v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                    variant="outline-primary text-success"
                  >
                    <feather-icon icon="EditIcon" />
                  </b-button>
                </b-input-group-append>
                <b-input-group-append
                  v-if="$ability.can('delete', 'vendor')"
                >
                  <b-button
                    size="sm"
                    v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                    variant="outline-primary text-danger"
                    @click="deletePurchase(row.item.id)"
                  >
                    <feather-icon icon="Trash2Icon" />
                  </b-button>
                </b-input-group-append>
              </b-input-group>
            </div>
          </div>
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card
          no-body
          class="px-1 py-1"
        >
          <b-row>
            <b-col cols="12">
              <b-tabs
                pills
                card
                horizontal
              >

                <b-tab title="Vendor Details">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Vendor Code</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.code }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Company Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Company Name (ar)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.name_ar }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>CR Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.cr_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>CR Attachment</strong>
                      </b-col>
                      <b-col cols="10">
                        <a
                          v-if="isPdf(row.item.cr_file)"
                          class="btn btn-primary"
                          :href="row.item.cr_file"
                          target="_blank"
                        >View file</a>

                        <enlargeable-image
                          v-else
                          :src="row.item.cr_file"
                          :src_large="row.item.cr_file"
                          animation_duration="600"
                        >
                          <b-img
                            v-if="row.item.cr_file != ''"
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.cr_file"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contract File</strong>
                      </b-col>
                      <b-col cols="10">
                        <a
                          v-if="isPdf(row.item.contract)"
                          class="btn btn-primary"
                          :href="row.item.contract"
                          target="_blank"
                        >View file</a>

                        <enlargeable-image
                          v-else
                          :src="row.item.contract"
                          :src_large="row.item.contract"
                          animation_duration="600"
                        >
                          <b-img
                            v-if="row.item.contract != ''"
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.contract"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>VAT Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.vat_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>VAT Attachment</strong>
                      </b-col>
                      <b-col cols="10">
                        <a
                          v-if="isPdf(row.item.vat_file)"
                          class="btn btn-primary"
                          :href="row.item.vat_file"
                          target="_blank"
                        >View file</a>

                        <enlargeable-image
                          v-else
                          :src="row.item.vat_file"
                          :src_large="row.item.vat_file"
                          animation_duration="600"
                        >
                          <b-img
                            v-if="row.item.vat_file != ''"
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.vat_file"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Name (Operations)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_ops }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Number (Operations)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_ops_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Name (Billing)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_billing }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Number (Billing)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_billing_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Name (Gov Relations)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_gov }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Number (Gov Relations)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_gov_no }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Vendor Services">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="12">
                        <b-table
                          :items="row.item.services"
                          :fields="serviceFields"
                          striped
                          responsive
                          class="mb-0"
                          :busy="isBusy"
                          show-empty
                        >
                          <template #empty="scope">
                            <h4>{{ scope.emptyText }}</h4>
                          </template>

                          <template #cell(rate)="data">

                            {{ Number( data.value).toLocaleString() }}
                          </template>
                        </b-table>
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-col>
          </b-row>
        </b-card>
      </template>

      <!-- <template #cell(iqama)="data">
          <b-avatar :src="data.value" />
        </template> -->

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle" />
          <strong>Loading...</strong>
        </div>
      </template>

      <!-- <template #cell(status)="data">
          <b-badge :variant="status[1][data.value]">
            {{ status[0][data.value] }}
          </b-badge>
        </template> -->
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
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile, BFormTextarea, BFormRadio, BForm, BFormCheckboxGroup, BFormRating, BListGroupItem, BListGroup, BModal, BPopover, BSpinner,
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
    BPopover,
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
      popoverShow: false,
      exportLimit: '',
      isBusy: true,
      fields: [{ key: 'Options', thClass: 'customHead' }, {
        key: 'id', label: 'ID', sortable: true, thClass: 'customHead',
      }, {
        key: 'code', label: 'Vendor Code', sortable: true, thClass: 'customHead',
      }, {
        key: 'name', label: 'Company Name', sortable: true, thClass: 'customHead',
      }, {
        key: 'name_ar', label: 'Company Name (ar)', sortable: true, thClass: 'customHead',
      }, {
        key: 'cr_no', label: 'CR Number', sortable: true, thClass: 'customHead',
      }, {
        key: 'vat_no', label: 'VAT Number', sortable: true, thClass: 'customHead',
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

    onClose() {
      this.popoverShow = false
    },

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
          axios.get('/deleteVendor', {
            params: {
              id,
            },
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getEmployees()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Vendor Deleted',
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
      axios.get('/getVendors', {
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
        }).catch((error) => {
          console.error(error);
          this.httpHelper(error)
        });
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
