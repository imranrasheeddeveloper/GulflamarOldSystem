<template>
  
    <div>

      <!-- search input -->
      <div class="custom-search d-flex justify-content-between">
        <b-form-group>
          <div class="d-flex align-items-center">
            <!-- <label class="mr-1">Search</label> -->

            <b-input-group>
              <b-input-group-prepend>
                <b-button variant="outline-primary" @click="page=1, getPymentRequests()">
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

        <b-form-group v-if="$ability.can('add/copy', 'payment')">
          <div class="d-flex align-items-center">

            <b-input-group>


              <b-input-group-prepend>

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="primary"

                  :to="{ name: 'create-new-payment-request'}"
                  
                  title="Payment Request"

                >
                ADD+
                </b-button>
              </b-input-group-prepend>

            </b-input-group>

          </div>
        </b-form-group>
      </div>


      <!-- Advance Search Model -->
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
                    label="Request Type"
                    label-for="request_type"

                  >

                    <v-select
                      id="request_type"

                      v-model="formValues.request_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="RequestType"

                      label="text"
                      :reduce="text => text.value"
                       @input="Type_Change(formValues.request_type)"
                    />

                  </b-form-group>
                </b-col>

                <!-- <b-col md="6">
                  <b-form-group
                    label="Request Owner"
                    label-for="request_owner"
                  >
                    <b-form-input
                      id="request_owner"
                      v-model="item.request_owner"
                      type="text"
                    />
                  </b-form-group>
                </b-col> -->
                            <b-col md="6">

        <b-form-group
          label="Resource Owner"
                    label-for="autosuggest__input"

        >
          <vue-autosuggest
            v-model="formValues.request_owner"

            :suggestions="filteredOptions"

            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID',}"
            @input="onInputChange(formValues)"
            @selected="assignOwnerField($event)"
          >
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }} </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>

                <b-col md="6">
                  <b-form-group
                    label="Payemnt Type"
                    label-for="payment_type"

                  >

                    <v-select
                      id="payment_type"
                      v-model="formValues.payment_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="paymentType"

                      label="text"
                      :reduce="text => text.value"
                    />

                  </b-form-group>
                </b-col>

                      <b-col md="6">
                        <b-form-group
                        label="Status"
                        label-for="status"

                        >                      
                        <v-select

                            id="status"

                            v-model="formValues.status"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="statusType"
                           

                            label="text"
                            :reduce="text => text.value"
                        />
                        </b-form-group>
                    </b-col>

 <!-- @input="ChangePaymentStatus()" -->
              

              <b-col md="12">

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  type="submit"
                  variant="primary"
                  class="mr-1"
                  @click="page=1,searchTerm='',closeSearch(), getPymentRequests()"
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
        striped
        responsive
        class="mb-0"
        :busy="isBusy"
      >
        <template #cell(Options)="row">
        
        <div>
        <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
        <b-form-group>
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

              <b-input-group-append v-if="$ability.can('edit', 'payment')">
                <b-button
                  size="sm"
                  :to="{name: 'edit_payment_request', params: { id: row.item.id } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append
                v-if="$ability.can('delete', 'payment')"
              >
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-danger"
                  @click="deletePayment(row.item.id)"
                >
                  <feather-icon icon="Trash2Icon" />
                </b-button>
              </b-input-group-append>

              <b-input-group-append>
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  :href="'/print_payments/'+ row.item.id"
                  target="_blank"
                  variant="outline-primary"
                >
                  <feather-icon icon="PrinterIcon" />
                </b-button>
                
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-form-group>  
        </div> 
        </template>

        <!-- full detail on click -->
        <!-- <template #cell(iqama)="data">
          <b-avatar :src="data.value" />
        </template> -->

        <template #cell(status)="data">
          <b-badge
            pill
            :variant="statusVariant(data.value)"
          >
            {{ data.value}}
          </b-badge>
        </template>

        <!-- full detail on click -->

      <template #row-details="row">
        <b-card no-body>
          <b-row >
            <b-col cols="12" class="text-center p-0 m-0"><label class="h4 p-0 py-1">Payment List</label></b-col>
            <b-col cols="12">
              <b-tabs
                pills
                card
                horizontal
              >
                <b-tab :title="item.request_owner" v-for="item in row.item.payments" :key="item.id" :title-link-class="'mx-1 border border-primary tabs_margin_calss'">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>ID</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.id }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Payment Request ID</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.request_payment_id }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Request Type</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-badge
                          pill
                          :variant="request_type_variant(item.request_type)"
                        >
                        {{ item.request_type }}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Request Owner</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.request_owner }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Request Owner Id</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.request_owner_id }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Payment Type</strong>
                      </b-col>
                      <b-col cols="10">
                         <b-badge
                          pill
                          :variant="PaymentTypeVariant(item.payment_type)"
                        >
                        {{ item.payment_type }}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Amount</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.amount }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Status</strong>
                      </b-col>
                      <b-col cols="10">
                        
                        <b-badge
                          pill
                          :variant="statusVariant(item.status)"
                        >
                          {{ item.status}}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom " v-for="attach in item.attachment" :key="attach">
                      <b-col cols="2">
                        <strong>Attachment</strong>
                      </b-col>
                      <b-col cols="10">
                        
                        <a
                          class="btn btn-primary"
                          :href="base_url+'/'+attach"
                          target="_blank"
                        >View file</a>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Notes</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ item.notes }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      
                      <b-col cols="12" class="text-right">
                       <strong>Created By : </strong> {{ item.created_by }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom " v-if="item.updated_by">
                      
                      <b-col cols="12" class="text-right">
                        <strong>Updated By : </strong> {{ item.updated_by }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>



              </b-tabs>
            </b-col>
          </b-row>

          <!-- <b-button
              size="sm"
              variant="outline-secondary"
              @click="row.toggleDetails"
            >
              Hide Details
            </b-button> -->
        </b-card>
      </template>





       <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
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
        ></b-pagination>
      </div>
    </div>

    
 
</template>

<script>
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import axios from '@axios'
import vSelect from 'vue-select'
import {
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile,BFormTextarea,BFormRadio,BForm,BFormCheckboxGroup,BFormRating,BListGroupItem ,BListGroup,BPopover,BSpinner,
} from 'bootstrap-vue'


import Ripple from 'vue-ripple-directive'
import EnlargeableImage from '@diracleo/vue-enlargeable-image';
import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'
import { VueAutosuggest } from 'vue-autosuggest'

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
    BListGroupItem ,
    BPopover,
    BSpinner,
    Cleave,
    VueAutosuggest,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      base_url: '',

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

      popoverShow:false,
      exportLimit:'',
      isBusy : true,
      fields: [{ key: 'Options', thClass: 'customHead' },{key:'id',label:'Id',sortable: true,thClass: 'customHead'},  {key:'initiated_by',label:'Initiated By',sortable: true,thClass: 'customHead'},{key:'total_amount',label:'Total Amount',sortable: true,thClass: 'customHead'},{key:'date',labe:'Date',sortable: true,thClass: 'customHead'},{key:'status',label:'Status',sortable: true,thClass: 'customHead'},
      // {key:'created_by',label:'Created By',sortable: true,thClass: 'customHead'},
      ],
      itemFields: ['name','quantity'],
      items: [
      ],
      emp_detail: [
        { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' }
        ],
      /* eslint-disable global-require */
      status: [{
        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',
      },
      {
        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',
      }],
    
      searchTerm:'',
      pageSize:10,
      page:1,
      count:0,

      filteredOptions:[],

      formValues: {

        // resourceType:'',
        // resourceOwner:'',
        // allocationDate:'',
        // notes:'',


                request_type:'',
        request_owner:'',
        request_owner_id:'',

        payment_type:'',
        // amount:'',
        status:'',
        // attachment:[],
        // attachment_length:0,
        // attachment_file_name:'',

        // notes:'',
      // filteredOptions: [],


      },

      //         items: [{
      //   request_type:'',
      //   request_owner:'',
      //   request_owner_id:'',

      //   payment_type:'',
      //   amount:'',
      //   status:'',
      //   attachment:[],
      //   attachment_length:0,
      //   attachment_file_name:'',

      //   notes:'',
      // filteredOptions: [],
      // }],

    RequestType: [
        { value: 'Accommodation', text: 'Accommodation' },
        { value: 'Client', text: 'Client' },
        { value: 'Employee', text: 'Employee' },
        { value: 'Supplier', text: 'Supplier' },
        { value: 'Vendor', text: 'Vendor' },

      ],
            statusType: [
        { value: 'Unpaid', text: 'Unpaid' },
        // { value: 'Partially Paid', text: 'Partially Paid' },
        { value: 'Paid', text: 'Paid' },
      ],
        paymentType: [
        { value: 'Cash', text: 'Cash' },
        { value: 'Bank Transfer', text: 'Bank Transfer' },
      ],
      // resourceTypes: [
      //   { value: 'Employee', text: 'Employee' },
      //   { value: 'Project', text: 'Project' },
      //   { value: 'Accommodation', text: 'Accommodation' },
      // ],
      


    }
  },

  mounted () {

      this.getPymentRequests();

  },
   methods: {

      closeSearch() {
        this.$refs['searchModel'].hide()
      },

      // assignOwnerField(item) {
      //   if (item) {
          
      //     this.formValues.resourceOwner = item.item.id;
      //   }
      // },
          assignOwnerField(event) {

          this.formValues.request_owner = event.item.name;
          this.formValues.request_owner_id = event.item.id;

          console.log('item request_owner',this.formValues.request_owner);
          console.log('item request_owner_id',this.formValues.request_owner_id);


          return;
    },


    onInputChange(item) {
        let text = item.request_owner;
        let type = item.request_type;
      if (text === '' || text === undefined) {
        return
      }
        if (type === '' || type === undefined) {
        return
      }
      console.log('text',text);
    //   return;
      
      // https://app.gulflamar.com/api/getEmployeeDropdown

      axios.get('/documentResourceOwner', {
        params: {
          id: text,
          type: item.request_type,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

            console.log('Document Resource Owners Fetched')
          } else {
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Error',
                icon: 'AlertCircleIcon',
                variant: 'danger',
                text: response.data.message,
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
    },
    resourceTypeChange(val){

      this.getResourceItems()

    },

    getResourceItems() {

          axios.get('/getResourceItemsDropdown', {
                params: {
                  resourceType : this.formValues.resourceType,
                },
              }).then((response) => {

            if(response.data.hasOwnProperty('success'))
            {
              if(response.data.success === true)
              {
                /*console.log(response.data.data);*/
                this.resourceItems = response.data.data;
                
                
                console.log('Resource Items Fetched');
              }
              else
              {
                this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `Error`,
                      icon: 'AlertCircleIcon',
                      variant: 'danger',
                      text: `Something went wrong, try again later`,
                    },
                  })
              }
              
            }
            else
            {
              this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `Error`,
                      icon: 'AlertCircleIcon',
                      variant: 'danger',
                      text: `Something went wrong, try again later`,
                    },
                  })
            }

            
          }).catch((error) => {
            console.error(error);
          });


    },

      resetSearch() {
        Object.entries(this.formValues).forEach(([key, value]) => {
        
            if (key == 'skills') {

              this.formValues[key] = [];
            }
            else if(key == 'lang_eng' ||key == 'lang_ar' ||key == 'lang_hind' ||key == 'appearance_presentable' ){


                this.formValues[key] = null;


            } else {
              this.formValues[key] = '';
            }
          
        });

        console.log(this.formValues);
      },

      onClose() {
        this.popoverShow = false
      },

      statusVariant(ownerType){

        if (ownerType == 'Unpaid') 
          {
            return 'danger';
          } 
        else if(ownerType == 'Paid') 
          {
            return 'success';
          }
        else if(ownerType == 'Partially Paid') 
          {
            return 'warning';
          }
        else
          {
            return 'primary';
          }
      },

            PaymentTypeVariant(PaymentType){

        if (PaymentType == 'Cash') 
          {
            return 'primary';
          } 
        else if(PaymentType == 'Bank Transfer') 
          {
            return 'success';
          } else
          {
            return 'primary';
          }
      },
      request_type_variant(ownerType){

        if (ownerType == 'Accommodation') 
          {
            return 'warning';
          } 
        else if(ownerType == 'Client') 
          {
            return 'success';
          }
        else if(ownerType == 'Employee') 
          {
            return 'primary';
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

      isPdf(url){
       return true ;

        if(url.substr(url.lastIndexOf('.')+1) == 'pdf')
        {
            return true
        }
        else
        {
          return false
        }
      },

      deletePayment(id) {
      console.log(id);
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
            axios.post('/deletePayment', {
            id: id,
          }
          ).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getPymentRequests()

                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Payment Deleted Successfully',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Payment Deleted Successfully')
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
      getPymentRequests(){

        this.isBusy= true;

        axios.get('/getAllPymentRequests', {
            params: {
              searchTerm: this.searchTerm,
              page_no:this.page,
              advanceSearch:this.formValues,
              
            },
          })

        .then((response) => {

            console.log('response', response.data.data);
            this.items = response.data.data;
          this.base_url = response.data.base_url;
            this.count = response.data.totalRows;
            this.isBusy= false;

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
        this.page = value;
        this.isBusy= true;
        this.getPymentRequests();
      },

      searchTimeOut() {
        let timeout = null;
        clearTimeout(timeout);
        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(() => {
          this.page = 1;
          this.getPymentRequests();
          
        }, 800);
      },
        Type_Change(item) {
        // console.log('recipient_type',this.formValues.recipient_type);
        // this.formValues.recipient = null;
        // console.log('recipient',this.formValues.recipient);

        // this.recipients = null;
        this.clearResourceOwner(item);
        // if(type == "Accommodation"){
        //     this.recipients = this.recipients_accommodation;
        //     return ;

        // }
        // if(type == "Client"){
        //     this.recipients = this.recipients_client;
        //     return ;

        // }
        // if(type == "Employee"){
        //     this.recipients = this.recipients_employee;
        //     return ;

        // }
        // if(type == "Supplier"){
        //     this.recipients = this.recipients_supplier;
        //     return ;

        // }
        // if(type == "Vendor"){
        //     this.recipients = this.recipients_vendor;
        //     return ;

        // }
        // console.log('type',type);
        // this.recipients = this.recipients_accommodation;
        // return ;

    },
      clearResourceOwner(item){
      console.log('clear item ',item);
      this.formValues.request_owner = '';
      this.formValues.request_owner_id = '';

      this.filteredOptions = [];   
      console.log('clear item new',item);

    //     this.formValues.resource_owner_id = '';
    //     this.formValues.resource_owner_name = '';
    //   this.filteredOptions = [];

      // console.log('filteredOptions',this.filteredOptions);
      // console.log('this.formValues.resource_owner_id',this.formValues.resource_owner_id);
      // console.log('this.formValues.resource_owner_name',this.formValues.resource_owner_name);


      
    },
    //     totalAmount(){
    //     var TotalAmount = 0;

    //     for (let i = 0; i < this.formValues.items.length;i++){
    //         let amount = this.formValues.items[i]['amount'];
    //             TotalAmount = Number(TotalAmount) + Number(amount);            
    //     }
    //         this.formValues.total_amount = Number(TotalAmount);


    // },


   }
};
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

.tabs_margin_calss{
  margin-top: 5px !important;
  margin-bottom: 5px !important;

}

</style>
<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>