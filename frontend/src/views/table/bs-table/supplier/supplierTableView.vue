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
                @click="page=1, getSuppliers()"
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
            <!-- <b-input-group-append>
                <b-button
                :to="{ name: 'create-new-client'}"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary">
                  ADD +
                </b-button>
              </b-input-group-append> -->
            <!--  -->
          </b-input-group>

        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'supplier')">
        <div class="d-flex align-items-center">

          <b-input-group>
            <b-input-group-prepend>

              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"

                :to="{ name: 'create-new-supplier'}"

                title="Add Supplier"
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
                label="Name"
                label-for="v-name"
              >
            <b-form-input v-model="formValues.name" id="v-name" placeholder="Enter Name"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="6">
                  <b-form-group
                    label="Supplier Type"
                    label-for="type"
                  >

                    <v-select
                      id="type"
                      v-model="formValues.supplierType"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="supplierTypes"

                      label="type"
                      :reduce="type => type.id"
                      @input="SearchTypeChange"

                    />

                  </b-form-group>
                </b-col>
                <b-col md="6">
              <b-form-group
                label="City"
                label-for="city"
              >
                  <v-select
                  id="city"
                  v-model="formValues.city"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="allCities"

                  label="fullName"
                  :reduce="fullName => fullName.name_en"
                      @input="SearchTypeChange"
                />
              </b-form-group>
            </b-col>
              <b-col md="6">
              <b-form-group
                label="Bank Name"
                label-for="bank_name"
              >
                  <v-select
                  id="bank_name"
                  v-model="formValues.bank_name"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="allBanks"

                  label="full_name"
                  :reduce="full_name => full_name.name_en"
                      @input="SearchTypeChange"
                />
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="VAT Number"
                label-for="vat"
              >
            <b-form-input v-model="formValues.vat_number" type="text" id="vat" placeholder="Enter VAT number"></b-form-input>
              </b-form-group>
            </b-col>
              <b-col md="6">
              <b-form-group
                label="CR Number"
                label-for="cr_no"
              >
            <b-form-input v-model="formValues.cr_number" type="text" id="cr_no" placeholder="Enter CR number"></b-form-input>
              </b-form-group>
            </b-col>
          
          <b-col md="6">
              <b-form-group
                label="Contact Number"
                label-for="cn_no"
              >
            <b-form-input v-model="formValues.contact_number" type="text" id="cn_no" placeholder="Enter Contact number"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="IBAN"
                label-for="iban"
              >
            <b-form-input v-model="formValues.iban" type="text" id="iban" placeholder="Enter IBAN"></b-form-input>
              </b-form-group>
            </b-col>
 


              <b-col md="12">

                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  type="submit"
                  variant="primary"
                  class="mr-1"
                  @click="page=1,searchTerm='',closeSearch(), getSuppliers()"
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

              <b-input-group-append v-if="$ability.can('edit', 'supplier')">
                <b-button
                  size="sm"
                  :to="{  name: 'edit-supplier', params: { id: row.item.id } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append
                v-if="$ability.can('delete', 'supplier')"
              >
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-danger"
                  @click="deleteSupplier(row.item.id)"
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
        <b-card no-body>
          <b-row>
            <b-col cols="12" class="m-0 p-2">

                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>City</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.city }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Bank Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.bank_name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Account Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.account_name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>IBAN</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.iban }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Supplier Types</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ SupTypes(row.item.supplier_items) }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom">
                      <b-col cols="12" class="text-right">
                        <strong>Created By : </strong>
                        {{ row.item.created_by }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom" v-if="row.item.updated_by">
                      <b-col cols="12" class="text-right">
                        <strong>Updated By : </strong>
                        {{ row.item.updated_by }}
                      </b-col>
                    </b-row>
                    
                  </b-card-text>

            </b-col>
          </b-row>

        </b-card>
      </template>


      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle" />
          <strong>Loading...</strong>
        </div>
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
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile, BFormTextarea, BFormRadio, BForm, BFormCheckboxGroup, BFormRating, BListGroupItem, BListGroup, BPopover, BSpinner,
} from 'bootstrap-vue'

import Ripple from 'vue-ripple-directive'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'

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
    BPopover,
    BSpinner,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      popoverShow: false,
      exportLimit: '',
      isBusy: true,
      fields: [{ key: 'Options', thClass: 'customHead' },
      {
        key: 'id', label: 'ID', sortable: true, thClass: 'customHead',
      }, {
        key: 'name', label: 'Name', sortable: true, thClass: 'customHead',
      }, { key: 'name_ar',label: 'Name Ar', sortable: true, thClass: 'customHead' }, {
        key: 'cr_number', label: 'CR Number', sortable: true, thClass: 'customHead',
      },
      {
        key: 'vat_number', label: 'VAT Number', sortable: true, thClass: 'customHead',
      },
      // {
      //   key: 'contact_name', label: 'Contact Name', sortable: true, thClass: 'customHead',
      // },
      {
        key: 'contact_number', label: 'Contact Number', sortable: true, thClass: 'customHead',
      },
      ],
      items: [
      ],

      /* eslint-disable global-require */

      searchTerm: '',
      pageSize: 10,
      page: 1,
      count: 0,

      formValues: {
        name: '',
        name_ar: '',
        cr_number: '',
        vat_number: '',
        contact_name: '',
        contact_number: '',
        city: '',
        bank_name: '',
        account_name: '',
        iban: '',
        created_by: '',
        updated_by: '',
        supplierType: '',











      },



      supplierTypes: [],
      allCities: [],
      allBanks: [],


    }
  },

  mounted() {

    this.getSuppliers()
    this.getSupplierTypes()
    this.getAllCities()
    this.getAllBanks()

  },
  methods: {

    onClose() {
      this.popoverShow = false
    },

    SupTypes(types) {
      var text = '';
          for(var i=0;i<types.length;i++){
            text += types[i].supplier_types.type + ','
          
            }
        return  text;
    },
    getSupplierTypes() {
      axios.get('/getAllSupplierType', {
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.supplierTypes = response.data.data

            console.log('Supplier Types Fetched')
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
    },
  
    deleteSupplier(id) {
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
            axios.post('/deleteSupplier', {
            id: id,
          }
          ).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getSuppliers()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Supplier Deleted Successfully',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Supplier Deleted Successfully')
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

    getSuppliers() {
      this.isBusy = true

      axios.get('/getAllSuppliers', {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,
          advanceSearch: this.formValues,
        },
      })

        .then(response => {
          console.log('response supplier', response.data.data)
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
      this.getSuppliers()
    },

    searchTimeOut() {
      let timeout = null
      clearTimeout(timeout)
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1
        this.getSuppliers()
      }, 800)
    },

    closeSearch() {
        this.$refs['searchModel'].hide()
      },
      resetSearch() {
        console.log(this.formValues);

        Object.entries(this.formValues).forEach(([key, value]) => { {
              this.formValues[key] = '';
            }
          
        });

        console.log(this.formValues);
      },
    SearchTypeChange(val) {
      this.getSuppliers()
    },
        getAllCities() {
      axios.get('/getAllCities', {
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.allCities = response.data.data

            console.log('All Cities Fetched')
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
    },
      getAllBanks() {
      axios.get('/getAllBanks', {
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.allBanks = response.data.data

            console.log('All Banks Name Fetched')
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
