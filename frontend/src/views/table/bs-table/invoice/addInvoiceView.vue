<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Client"
          label-for="client"
        >

          <vue-autosuggest

            :suggestions="filteredOptions"

            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID'}"
            @input="onInputChange"
            @selected="assignOwnerField">
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }} </span>
            </template>
          </vue-autosuggest>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <label for="date">Date</label>
        <b-input-group>

          <cleave
            id="date"
            v-model="formValues.invoice_date"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.invoice_date"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="date"
            />
          </b-input-group-append>

        </b-input-group>
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
            :options="statuses"
            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Location"
          label-for="location_en"
        >
          <v-select
            id="location_en"
            v-model="formValues.location_en"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="fillerdLocations"
            label="text"
            :reduce="text => text.text"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Period"
          label-for="period"
        >
          <vue-monthly-picker
            v-model="formValues.period"
            :month-labels="monthLabelArray"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Invoice Attachment"
          label-for="v-purchaseOrder"
        >

          <b-form-file
            id="v-purchaseOrder"
            v-model="invoiceAttachement"
            accept="image/* , .pdf"
            placeholder="Upload attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col md="12">

        <b-card title="Invoice Services">
          <div>
            <b-form
              ref="form"
              :style="{height: trHeight}"
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
                    label="Item Name"
                    label-for="item-name"
                  >

                    <v-select
                      id="item-name"
                      v-model="item.name"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="resourceItems"

                      label="text"
                      :reduce="text => text.value"
                    />

                  </b-form-group>
                </b-col>

                <!-- Quantity -->
                <b-col md="2">
                  <b-form-group
                    label="Quantity"
                    label-for="quantity"
                  >
                    <b-form-input
                      id="quantity"
                      v-model="item.quantity"
                      type="number"
                      placeholder="0"
                      @input="calculatePrices"
                    />
                  </b-form-group>
                </b-col>

                <!-- Rate -->
                <b-col md="2">
                  <b-form-group
                    label="Amount"
                    label-for="amount"
                  >
                    <b-input-group>
                      <b-input-group-prepend is-text>
                        SR
                      </b-input-group-prepend>
                      <b-form-input
                        id="rate"
                        v-model="item.amount"
                        type="number"
                        placeholder="0"
                        @input="calculatePrices"
                      />
                    </b-input-group>

                  </b-form-group>
                </b-col>

                <!-- total -->
                <b-col md="3">
                  <b-form-group
                    label="Line Total"
                    label-for="total"
                  >
                    <b-input-group>
                      <b-input-group-prepend is-text>
                        SR
                      </b-input-group-prepend>
                      <b-form-input
                        id="total"
                        v-model="item.grandTotal = item.quantity * item.amount"
                        type="number"
                        placeholder="1"
                        readonly
                      />
                    </b-input-group>

                  </b-form-group>
                </b-col>

                <!-- Remove Button -->
                <b-col
                  md="1"
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
                    <!--  <span>Delete</span> -->
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

          <div class="col-12">
            <hr>
          </div>

          <div>
            <b-row>

              <b-col md="4">
                <b-form-group
                  label="Sub Total"
                  label-for="subtotal"
                >
                  <b-form-input
                    id="subtotal"
                    v-model="formValues.subtotal"
                    type="text"
                    placeholder="Sub Total"
                    readonly
                  />
                </b-form-group>
              </b-col>

              <b-col md="4">
                <b-form-group
                  label="VAT"
                  label-for="vat"
                >
                  <b-form-input
                    id="vat"
                    v-model="formValues.vat"
                    type="text"
                    placeholder="VAT"
                    readonly
                  />
                </b-form-group>
              </b-col>

              <b-col md="4">
                <b-form-group
                  label="Total"
                  label-for="net_total"
                >
                  <b-form-input
                    id="net_total"
                    v-model="formValues.net_total"
                    type="text"
                    placeholder="Total"
                    readonly
                  />
                </b-form-group>
              </b-col>

            </b-row>
          </div>

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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BInputGroupAppend,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import VueMonthlyPicker from 'vue-monthly-picker'
import { MonthPickerInput } from 'vue-month-picker'
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
    VueMonthlyPicker,
    MonthPickerInput,
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
      nextTodoId: 2,
      isSubmitting: false,
      monthLabelArray: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

      filteredOptions: [],
      fillerdLocations: [],
      statuses: [
        { value: '1', text: 'UnPaid' },
        { value: '2', text: 'Paid' },
        { value: '3', text: 'Partially Paid' },
      ],
      invoiceAttachement: null,
      resourceItems: [],
      settings: [],
      vat: 10, // fixed need to make it dynamic later
      formValues: {
        invoice_date: '',
        status: '',
        client_code: '',
        location_en: '',
        period: '',
        invoice_copy: '',
        subtotal: 0,
        vat: 0,
        net_total: 0,
        services: [{
          id: 1,
          name: '',
          quantity: 1,
          amount: 1,
          grandTotal: 1,
        }],
      },
    }
  },
  mounted() {
    this.initTrHeight()
    this.getSettings()
    this.getResourceItems()
    this.calculatePrices()
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
        this.onLocationInputChange(this.formValues.client_code)
      }
    },
    assignLocationField(item) {
      if (item) {
        this.formValues.location_en = item.item.id
      }
    },

    getSettings() {
      axios.get('/getsettings').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.settings = response.data.data

            console.log('settings', this.settings)
            console.log('Settings Fetched')
            this.calculatePrices()
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
    calculatePrices() {
      this.formValues.subtotal = 0
      this.formValues.services.forEach((value, index) => {
        console.log(value.amount)
        this.formValues.subtotal += value.amount * value.quantity
      })

      const vat_per = this.settings.vat / 100

      this.formValues.vat = vat_per * this.formValues.subtotal
      this.formValues.net_total = this.formValues.subtotal + this.formValues.vat
    },

    onInputChange(text) {
      if (text === '' || text === undefined) {
        return
      }

      axios.get('/getEmployeeDropdown', {
        params: {
          id: text,
          resourceType: 'Project',
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

            console.log('Clients Fetched')
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

     onLocationInputChange(text) {
      if (text === '' || text === undefined) {
        return
      }
    
      axios.get('/getClientLocations', {
        params: {
          client_code: text,
          
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data.location_name)
            this.fillerdLocations = response.data.data
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

    submitResource() {
      this.isSubmitting = true

      if (this.invoiceAttachement != null) {
        this.uploadImg(this.invoiceAttachement)
      } else {
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

    getResourceItems() {
      axios.get('/getExpenceItemsDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.resourceItems = response.data.data

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


    repeateAgain() {
      this.formValues.services.push({
        id: this.nextTodoId += this.nextTodoId,
        name: '',
        quantity: 1,
        amount: 1,
        grandTotal: 1,
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
