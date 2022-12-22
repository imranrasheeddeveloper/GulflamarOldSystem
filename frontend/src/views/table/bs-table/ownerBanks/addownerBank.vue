<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Account Name"
          label-for="Account_Name"
        >
          <b-form-input
            id="Account_Name"
            v-model="formValues.account_name"
            type="text"
            placeholder="Account Name..."
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group
          label="Bank Name"
          label-for="Bank_Name"
        >
          <b-form-input
            id="Bank_Name"
            v-model="formValues.bank_name"
            type="text"
            placeholder="Bank Name..."
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group
          label="IBAN"
          label-for="IBAN"
        >
          <b-form-input
            id="IBAN"
            v-model="formValues.iban"
            type="text"
            placeholder="IBAN..."
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
          @click="submitSupplierType"
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import VueMonthlyPicker from 'vue-monthly-picker'
import { MonthPickerInput } from 'vue-month-picker'
import { VueAutosuggest } from 'vue-autosuggest'
import axios from '@axios'
import { heightTransition } from '@core/mixins/ui/transition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
    VueMonthlyPicker,
    MonthPickerInput,
  },
  directives: {
    Ripple,
  },

  mixins: [heightTransition],

  data() {
    return {
      isSubmitting: false,
      formValues: {
        bank_name: '',
        account_name: '',
        iban: '',
      },
    }
  },
  mounted() {
  },


  methods: {
    submitSupplierType() {
      this.isSubmitting = true

      axios.post('/addOwnerBank', this.formValues).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/ownerBanks')
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

  },
}
</script>
