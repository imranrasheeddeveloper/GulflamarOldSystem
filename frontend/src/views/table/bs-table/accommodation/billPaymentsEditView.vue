<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Accommodation"
          label-for="accommodation"
        >

          <vue-autosuggest

            v-model="oldAccommodation"

            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID'}"
            @input="onInputChange"
            @selected="assignOwnerField"
          >
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }} </span>
            </template>
          </vue-autosuggest>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Amount"
          label-for="amount"
        >

          <b-input-group>
            <b-input-group-prepend is-text>
              SR
            </b-input-group-prepend>
            <b-form-input
              id="location"
              v-model="formValues.amount"
              type="text"
              placeholder="Amount payed"
            />
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Bill Period"
          label-for="periodFrom"
        >
          <!-- <b-form-datepicker
              id="periodFrom"
              v-model="formValues.billMonth"
              class="mb-1"
            /> -->

          <vue-monthly-picker
            v-model="formValues.billMonth"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Bill Type"
          label-for="periodTo"
        >

          <v-select
            id="periodTo"
            v-model="formValues.billType"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="billTypes"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Attachment"
          label-for="v-purchaseOrder"
        >

          <b-form-file
            id="v-purchaseOrder"
            v-model="purchaseOrderFile"
            accept="image/* , .pdf"
            placeholder="Upload attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col
        v-if="isPdf(ui_attachment)"
        md="2"
      >

        <a
          class="btn btn-primary mt-md-2"
          :href="ui_attachment"
          target="_blank"
        >View file</a>
      </b-col>
      <b-col
        v-else
        md="2"
      >
        <enlargeable-image
          v-if="ui_attachment"
          :src="ui_attachment"
          :src_large="ui_attachment"
          animation_duration="600"
        >
          <b-img
            style="max-height: 80px;"
            thumbnail
            :src="ui_attachment"
          />
        </enlargeable-image>
      </b-col>

      <b-col
        v-if="ui_attachment"
        md="4"
      >
        <b-button
          v-ripple.400="'rgba(234, 84, 85, 0.15)'"
          variant="outline-danger"
          class="mt-0 mt-md-2 ml-0"
          @click="removeImage('attachment')"
        >
          <feather-icon
            icon="XIcon"
            class="mr-25"
          />
          <span>Delete</span>
        </b-button>
      </b-col>

      <b-col md="6">
        <label for="date">Date of payment</label>
        <b-input-group>

          <cleave
            id="date"
            v-model="formValues.date"
            class="form-control"
            :raw="false"
            :options="options.date"
            placeholder="YYYY-MM-DD"
          />

          <b-input-group-append>
            <b-form-datepicker
              v-model="formValues.date"
              show-decade-nav
              button-only
              right
              locale="en-US"
              aria-controls="date"
            />
          </b-input-group-append>

        </b-input-group>
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BFormTextarea, BInputGroupAppend, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import { VueAutosuggest } from 'vue-autosuggest'
import axios from '@axios'
import VueMonthlyPicker from 'vue-monthly-picker'
import { heightTransition } from '@core/mixins/ui/transition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'

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
    BFormTextarea,
    VueMonthlyPicker,
    BInputGroupAppend,
    Cleave,
    EnlargeableImage,
    BImg,
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
          prefix: 'SA',
          blocks: [24],
          uppercase: true,
          noImmediatePrefix: true,
        },

      },
      isSubmitting: false,
      billTypes: [
        { value: 'Electricity', text: 'Electricity' },
        { value: 'Water', text: 'Water' },
        { value: 'Others', text: 'Others' },
      ],
      filteredOptions: [],
      formValues: {
        amount: '',
        accommodation_base_id: '',
        billType: '',
        billMonth: '',
        notes: '',
        id: '',
        newAttachment: '',
      },

      ui_attachment: '',

      purchaseOrderFile: null,

      oldAccommodation: '',

    }
  },
  mounted() {
    this.getPaymentDetail()
  },

  methods: {

    removeImage(imageColumn) {
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
          axios.post('/deleteImage', {
            tableName: 'accommodationBillPayment',
            uniqueColumn: 'id',
            uniqueValue: this.$route.params.id,
            imageColumn,
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getPaymentDetail()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: response.data.message,
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
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

    isPdf(url) {
      if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
        return true
      }

      return false
    },

    getPaymentDetail() {
      console.log(this.$route.params.id)

      const config = {
        headers: {
          'content-type': 'multipart/form-data',

        },
      }

      axios.get('/getBillPaymentDetails', {
        params: {
          id: this.$route.params.id,
        },
      }, config).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data)

            this.formValues.accommodation_base_id = response.data.data.accommodation_base_id
            this.oldAccommodation = response.data.data.accommodation
            this.formValues.billType = response.data.data.billType
            this.formValues.amount = response.data.data.amount
            this.formValues.billMonth = response.data.data.period
            this.formValues.notes = response.data.data.notes
            this.formValues.date = response.data.data.date

            this.ui_attachment = response.data.data.attachment

            console.log('done')
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

    assignOwnerField(item) {
      if (item) {
        this.formValues.accommodation_base_id = item.item.id
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
          resourceType: 'Accommodation',
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

            console.log('Accommodations Fetched')
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
      this.formValues.id = this.$route.params.id

      if (this.purchaseOrderFile != null) {
        this.uploadImg(this.purchaseOrderFile)
      } else {
        axios.post('/updateAccommodationBillPayment', this.formValues).then(response => {
          if (response.data.hasOwnProperty('success')) {
            if (response.data.success === true) {
              console.log(response.data.data)

              this.$router.replace(`/view/accommodation/bills/${response.data.accommodationId}`)
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
            this.formValues.newAttachment = response.data.data.image
            // create purchase start -------------------------------------------------------------------

            axios.post('/updateAccommodationBillPayment', this.formValues).then(response => {
              if (response.data.hasOwnProperty('success')) {
                if (response.data.success === true) {
                  console.log(response.data.data)

                  this.$router.replace(`/view/accommodation/bills/${response.data.accommodationId}`)
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

  },
}
</script>
