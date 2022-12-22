<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Client Name"
          label-for="client_name"
        >
          <b-form-input
            id="client_name"
            v-model="formValues.client_name"
            type="text"
            placeholder="Client Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Client Name (ar)"
          label-for="client_name_ar"
        >
          <b-form-input
            id="client_name_ar"
            v-model="formValues.client_name_ar"
            type="text"
            placeholder="Client Name (ar)"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Address"
          label-for="address"
        >
          <b-form-input
            id="address"
            v-model="formValues.address"
            type="text"
            placeholder="Address"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="CR Number"
          label-for="cr_no"
        >
          <cleave
            id="cr_no"
            v-model="formValues.cr_no"
            class="form-control"
            :raw="false"
            :options="options.cr_number"
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
            v-model="formValues.vat_no"
            type="text"
            placeholder="VAT Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="CR Attachment"
          label-for="cr"
        >

          <b-form-file
            id="cr"
            v-model="formValues.cr"
            accept="image/* , .pdf"
            placeholder="Upload CR attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="VAT Attachment"
          label-for="vat_cert"
        >

          <b-form-file
            id="vat_cert"
            v-model="formValues.vat_cert"
            accept="image/* , .pdf"
            placeholder="Upload VAT attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="AJEER License"
          label-for="ajeer_license"
        >
          <b-form-input
            id="ajeer_license"
            v-model="formValues.ajeer_license"
            type="text"
            placeholder="AJEER License"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Department"
          label-for="dept_en"
        >
          <b-form-input
            id="dept_en"
            v-model="formValues.dept_en"
            type="text"
            placeholder="Department"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Department (ar)"
          label-for="dept_ar"
        >
          <b-form-input
            id="dept_ar"
            v-model="formValues.dept_ar"
            type="text"
            placeholder="Department (ar)"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Name"
          label-for="contactName"
        >
          <b-form-input
            id="contactName"
            v-model="formValues.contactName"
            type="text"
            placeholder="Contact Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Number"
          label-for="contactNo"
        >
          <b-form-input
            id="contactNo"
            v-model="formValues.contactNo"
            type="text"
            placeholder="Contact Number"
          />
        </b-form-group>
      </b-col>


      <b-col md="6">
        <b-form-group
          label="Account Name"
          label-for="accountName"
        >
          <b-form-input
            id="accountName"
            v-model="formValues.account_name"
            type="text"
            placeholder="Account Name"
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group
          label="Bank Name"
          label-for="bankName"
        >
          <b-form-input
            id="bankName"
            v-model="formValues.bank_name"
            type="text"
            placeholder="Bank Name"
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


      <b-col md="6">
        <b-form-group
          label="Contract File"
          label-for="contract"
        >

          <b-form-file
            id="contract"
            v-model="formValues.contract"
            accept="image/* , .pdf"
            placeholder="Upload Contract File..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contract Type"
        >
          <div class="demo-inline-spacing">
            <b-form-radio
              v-model="formValues.contract_type"
              name="contract_type"
              value="Permanent"
            >
              Permanent
            </b-form-radio>
            <b-form-radio
              v-model="formValues.contract_type"
              name="contract_type"
              value="Temporary"
            >
              Temporary
            </b-form-radio>

          </div>
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contract Status"
          label-for="contract_status"
        >
          <v-select
            id="contract_status"
            v-model="formValues.contract_status"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="contractStatus"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Operational Services"
          label-for="fat_details"
        >
          <b-form-checkbox-group
            id="fat_details"
            v-model="formValues.fat_details"
            name="Operational Services"
            class="demo-inline-spacing"
          >
            <b-form-checkbox value="Food">
              Food
            </b-form-checkbox>
            <b-form-checkbox value="Accommodation">
              Accommodation
            </b-form-checkbox>
            <b-form-checkbox value="Transportation">
              Transportation
            </b-form-checkbox>
            <b-form-checkbox value="Uniforms">
              Uniforms
            </b-form-checkbox>
            <b-form-checkbox value="Safety Shoes">
              Safety Shoes
            </b-form-checkbox>
            <b-form-checkbox value="Baladiya Card">
              Baladiya Card
            </b-form-checkbox>

          </b-form-checkbox-group>
        </b-form-group>
      </b-col>

      <b-col md="6">

        <label for="start_date">Project Start Date</label>
        <b-input-group>

          <cleave
            id="start_date"
            v-model="formValues.start_date"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.start_date"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="start_date"
            />
          </b-input-group-append>

        </b-input-group>

      </b-col>

      <b-col md="6">

        <label for="end_date">Project End Date</label>
        <b-input-group>

          <cleave
            id="end_date"
            v-model="formValues.end_date"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.end_date"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="end_date"
            />
          </b-input-group-append>

        </b-input-group>

      </b-col>

      <b-col md="6">
        <b-form-group
          label="Working Days"
          label-for="daysWeek"
        >
          <v-select
            id="daysWeek"
            v-model="formValues.daysWeek"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="daysWeeks"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Working Hours"
          label-for="hoursDay"
        >
          <v-select
            id="hoursDay"
            v-model="formValues.hoursDay"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="hoursDays"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="12">
              <!-- :style="{height: trHeight}" -->

        <b-card title="Client Services">
          <div>
            <b-form
              ref="form"
              class="repeater-form"
              @submit.prevent="repeateAgain"
            >

              <!-- Row Loop -->
              <b-row
                v-for="(item, index) in formValues.services"
                :id="item.id"
                :key="item.id"
                ref="row"
              >

                <!-- Item Name -->
                <b-col md="4">
                  <b-form-group
                    label="Name"
                    label-for="name"
                  >

                    <b-form-input
                      id="name"
                      v-model="item.name"
                      type="text"
                      placeholder="Service Name"
                    />

                  </b-form-group>
                </b-col>

                <!-- Quantity -->
                <b-col md="3">
                  <b-form-group
                    label="Period"
                    label-for="period"
                  >
                    <b-form-input
                      id="period"
                      v-model="item.period"
                      type="text"
                      placeholder="Period"
                    />
                  </b-form-group>
                </b-col>

                <!-- Rate -->
                <b-col md="3">
                  <b-form-group
                    label="Rate"
                    label-for="rate"
                  >
                    <b-input-group>
                      <b-input-group-prepend is-text>
                        SR
                      </b-input-group-prepend>
                      <b-form-input
                        id="rate"
                        v-model="item.rate"
                        type="number"
                        placeholder="0"
                      />
                    </b-input-group>

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
     <br>
      <!-- client location   -->
      <b-col md="12">
        <b-card title="Client Location">
          <div>
            <b-form
              ref="form2"
              class=""
              @submit.prevent="repeateAgainLocation"
            >
              <!-- Row Loop -->
              <b-row
                v-for="(item, index) in formValues.location"
                :id="item.id"
                :key="item.id"
                ref="row"
              > 

                <!-- Item Name -->
                <b-col md="10">
                  <b-form-group
                    label="Location"
                    label-for="Location"
                  >

                    <b-form-input
                      id="Location"
                      v-model="item.location_name"
                      type="text"
                      placeholder=" Location Name"
                    />

                  </b-form-group>
                </b-col>
                   <b-col md="10">
                  <b-input-group
                    label="Coordinates"
                    label-for="Coordinates"
                  >
                    <b-form-input
                      id="Coordinate"
                      v-model="item.coordinates"
                      type="text"
                      placeholder="Coordinates"
                    />
                     <b-input-group-append>
           
            <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-danger"
                  @click="openGoogleMaps()"
                >
                 <span>Map</span>
                  
                </b-button>
          </b-input-group-append>
                  </b-input-group>
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
                    @click="removeItemlocation(index)"
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
            @click="repeateAgainLocation">
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
          class="mr-1"
          :disabled="isSubmitting"
          @click="submitResource"
        >
          <b-spinner
            v-if="isSubmitting"
            small
          />
          <span v-if="isSubmitting">Please Wait</span>

          <span v-if="!isSubmitting">Submit</span>
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
      options: {

        phone: {
          prefix: '9665',
          blocks: [12],
          uppercase: true,
          noImmediatePrefix: true,
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
          noImmediatePrefix: true,
        },

      },
      isSubmitting: false,

      contractStatus: [
        { value: 'Active', text: 'Active' },
        { value: 'Under-Signing', text: 'Under-Signing' },
        { value: 'Terminated', text: 'Terminated' },
        { value: 'Expired', text: 'Expired' },
      ],

      daysWeeks: [
        { value: '1 Day', text: '1 Day' },
        { value: '2 Days', text: '2 Days' },
        { value: '3 Days', text: '3 Days' },
        { value: '4 Days', text: '4 Days' },
        { value: '5 Days', text: '5 Days' },
        { value: '6 Days', text: '6 Days' },
        { value: '7 Days', text: '7 Days' },
      ],

      hoursDays: [
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' },
        { value: '6', text: '6' },
        { value: '7', text: '7' },
        { value: '8', text: '8' },
        { value: '9', text: '9' },
        { value: '10', text: '10' },
        { value: '11', text: '11' },
        { value: '12', text: '12' },
        { value: '13', text: '13' },
        { value: '14', text: '14' },
        { value: '15', text: '15' },
        { value: '16', text: '16' },
        { value: '17', text: '17' },
        { value: '18', text: '18' },
        { value: '19', text: '19' },
        { value: '20', text: '20' },
        { value: '21', text: '21' },
        { value: '22', text: '22' },
        { value: '23', text: '23' },
        { value: '24', text: '24' },
      ],

      formValues: {
        client_name: '',
        client_name_ar: '',
        address: '',
        cr_no: '',
        cr: null,
        vat_no: '',
        vat_cert: null,
        ajeer_license: '',
        dept_en: '',
        dept_ar: '',
        contract: null,
        contract_type: '',
        contract_status: '',
        fat_details: [],
        start_date: '',
        end_date: '',
        daysWeek: '',
        hoursDay: '',
        contactName: '',
        contactNo: '',
        account_name: '',
        bank_name: '',
        iban: '',
        services: [{
          id: 1,
          name: '',
          period: '',
          rate: 1,
        }],
        location: [{
          id: 1,
          location_name: '',
          coordinates:'',
        }],

      },

      purchaseType: 'Product',
      filteredOptions: [],
      resourceOwner: '',
      allocationDate: '',
      /* PurchaseOrderNo:'', */
      purchaseOrderFile: null,
      fileString: '',

      purchaseItems: [],

      nextTodoId: 2,

      showServiceInput: false,

    }
  },
  mounted() {
    this.initTrHeight()
    this.getResourceItems()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {

    submitResource() {
    //  this.isSubmitting = true
      const formData = new FormData()

      Object.entries(this.formValues).forEach(([key, value]) => {
        if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
          if (value instanceof File) {
            formData.append(key, value)
          } else {
            console.log(key, value)
            formData.append(key, JSON.stringify(value))
          }
        } else if (typeof value === 'object' && value !== null && Array.isArray(value)) {
          formData.append(key, JSON.stringify(value))
        } else if (value !== null) {
          // console.log(key, value);
          formData.append(key, value)
        }
      })
      console.log("data" , formData)
      axios.post('/createClient',
        formData).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(this.formValues.account_name , "data")

            this.$router.replace('/clients')
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
            this.isSubmitting = false

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

    uploadImg(img) {
      const formData = new FormData()
      formData.append('image', img)

      axios.post('/imgUpload',
        formData).then(response => {
        console.log(response)
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `Done`,
                      icon: 'EditIcon',
                      variant: 'success',
                      text: response.data.message,
                    },
                  }) */

            console.log('imgUploadResponce', response.data)
            this.fileString = response.data.data.image
            // create purchase start -------------------------------------------------------------------

            axios.post('/createPurchase', {
              purchaseType: this.purchaseType,
              /* orderNo: this.PurchaseOrderNo, */
              date: this.allocationDate,
              purchaseOrderFile: this.fileString,
              resourceItems: this.items,
            }).then(response => {
              if (response.data.hasOwnProperty('success')) {
                if (response.data.success === true) {
                  console.log(response.data.data)

                  this.$router.replace('/purchase_list')
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
                  this.isSubmitting = false

                  this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `! ${response.data.message}`,
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
            // create purchase end-----------------------------------------------------------------------------
          } else {
            this.isSubmitting = false

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

    assignOwnerField(item) {
      if (item) {
        this.resourceOwner = item.item.id
        console.log(this.allocationDate)
      }
    },

    onInputChange(text) {
      if (text === '' || text === undefined) {
        return
      }

      axios.get('/getEmployeeDropdown', {
        params: {
          id: text,
          resourceType: this.resourceType,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

            console.log('Resource Owners Fetched')
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

    getResourceItems() {
      axios.get('/getAllResources', {
        params: {
          resourceType: this.resourceType,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.purchaseItems = response.data.data

            console.log('Resource Items Fetched')
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
      this.formValues.services.push({
        id: this.nextTodoId += this.nextTodoId,
        name: '',
        period: '',
        rate: 1,
      })
      console.log(this.formValues.services)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.formValues.services.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    repeateAgainLocation() {
      this.formValues.location.push({
        id: this.nextTodoId += this.nextTodoId,
        location_name: '',
        coordinates: '',
      })
      console.log(this.formValues.location)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItemlocation(index) {
      this.formValues.location.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
    openGoogleMaps() {
    window.open('https://www.google.com/maps', '_blank');
    },
  },
}
</script>
