<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Supplier Name"
          label-for="Supplier_name"
        >
          <b-form-input
            id="Supplier_name"
            v-model="formValues.name"
            type="text"
            placeholder="Supplier Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Supplier Name (ar)"
          label-for="Supplier_name_ar"
        >
          <b-form-input
            id="Supplier_name_ar"
            v-model="formValues.name_ar"
            type="text"
            placeholder="Supplier Name (arabic)"
          />
        </b-form-group>
      </b-col>

        <b-col md="6">
        <b-form-group
          label="CR Number"
          label-for="cr_no"
        >
          <b-form-input
            id="cr_no"
            v-model="formValues.cr_number"
            type="text"
            placeholder="CR Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="VAT Number"
          label-for="vat_no"
        >
          <b-form-input
            id="vat_no"
            v-model="formValues.vat_number"
            type="text"
            placeholder="VAT Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Name"
          label-for="Contact_name"
        >
          <b-form-input
            id="Contact_name"
            v-model="formValues.contact_name"
            type="text"
            placeholder="Contact Name"
          />
        </b-form-group>
      </b-col>
        <b-col md="6">
        <b-form-group
          label="Contact Number"
          label-for="Contact_number"
        >
          <b-form-input
            id="Contact_number"
            v-model="formValues.contact_number"
            type="text"
            placeholder="Contact Number"
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
            :reduce="fullName => fullName.fullName"
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
            :reduce="full_name => full_name.full_name"
          />
        </b-form-group>
      </b-col>
        <b-col md="6">
        <b-form-group
          label="Account Name"
          label-for="account_name"
        >
          <b-form-input
            id="account_name"
            v-model="formValues.account_name"
            type="text"
            placeholder="Account Name"
          />
        </b-form-group>
      </b-col>
        <b-col md="6">
        <b-form-group
          label="IBAN"
          label-for="iban"
        >
          <b-form-input
            id="iban"
            v-model="formValues.iban"
            type="text"
            placeholder="IBAN"
          />
        </b-form-group>
      </b-col>
        <b-col md="12">

        <b-card title="Supplier Type's">
          <div>
            <b-form
              ref="form"
              :style="{height: trHeight}"
              class="repeater-form"
              @submit.prevent="repeateAgain"
            >

              <!-- Row Loop -->
              <b-row
                v-for="(item, index) in items"
                :id="item.id"
                :key="item.id"
                ref="row"
              >

                <!-- Item Name -->
                <b-col md="5">
                  <b-form-group
                    label="Add Type"
                    label-for="type"
                  >

                    <v-select
                      id="type"
                      v-model="item.type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="supplierTypes"

                      label="type"
                      :reduce="type => type.id"
                    />

                  </b-form-group>
                </b-col>


                <!-- Remove Button -->
                <b-col
                  md="2"
                  class="mb-50"
                >
                  <b-button
                    v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                    variant="outline-danger"
                    class="mt-0 mt-md-2"
                    @click="removeItem(index)"
                  >
                    <feather-icon
                      icon="XIcon"
                      class="mr-25"
                    />
                    <span>Delete</span>
                  </b-button>
                </b-col>
                <b-col cols="12">
                  <hr>
                </b-col>
              </b-row>

            </b-form>
          </div>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            @click="repeateAgain"
          >
            <feather-icon
              icon="PlusIcon"
              class="mr-25"
            />
            <span>Add more</span>
          </b-button>

        </b-card>
      </b-col>





      <!-- submit and reset -->
      <b-col md="12">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          type="submit"
          variant="primary"
          class="mx-1 my-2"
          :disabled="isSubmitting"
          @click="submitResource"
        >
          <b-spinner
            v-if="isSubmitting"
            small
          />
          <span v-if="isSubmitting">Please Wait</span>

          <span v-if="!isSubmitting" >Update</span>
        </b-button>

      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import {
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormRadio, BFormCheckboxGroup, BInputGroupAppend,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import { VueAutosuggest } from 'vue-autosuggest'
import axios from '@axios'
import { heightTransition } from '@core/mixins/ui/transition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'

export default {
  components: {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormDatepicker,
    vSelect,
    VueAutosuggest,
    BCard,
    BFormFile,
    BSpinner,
    BInputGroup,
    BInputGroupPrepend,
    BFormRadio,
    BFormCheckboxGroup,
    BInputGroupAppend,
    Cleave,
  },
  directives: {
    Ripple,
  },

  mixins: [heightTransition],

  data() {
    return {
      isSubmitting: false,

      formValues: {
          id: '',
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
        supplier_types: [],
        created_by: '',
        updated_by: '',



        

      },

      resourceItems: [],
      supplierTypes: [],
      allCities: [],
      allBanks: [],

      items: [{
        // id: 1,
        type: '',
      }],
      nextTodoId: 2,




    }
  },
  mounted() {
    this.initTrHeight()
    this.getSupplier()

    this.getSupplierTypes()

    this.getAllCities()
    this.getAllBanks()
    

  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {

    submitResource() {
      this.isSubmitting = true;
      this.formValues.supplier_types = this.items.map(a => a.type);
      this.formValues.supplier_types = [...new Set(this.formValues.supplier_types)];
      console.log('supplier_types' , this.formValues.supplier_types);
      


      axios.post('/updateSupplier',
        this.formValues).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/suppliers')
              .then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: response.data.message,
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
              })
          } else {
            this.isSubmitting = false;

            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: response.data.message,
                icon: 'AlertCircleIcon',
                variant: 'danger',
              },
            })
          }
        } else {
          this.isSubmitting = false

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

    getSupplier() {
        console.log(this.$route.params.id)
    
      axios.get('/getSupplier', {
          params: {
          id: this.$route.params.id,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
             console.log(response.data.data); 
            this.formValues = response.data.data;
            this.formValues.supplier_types = [];

            this.items = response.data.data.supplier_items.map(a => {
              return {
                type: a.supplier_types.id,
              }
            });
                this.initTrHeight()

            console.log('form' , this.formValues);
            
            console.log('items' , this.items);


            console.log('Supplier Fetched')
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

    repeateAgain() {
      console.log(this.items)
      this.items.push({
        // id: this.nextTodoId++,
        type: '',
      })
      console.log(this.items)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
  },
}
</script>
