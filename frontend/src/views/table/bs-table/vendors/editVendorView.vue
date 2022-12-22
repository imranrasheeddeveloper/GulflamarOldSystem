<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group
          label="Vendor Code"
          label-for="code"
        >
          <b-form-input
            id="code"
            v-model="formValues.code"
            type="text"
            placeholder="Vendor Code"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Company Name"
          label-for="name"
        >
          <b-form-input
            id="name"
            v-model="formValues.name"
            type="text"
            placeholder="Company Name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Company Name (ar)"
          label-for="name_ar"
        >
          <b-form-input
            id="name_ar"
            v-model="formValues.name_ar"
            type="text"
            placeholder="Company Name Arabic"
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
            placeholder="Cr Number"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="CR Attachment"
          label-for="cr_file"
        >

          <b-form-file
            id="cr_file"
            v-model="formValues.cr_file"
            accept="image/* , .pdf"
            placeholder="Upload CR attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col
        v-if="isPdf(ui_cr_file)"
        md="2"
      >

        <a
          class="btn btn-primary mt-md-2"
          :href="ui_cr_file"
          target="_blank"
        >View file</a>
      </b-col>
      <b-col
        v-else
        md="2"
      >
        <enlargeable-image
          v-if="ui_cr_file"
          :src="ui_cr_file"
          :src_large="ui_cr_file"
          animation_duration="600"
        >
          <b-img
            style="max-height: 80px;"
            thumbnail
            :src="ui_cr_file"
          />
        </enlargeable-image>
      </b-col>

      <b-col
        v-if="ui_cr_file"
        md="4"
      >
        <b-button
          v-ripple.400="'rgba(234, 84, 85, 0.15)'"
          variant="outline-danger"
          class="mt-0 mt-md-2 ml-0"
          @click="removeImage('cr_file')"
        >
          <feather-icon
            icon="XIcon"
            class="mr-25"
          />
          <span>Delete</span>
        </b-button>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="VAT Attachment"
          label-for="vat_file"
        >

          <b-form-file
            id="vat_file"
            v-model="formValues.vat_file"
            accept="image/* , .pdf"
            placeholder="Upload VAT attachment..."
            drop-placeholder="Drop file here..."
          />
        </b-form-group>
      </b-col>

      <b-col
        v-if="isPdf(ui_vat_file)"
        md="2"
      >

        <a
          class="btn btn-primary mt-md-2"
          :href="ui_vat_file"
          target="_blank"
        >View file</a>
      </b-col>
      <b-col
        v-else
        md="2"
      >
        <enlargeable-image
          v-if="ui_vat_file"
          :src="ui_vat_file"
          :src_large="ui_vat_file"
          animation_duration="600"
        >
          <b-img
            style="max-height: 80px;"
            thumbnail
            :src="ui_vat_file"
          />
        </enlargeable-image>
      </b-col>

      <b-col
        v-if="ui_vat_file"
        md="4"
      >
        <b-button
          v-ripple.400="'rgba(234, 84, 85, 0.15)'"
          variant="outline-danger"
          class="mt-0 mt-md-2 ml-0"
          @click="removeImage('vat_file')"
        >
          <feather-icon
            icon="XIcon"
            class="mr-25"
          />
          <span>Delete</span>
        </b-button>
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

      <b-col
        v-if="isPdf(ui_contract)"
        md="2"
      >

        <a
          class="btn btn-primary mt-md-2"
          :href="ui_contract"
          target="_blank"
        >View file</a>
      </b-col>
      <b-col
        v-else
        md="2"
      >
        <enlargeable-image
          v-if="ui_contract"
          :src="ui_contract"
          :src_large="ui_contract"
          animation_duration="600"
        >
          <b-img
            style="max-height: 80px;"
            thumbnail
            :src="ui_contract"
          />
        </enlargeable-image>
      </b-col>

      <b-col
        v-if="ui_contract"
        md="4"
      >
        <b-button
          v-ripple.400="'rgba(234, 84, 85, 0.15)'"
          variant="outline-danger"
          class="mt-0 mt-md-2 ml-0"
          @click="removeImage('contract')"
        >
          <feather-icon
            icon="XIcon"
            class="mr-25"
          />
          <span>Delete</span>
        </b-button>
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
          label="Contact Name (Operations)"
          label-for="contact_ops"
        >
          <b-form-input
            id="contact_ops"
            v-model="formValues.contact_ops"
            type="text"
            placeholder="Contact Name (Operations)"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Number (Operations)"
          label-for="contact_ops_no"
        >
          <b-input-group>
            <cleave
              id="contact_ops_no"
              v-model="formValues.contact_ops_no"
              class="form-control"
              :raw="false"
              :options="options.phone"
              placeholder="Contact Number (Operations)"
            />
          </b-input-group>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Name (Billing)"
          label-for="contact_billing"
        >
          <b-form-input
            id="contact_billing"
            v-model="formValues.contact_billing"
            type="text"
            placeholder="Contact Name (Billing)"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Number (Billing)"
          label-for="contact_billing_no"
        >
          <b-input-group>
            <cleave
              id="contact_billing_no"
              v-model="formValues.contact_billing_no"
              class="form-control"
              :raw="false"
              :options="options.phone"
              placeholder="Contact Number (Billing)"
            />
          </b-input-group>

        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Name (Gov Relations)"
          label-for="contact_gov"
        >
          <cleave
            id="contact_gov_no"
            v-model="formValues.contact_gov_no"
            class="form-control"
            :raw="false"
            :options="options.phone"
            placeholder="Contact Number (Gov Relations)"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group
          label="Contact Number (Gov Relations)"
          label-for="contact_gov_no"
        >
          <b-input-group>
            <b-form-input
              id="contact_gov_no"
              v-model="formValues.contact_gov_no"
              type="text"
              placeholder="Contact Number (Gov Relations)"
            />
          </b-input-group>

        </b-form-group>
      </b-col>

      <b-col md="12">

        <b-card title="Vendor Services">
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
                <b-col md="5">
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
                <b-col md="5">
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
                <b-col md="5">
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BInputGroupAppend, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import { VueAutosuggest } from 'vue-autosuggest'
import axios from '@axios'
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
      nextTodoId: 2,
      formValues: {
        id: '',
        code: '',
        name: '',
        name_ar: '',
        cr_no: '',
        cr_file: null,
        vat_no: '',
        vat_file: null,
        contact_ops: '',
        contact_ops_no: '',
        contact_billing: '',
        contact_billing_no: '',
        contact_gov: '',
        contact_gov_no: '',
        contract: null,
        services: [{
          id: 1,
          name: '',
          period: '',
          rate: 1,
        }],
      },

      ui_cr_file: '',
      ui_vat_file: '',
      ui_contract: '',
      tmp_cr: null,
      tmp_vat: null,
    }
  },
  mounted() {
    this.getVendorDetail()
    this.initTrHeight()
  },

  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
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
            tableName: 'vendor_base',
            uniqueColumn: 'id',
            uniqueValue: this.$route.params.id,
            imageColumn,
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getVendorDetail()
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

    submitResource() {
      this.isSubmitting = true
      this.formValues.id = this.$route.params.id

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

      axios.post('/updateVendor', formData).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/vendors')
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
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },

    getVendorDetail() {
      console.log(this.$route.params.empId)

      const config = {
        headers: {
          'content-type': 'multipart/form-data',

        },
      }

      axios.get('/getVendorDetail', {
        params: {
          id: this.$route.params.id,
        },
      }, config).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data)
            this.formValues.code = response.data.data.code
            this.formValues.name = response.data.data.name
            this.formValues.name_ar = response.data.data.name_ar
            this.formValues.cr_no = response.data.data.cr_no
            this.formValues.vat_no = response.data.data.vat_no
            this.formValues.contact_ops = response.data.data.contact_ops
            this.formValues.contact_ops_no = response.data.data.contact_ops_no
            this.formValues.contact_billing = response.data.data.contact_billing

            this.formValues.contact_billing_no = response.data.data.contact_billing_no
            this.formValues.contact_gov = response.data.data.contact_gov
            this.formValues.contact_gov_no = response.data.data.contact_gov_no

            this.formValues.services = response.data.data.services
            this.ui_cr_file = response.data.data.cr_file
            this.ui_vat_file = response.data.data.vat_file
            this.ui_contract = response.data.data.contract
            this.$nextTick(() => {
              this.trAddHeight(this.$refs.row[0].offsetHeight * (this.formValues.services.length - 1))
            })

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
