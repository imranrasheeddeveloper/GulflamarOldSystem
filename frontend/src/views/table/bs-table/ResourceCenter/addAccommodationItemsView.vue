<template>
  <b-card>
    <b-form @submit.prevent>
      <b-row>

        <b-col md="6">
          <b-form-group
            label="Name"
            label-for="name"
          >

            <b-input-group>

              <b-form-input
                id="name"
                v-model="formValues.name"
                type="text"
                placeholder="Item Name"
              />
            </b-input-group>
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
  </b-card>
</template>

<script>
import {
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BFormTextarea,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import axios from '@axios'
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
    BCard,
    BFormFile,
    BSpinner,
    BInputGroup,
    BInputGroupPrepend,
    BFormCheckboxGroup,
    BFormTextarea,
  },
  directives: {
    Ripple,
  },

  data() {
    return {
      isSubmitting: false,
      formValues: {
        name: '',
        resourceType: 'Accommodation',
      },

    }
  },

  methods: {

    submitResource() {
      this.isSubmitting = true

      axios.post('/addResourceItem', this.formValues).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace(response.data.returnUrl)
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
