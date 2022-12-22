<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Name"
          label-for="name"
        >
          <b-form-input
            id="name"
            v-model="formValues.name"
            type="text"
            placeholder="Accommodation Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Location"
          label-for="location"
        >
          <b-form-input
            id="location"
            v-model="formValues.location"
            type="text"
            placeholder="Accommodation Location"
          />
        </b-form-group>
      </b-col>
        <b-col md="6">
        <label for="Coordinates">Coordinates</label>
        <b-input-group>

          <b-form-input
            id="Coordinates"
            v-model="formValues.coordinates"
            type="text"
            class="form-control"
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

      <b-col md="6">
        <b-form-group
          label="Address"
          label-for="address"
        >
          <b-form-input
            id="address"
            v-model="formValues.address"
            type="text"
            placeholder="Accommodation Address"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Rooms"
          label-for="v-rooms"
        >
          <v-select
            id="v-rooms"
            v-model="formValues.rooms"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="roomsDropdown"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Beds"
          label-for="beds"
        >
          <b-form-input
            id="beds"
            v-model="formValues.beds"
            type="number"
            placeholder="No of Beds"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <label for="contract_startdate">Contract Start Date</label>
        <b-input-group>

          <cleave
            id="contract_startdate"
            v-model="formValues.contract_startdate"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.contract_startdate"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="contract_startdate"
            />
          </b-input-group-append>

        </b-input-group>
      </b-col>

      <b-col md="6">
        <label for="contract_enddate">Contract End Date</label>
        <b-input-group>

          <cleave
            id="contract_enddate"
            v-model="formValues.contract_enddate"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.contract_enddate"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="contract_enddate"
            />
          </b-input-group-append>

        </b-input-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Unit No."
          label-for="apartment"
        >
          <b-form-input
            id="apartment"
            v-model="formValues.apartment"
            type="text"
            placeholder="Appartments"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Rent"
          label-for="rent"
        >
          <b-input-group>
            <b-input-group-prepend is-text>
              SR
            </b-input-group-prepend>
            <b-form-input
              id="rent"
              v-model="formValues.rent"
              type="number"
              placeholder="Accommodation rent"
            />
          </b-input-group>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Period"
          label-for="period"
        >
          <v-select
            id="period"
            v-model="formValues.period"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="periodDropdown"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <label for="due-date">Rent Due Date</label>
        <b-input-group>

          <cleave
            id="due-date"
            v-model="formValues.dueDate"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.dueDate"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="due-date"
            />
          </b-input-group-append>

        </b-input-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Services"
          label-for="services"
        >
          <b-form-checkbox-group
            id="checkbox-group-2"
            v-model="formValues.services"
            name="services"
            class="demo-inline-spacing"
          >
            <b-form-checkbox value="Room">
              Room
            </b-form-checkbox>
            <b-form-checkbox value="Beds">
              Beds
            </b-form-checkbox>
            <b-form-checkbox value="AC">
              AC
            </b-form-checkbox>
            <b-form-checkbox value="Water">
              Water
            </b-form-checkbox>
            <b-form-checkbox value="Electricity">
              Electricity
            </b-form-checkbox>
            <b-form-checkbox value="Kitchen Equipment">
              Kitchen Equipment
            </b-form-checkbox>

          </b-form-checkbox-group>
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Electricity Account#"
          label-for="electricityAccountNo"
        >
          <cleave
            id="electricityAccountNo"
            v-model="formValues.electricityAccountNo"
            class="form-control"
            :raw="false"
            :options="options.account_number"
            placeholder="Electricity Account Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Water Bill Account#"
          label-for="waterBillAccountNo"
        >
          <cleave
            id="waterBillAccountNo"
            v-model="formValues.waterBillAccountNo"
            class="form-control"
            :raw="false"
            :options="options.account_number"
            placeholder="Water Bill Account Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Fixed Electricity Amount"
          label-for="fixedElectricityAmount"
        >
          <b-input-group>
            <b-input-group-prepend is-text>
              SR
            </b-input-group-prepend>
            <b-form-input
              id="fixedElectricityAmount"
              v-model="formValues.fixedElectricityAmount"
              type="number"
              placeholder="Fixed Electricity Amount"
            />
          </b-input-group>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Fixed Water Amount"
          label-for="fixedWaterAmount"
        >
          <b-input-group>
            <b-input-group-prepend is-text>
              SR
            </b-input-group-prepend>
            <b-form-input
              id="fixedWaterAmount"
              v-model="formValues.fixedWaterAmount"
              type="number"
              placeholder="Fixed Water Amount"
            />
          </b-input-group>

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
            placeholder="Contact Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Number"
          label-for="contactNo"
        >
          <cleave
            id="contactNo"
            v-model="formValues.contactNo"
            class="form-control"
            :raw="false"
            :options="options.phone"
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
            v-model="formValues.accountName"
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
          <!-- <b-form-input
             id="bankName"
            v-model="formValues.bankName"
            type="text"
            placeholder="Bank Name"
          /> -->

          <v-select
            id="bankName"
            v-model="formValues.bankName"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="bankDropdown"

            label="text"
            :reduce="text => text.value"
          />

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="IBAN"
          label-for="iban"
        >
          <cleave
            id="iban"
            v-model="formValues.iban"
            class="form-control"
            :raw="false"
            :options="options.iban"
            placeholder="Enter IBAN"
          />
        </b-form-group>
      </b-col>

      <b-col md="12">
        <b-form-group
          label="Notes"
          label-for="notes"
        >
          <b-form-textarea
            id="notes"
            v-model="formValues.notes"
            placeholder="Notes"
            rows="3"
          />
        </b-form-group>
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BInputGroupAppend, BFormTextarea,
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
    BFormCheckboxGroup,
    BInputGroupAppend,
    Cleave,
    BFormTextarea,
  },
  directives: {
    Ripple,
  },

  mixins: [heightTransition],

  data() {
    return {
      options: {

        phone: {
          blocks: [12],
          uppercase: true,
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
          blocks: [24],
          uppercase: true,
        },

      },
      isSubmitting: false,
      formValues: {
        name: '',
        location: '',
        coordinates: '',
        address: '',
        rooms: '',
        beds: '',
        contract_startdate: '',
        contract_enddate: '',
        rent: '',
        services: [],
        dueDate: '',
        period: '',
        apartment: '',
        electricityAccountNo: '',
        waterBillAccountNo: '',

        contactName: '',
        contactNo: '',
        accountName: '',
        bankName: '',
        iban: '',
        fixedElectricityAmount: '',
        fixedWaterAmount: '',
        id: '',
        notes: '',
      },

      roomsDropdown: [
        { value: '1 Room', text: '1 Room' },
        { value: '2 Rooms', text: '2 Rooms' },
        { value: '3 Rooms', text: '3 Rooms' },
        { value: '4 Rooms', text: '4 Rooms' },
        { value: '5 Rooms', text: '5 Rooms' },
        { value: '6 Rooms', text: '6 Rooms' },
        { value: '7 Rooms', text: '7 Rooms' },
        { value: '8 Rooms', text: '8 Rooms' },
        { value: '9 Rooms', text: '9 Rooms' },
      ],

      periodDropdown: [
        { value: 'Monthly', text: 'Monthly' },
        { value: 'Bi-Monthly', text: 'Bi-Monthly' },
        { value: 'Quarterly', text: 'Quarterly' },
        { value: '4 Months', text: '4 Months' },
        { value: '6 Months', text: '6 Months' },
        { value: 'Annual', text: 'Annual' },
      ],

      bankDropdown: [
        { value: 'Al Rajhi', text: 'Al Rajhi' },
        { value: 'Al Ahli', text: 'Al Ahli' },
        { value: 'Alinma', text: 'Alinma' },
        { value: 'Riyadh Bank', text: 'Riyadh Bank' },
        { value: 'Banque Saudi Fransi', text: 'Banque Saudi Fransi' },
        { value: 'The Saudi Investment Bank', text: 'The Saudi Investment Bank' },
        { value: 'The National Commercial Bank', text: 'The National Commercial Bank' },
        { value: 'The Saudi British Bank (SABB)', text: 'The Saudi British Bank (SABB)' },
        { value: 'Samba Financial Group (Samba)', text: 'Samba Financial Group (Samba)' },
        { value: 'Alawwal bank', text: 'Alawwal bank' },
        { value: 'Arab National Bank', text: 'Arab National Bank' },
        { value: 'Bank AlBilad', text: 'Bank AlBilad' },
        { value: 'Bank AlJazira', text: 'Bank AlJazira' },
        { value: 'Saudi National Bank', text: 'Saudi National Bank' },
        { value: 'Gulf International Bank Saudi Arabia (GIB-SA)', text: 'Gulf International Bank Saudi Arabia (GIB-SA)' },
      ],

    }
  },
  mounted() {
    this.getaccommodationDetail()
  },

  methods: {

    getaccommodationDetail() {
      console.log(this.$route.params.id)

      const config = {
        headers: {
          'content-type': 'multipart/form-data',

        },
      }

      axios.get('/getAccommodationDetail', {
        params: {
          id: this.$route.params.id,
        },
      }, config).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data)

            const responseData = response.data.data

            this.formValues.name = responseData.name
            this.formValues.location = responseData.location
            this.formValues.coordinates = `${responseData.latitude},${responseData.longitude}`
            this.formValues.address = responseData.address
            this.formValues.rooms = responseData.rooms
            this.formValues.beds = responseData.beds
            this.formValues.contract_startdate = responseData.contract_startdate
            this.formValues.contract_enddate = responseData.contract_enddate
            this.formValues.rent = responseData.rent
            this.formValues.services = responseData.services
            this.formValues.dueDate = responseData.dueDate
            this.formValues.period = responseData.period
            this.formValues.apartment = responseData.apartment
            this.formValues.electricityAccountNo = responseData.electricityAccountNo
            this.formValues.waterBillAccountNo = responseData.waterBillAccountNo
            this.formValues.contactName = responseData.contactName
            this.formValues.contactNo = responseData.contactNo
            this.formValues.accountName = responseData.accountName
            this.formValues.bankName = responseData.bankName
            this.formValues.iban = responseData.iban
            this.formValues.fixedElectricityAmount = responseData.fixedElectricityAmount
            this.formValues.fixedWaterAmount = responseData.fixedWaterAmount
            this.formValues.notes = response.data.data.notes

            this.formValues.id = this.$route.params.id

            console.log('done')
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
    openGoogleMaps() {
    window.open('https://www.google.com/maps', '_blank');
    },  

    submitResource() {
      this.isSubmitting = true

      axios.post('/updateAccommodation', this.formValues).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/accommodations')
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
      }).catch(error => {
        console.error(error)
      })
    },

  },
}
</script>
