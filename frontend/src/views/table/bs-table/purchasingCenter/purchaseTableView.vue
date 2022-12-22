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
            <b-input-group-append>
                <b-button 
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                v-b-modal.modal-advancesearch
                variant="outline-primary">
                  Advance search
                </b-button>
              </b-input-group-append>

          </b-input-group>

        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'purchase_order')">
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
                          :href="'/api/purchaseCsv?searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
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

                :to="{ name: 'save-purchase'}"

                title="Create Expense"
              >
                ADD+
              </b-button>
            </b-input-group-prepend>

          </b-input-group>

        </div>
      </b-form-group>
    </div>

      <b-modal
        hide-footer
        id="modal-advancesearch"
        title="Advance Search"
        ref='searchModel'
        size="lg"
        cancel-variant="outline-secondary"
      >

        <b-form @submit.prevent>
          <b-row>
             <b-col md="6">
              <b-form-group
                label="Expense Type"
                label-for="v-purchaseType"
              >
                <v-select
                  id="v-purchaseType"
                  v-model="formValues.purchaseType"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="purchaseTypes"
                  label="text"
                  :reduce="text => text.value"
                />
              </b-form-group>
            </b-col>

                

            <b-col md="6">
              <b-form-group
                label="Amount"
                label-for="v-amount"
              >
            <b-form-input v-model="formValues.amount" type="number" id="v-amount" placeholder="Enter Amount"></b-form-input>
              </b-form-group>
            </b-col>
         
            <b-col md="6">
        
              <label for="v-from_date">From Date</label>
                  <b-input-group >

                    <cleave
                      id="from_date"
                      v-model="formValues.fromDate"
                      class="form-control"
                      :raw="false"
                      :options="options.date"
                      placeholder="YYYY-MM-DD"
                    />

          
                    <b-input-group-append>
                      <b-form-datepicker
                      v-model="formValues.fromDate"
                        show-decade-nav
                        button-only
                        right
                        locale="en-US"
                        aria-controls="v-from_date"
                      />
                    </b-input-group-append>

                    
                  
                  </b-input-group>
            </b-col>
            <b-col md="6">
        
              <label for="v-to_date">To Date</label>
                  <b-input-group >

                    <cleave
                      id="to_date"
                      v-model="formValues.toDate"

                      class="form-control"
                      :raw="false"
                      :options="options.date"
                      placeholder="YYYY-MM-DD"
                    />

          
                    <b-input-group-append>
                      <b-form-datepicker
                      v-model="formValues.toDate"
                        show-decade-nav
                        button-only
                        right
                        locale="en-US"
                        aria-controls="v-to_date"
                      />
                    </b-input-group-append>

                    
                  
                  </b-input-group>
            </b-col>
            <b-col md="6">
                  <b-form-group
                    label="Seller Type"
                    label-for="request_type"

                  >

                    <v-select
                      id="request_type"

                      v-model="formValues.seller_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="RequestType"
                      label="text"
                      :reduce="text => text.value"
                    />

                  </b-form-group>
                </b-col>
                <b-col md="6">
              <b-form-group
                label="Seller Name"
                label-for="v-seller_name"
              >
            <b-form-input v-model="formValues.seller_name" type="text" id="v-seller_name" placeholder="Enter Seller Name..."></b-form-input>
              </b-form-group>
            </b-col>
            <b-col cols="6">
            <div class="d-flex pt-1 my-2">
            <b-form-checkbox class="d-flex" value="VAT" v-model="formValues.vat_not_vat" name="vat_not_vat">
              VAT
            </b-form-checkbox>
              <b-form-checkbox class="d-flex ml-2 pl-2" value="NOT VAT" v-model="formValues.vat_not_vat" name="vat_not_vat">
              NOT VAT
            </b-form-checkbox>
            </div>
      </b-col>
              <b-col md="12">

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  type="submit"
                  variant="primary"
                  class="mr-1"
                  @click="page=1,searchTerm='',closeSearch(), getEmployees()"
                >

                  <span  >GO</span>
                </b-button>

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  type="submit"
                  variant="outline-primary"
                  class="mr-1"
                  @click="resetSearch"
                >

                  <span  >Reset</span>
                </b-button>

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  type="submit"
                  variant="outline-secondary"
                  class="mr-1"
                  @click="closeSearch"
                >

                  <span  >Cancel</span>
                </b-button>

                
              </b-col>
          
           </b-row>
        </b-form>
      </b-modal>

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

              <b-input-group-append v-if="$ability.can('edit', 'purchase_order')">
                <b-button
                  size="sm"
                  :to="{  name: 'edit-purchase', params: { id: row.item.id } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append
                v-if="$ability.can('delete', 'purchase_order')"
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
            <template #cell(date)="data">

            
            {{ data.value | moment("DD/MM/YYYY") }}

      </template>
      <template #cell(seller_type)="data">

            <b-badge
            v-if="data.value"
              pill
              :variant="request_type_variant(data.value)"
            >
            {{ data.value }}
            </b-badge>

      </template>




      <template #cell(net_total)="data">

        {{ Number( data.value).toLocaleString() }}
      </template>



      <template #cell(request_seller)="data">

        <span v-if="data.value">{{ data.item.request_seller }},{{data.item.request_seller_id}}</span> 
      </template>
      <!-- full detail on click -->
      <template #row-details="row">
        
    <div class="container">
        <b-row >

          <b-col md="12" class="mb-1" v-if="row.item.purchaseOrder">
            <strong>Attachment : </strong>

            <b-button
              v-ripple.400="'rgba(113, 102, 240, 0.15)'"
              variant="outline-primary"
            >
              <a
                :href="row.item.purchaseOrder"
                target="_blank"
              >View file</a>
            </b-button>

          </b-col>



          <b-col md="12" class="mb-1" v-if="row.item.purchaseType">
            <strong>Expense Type : </strong>
            {{row.item.purchaseType}}
          </b-col>

          <b-col md="12" class="mb-1" v-if="row.item.subtotal">
            <strong>Sub Total : </strong>
            {{ row.item.subtotal }}
          </b-col>
          <b-col md="12" class="mb-1" v-if="row.item.vat">
            <strong>VAT : </strong>
            {{ row.item.vat }}
          </b-col>
          <b-col md="12" class="mb-1" v-if="row.item.net_total">
            <strong>Total : </strong>
            {{ row.item.net_total }}
          </b-col>
          
          <b-col md="12" class="mb-1" v-if="row.item.vat_not_vat">
            <strong>VAT/NOT VAT : </strong>
            <!-- {{ row.item.vat_not_vat }} -->
            <b-badge
              pill
              :variant="vat_not_vat__type_variant(row.item.vat_not_vat)"
            >
            {{ row.item.vat_not_vat }}
            </b-badge>
          </b-col>
          <b-col md="12" class="mb-1 text-right" v-if="row.item.created_by">
            <strong>Created By : </strong>
            {{ row.item.created_by }}
          </b-col>
          <b-col md="12" class="mb-1 text-right" v-if="row.item.updated_by">
            <strong>Updated By : </strong>
            {{ row.item.updated_by }}
          </b-col>
          

        </b-row >
    </div>

        <b-card no-body>
          <div>
            <b-table
              :items="row.item.resourceItems"
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

              <template #cell(total)="data">

                {{ Number( data.value).toLocaleString() }}
              </template>
            </b-table>
          </div>
        </b-card>
      </template>





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
import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'
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
    Cleave,
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
      }, { key: 'date',label: 'Date', sortable: true, thClass: 'customHead' },
      { key: 'seller_type',label: 'Seller Type',  sortable: true, thClass: 'customHead' }, 
       {
        key: 'request_seller', label: 'Seller', sortable: true, thClass: 'customHead',
      }, {
        key: 'net_total', label: 'Amount', sortable: true, thClass: 'customHead',
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
      formValues: {
        purchaseType:'',
        amount:'',
        fromDate:'',
        toDate:'',
        seller_type:null,
        seller_name:null,
        vat_not_vat:null,

      },
               RequestType: [
        { value: 'Accommodation', text: 'Accommodation' },
        // { value: 'Client', text: 'Client' },
        // { value: 'Employee', text: 'Employee' },
        { value: 'Supplier', text: 'Supplier' },
        { value: 'Vendor', text: 'Vendor' },

      ],

        purchaseTypes: [
        { value: 'Product', text: 'Product' },
        { value: 'Service', text: 'Service' },
      ],
      options: {
        penColor: "#000",
        backgroundColor: '#fff',
        phone: {
          prefix: '9665',
          blocks: [12],
          uppercase: true,
          noImmediatePrefix: true
        },
        date: {
          date: true,
          delimiter: '-',
          datePattern: ['Y', 'm', 'd'],
        },
        account_number: {
          blocks: [15],
          uppercase: true,
        },
        iqama_number: {
          blocks: [10],
          uppercase: true,
        },
        cr_number: {
          blocks: [10],
          uppercase: true,
        },
        iban: {
          prefix: 'SA',
          blocks: [24],
          uppercase: true,
          noImmediatePrefix: true
        },
      },

    }
  },

  mounted() {
    this.getEmployees()
  },
  methods: {
      request_type_variant(ownerType){

        if (ownerType == 'Accommodation') 
          {
            return 'warning';
          } 
           else if(ownerType == 'Supplier') 
          {
            return 'info';
          }
          else if(ownerType == 'Vendor') 
          {
            return 'secondary';
          }
        else
          {
            return 'primary';
          }
      },
      vat_not_vat__type_variant(ownerType){

          if (ownerType == 'VAT') 
          {
            return 'success';
          } 
           else if(ownerType == 'NOT VAT') 
          {
            return 'danger';
          }
          else
          {
            return 'primary';
          }
      },
    onClose() {
      this.popoverShow = false
    },

    closeSearch() {
        this.$refs['searchModel'].hide()
      },
      resetSearch() {
        Object.entries(this.formValues).forEach(([key, value]) => { {
              this.formValues[key] = '';
            }
          
        });

        console.log(this.formValues);
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
          axios.get('/deletePurchase', {
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
                    title: 'Purchase Deleted',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Purchase deleted')
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
      axios.get('/getPurchaseList', {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,
          advanceSearch:this.formValues,

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
