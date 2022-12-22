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
            placeholder="Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Username"
          label-for="user_name"
        >
          <b-form-input
            id="user_name"
            v-model="formValues.user_name"
            type="text"
            placeholder="Username"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Email"
          label-for="email"
        >
          <b-form-input
            id="email"
            v-model="formValues.email"
            type="email"
            placeholder="Email"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Password"
          label-for="password"
        >
          <b-form-input
            id="password"
            v-model="formValues.password"
            type="password"
            placeholder="Password"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Mobile#"
          label-for="mobile"
        >
          <b-form-input
            id="mobile"
            v-model="formValues.mobile"
            type="text"
            placeholder="Mobile Number"
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group
          label="User Level"
          label-for="role"
        >

          <v-select
            id="role"
            v-model="formValues.role"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="resourceItems"

            label="text"
            :reduce="text => text.value"
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
      nextTodoId: 2,
      isSubmitting: false,
      monthLabelArray: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

      filteredOptions: [],
      resourceItems: [],

      vat: 10, // fixed need to make it dynamic later
      formValues: {
        name: '',
        email: '',
        user_name: '',
        password: '',
        role: '',
        mobile: '',
      },
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

    assignOwnerField(item) {
      if (item) {
        this.formValues.client_code = item.item.id
      }
    },

    calculatePrices() {
      this.formValues.subtotal = 0
      this.formValues.services.forEach((value, index) => {
        console.log(value.amount)
        this.formValues.subtotal += value.amount * value.quantity
      })

      const vat_per = this.vat / 100

      this.formValues.vat = vat_per * this.formValues.subtotal
      this.formValues.net_total = this.formValues.subtotal + this.formValues.vat
    },

    submitResource() {
      this.isSubmitting = true

      axios.post('/addUser', this.formValues).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/users')
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
            this.formValues.invoice_copy = response.data.data.image
            // create Invoice start -------------------------------------------------------------------

            axios.post('/addInvoice', this.formValues).then(response => {
              if (response.data.hasOwnProperty('success')) {
                if (response.data.success === true) {
                  console.log(response.data.data)

                  this.$router.replace('/invoices')
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

    getResourceItems() {
      axios.get('/getRolesDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.resourceItems = response.data.data

            console.log('Roles Fetched')
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
        quantity: 1,
        amount: 1,
      })
      console.log(this.formValues.services)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })

      this.calculatePrices()
    },
    removeItem(index) {
      this.formValues.services.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)

      this.calculatePrices()
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
