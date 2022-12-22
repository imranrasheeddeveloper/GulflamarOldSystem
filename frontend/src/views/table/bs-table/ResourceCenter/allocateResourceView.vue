<template>
<b-form @submit.prevent>
  <b-row>
    <b-col md="6">
      <b-form-group
        label="Resource Type"
        label-for="v-resourceType"
      >
        <v-select
          id="v-resourceType"
          v-model="resourceType"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
          :options="resourceTypes"

          label="text"
          :reduce="text => text.value"
          @input="resourceTypeChange"
           >
          </v-select>
      </b-form-group>
    </b-col>

    <b-col md="6">

      <b-form-group
        label="Resource Owner"
        label-for="autosuggest__input"
      >
        <vue-autosuggest

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

      <label for="v-dob">Date</label>
      <b-input-group>

        <cleave
          id="date"
          v-model="allocationDate"
          class="form-control"
          :raw="false"
          :options="options.date"
          placeholder="YYYY-MM-DD"
        />

        <b-input-group-append>
          <b-form-datepicker
            v-model="allocationDate"
            show-decade-nav
            button-only
            right
            locale="en-US"
            aria-controls="v-dob"
          />
        </b-input-group-append>

      </b-input-group>
    </b-col>

    <b-col md="6">
      <b-form-group
        label="Attachment"
        label-for="v-attachment"
      >

        <b-form-file
          id="v-attachment"
          v-model="attachmentFile"
          accept="image/* , .pdf"
          placeholder="Upload Attachment..."
          drop-placeholder="Drop file here..."
        />
      </b-form-group>
    </b-col>
    <b-col  md="6" v-if="locationField">
      <b-form-group
        label="Location"
        label-for="resourceOwnerLocation"
      >
        <v-select
          id="resourceOwnerLocation"
          v-model="resourceOwnerLocation"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
          :options="fillerdLocations"
          label="text"
          :reduce="text => text.text"
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
          v-model="notes"
          placeholder="Notes"
          rows="1"
        />
      </b-form-group>
    </b-col>
    <!-- form repeate -->
    <b-col md="12">

      <b-card title="Resource Items">
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
              <b-col md="5">
                <b-form-group
                  label="Quantity"
                  label-for="quantity"
                >
                  <b-form-input
                    id="quantity"
                    v-model="item.quantity"
                    type="number"
                    placeholder="1"
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

    <!-- <b-col md="6">
      <b-card title="Signature">
        <VueSignaturePad
          id="signature"
          ref="signaturePad"
          class="col-12" 
          height="250px"
          :options="options"
        />
        <b-button
          v-ripple.400="'rgba(40, 199, 111, 0.15)'"
          variant="danger"
          class="mt-1"
          @click="clearCanvas"
        >
          Clear
        </b-button>
      </b-card>

    </b-col> -->

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
BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BSpinner, BFormFile, BFormTextarea, BInputGroupAppend, BInputGroup,
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
  BSpinner,
  BFormFile,
  BFormTextarea,
  BInputGroupAppend,
  Cleave,
  BInputGroup,

},
directives: {
  Ripple,
},

mixins: [heightTransition],

data() {
  return {
    options: {
      penColor: '#000',
      backgroundColor: '#fff',
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

    resourceTypes: [
      { value: 'Employee', text: 'Employee' },
      { value: 'Project', text: 'Project' },
      { value: 'Accommodation', text: 'Accommodation' },
    ],

    resourceType: 'Employee',
    filteredOptions: [],
    fillerdLocations: [],
    resourceOwner: '',
    allocationDate: '',
    attachmentFile: null,
    attachment: '',
    signature: '',
    notes: '',
    resourceOwnerLocation:'',

    resourceItems: [],
    items: [{
      id: 1,
      name: '',
      quantity: '',
    }],
    nextTodoId: 2,
    locationField: false,
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
  clearCanvas() {
    this.$refs.signaturePad.clearSignature()
  },

  submitResource() {
    this.isSubmitting = true

    /* var sign = this.$refs.signaturePad.toData();
      var isEmpty = this.$refs.signaturePad.isEmpty(); */
    // const { isEmpty, data } = this.$refs.signaturePad.saveSignature()
    // const sign = data
    // if (isEmpty) {
      // this.$toast({
      //   component: ToastificationContent,
      //   position: 'top-right',
      //   props: {
      //     title: 'Signature missing',
      //     icon: 'AlertCircleIcon',
      //     variant: 'danger',

      //   },
      // })

      // this.isSubmitting = false
    // } else {
      // this.signature = sign
      if (this.attachmentFile != null) {
        this.uploadImg(this.attachmentFile)
      } else {
        axios.post('/allocateResource', {
          resourceOwnerType: this.resourceType,
          resourceOwnerId: this.resourceOwner,
          allocationDate: this.allocationDate,
          resource_owner_location: this.resourceOwnerLocation,
          attachment: this.attachment,
          resourceItems: this.items,
          notes: this.notes,
          signature: this.signature,

        }).then(response => {
          if (response.data.hasOwnProperty('success')) {
            if (response.data.success === true) {
              console.log(response.data.data)

              this.$router.replace('/allocated_resources')
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
    // }
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
          this.attachment = response.data.data.image
          // create purchase start -------------------------------------------------------------------
          console.log(this.signature)
          axios.post('/allocateResource', {
            resourceOwnerType: this.resourceType,
            resourceOwnerId: this.resourceOwner,
            resource_owner_location: this.resourceOwnerLocation,
            allocationDate: this.allocationDate,
            attachment: this.attachment,
            resourceItems: this.items,
            notes: this.notes,
            signature: this.signature,
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                console.log(response.data.data)

                this.$router.replace('/allocated_resources')
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
          console.log(response.data.data.data , 'location_name faraz')
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
  resourceTypeChange(val) {
    this.getResourceItems()
  },

  assignOwnerField(item) {
    if (item) {
      this.resourceOwner = item.item.id
      this.onLocationInputChange(this.resourceOwner)
      this.client_code = this.resourceOwner
      console.log(this.client_code, 'shopcode')
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

  getResourceItems() {
    axios.get('/getResourceItemsDropdown', {
      params: {
        resourceType: this.resourceType,
      },
    }).then(response => {
      if (response.data.hasOwnProperty('success')) {
        if (response.data.success === true) {
          /* console.log(response.data.data); */
          if (this.resourceType == 'Project') {
          this.locationField = true;
      } else {
          this.locationField = false;
      }
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
    }).catch(error => {
      console.error(error)
    })
  },

  repeateAgain() {
    this.items.push({
      id: this.nextTodoId += this.nextTodoId,
      name: '',
      quantity: '',
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

<style>

#signature {
border: double 3px transparent;
border-radius: 5px;
background-image: linear-gradient(white, white),
  radial-gradient(circle at top left, #4bc5e8, #9f6274);
background-origin: border-box;
background-clip: content-box, border-box;
}

</style>
