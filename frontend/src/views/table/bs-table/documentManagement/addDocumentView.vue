<template>
  <b-form @submit.prevent>
    <b-row>
      <b-col md="6">
        <b-form-group
          label="Recipient Type"
          label-for="Recipient_Type"
        >
          <v-select
            id="Recipient_Type"
            v-model="formValues.recipient_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="recipientType"
            label="text"
            :reduce="text => text.value"
            @input="Type_Change(formValues.recipient_type)"


          />
        </b-form-group>
      </b-col>
        <!-- <b-col md="6">
        <b-form-group
          label="Resource Owner"
          label-for="resource_owner"
        >
          <v-select
            id="resource_owner"
            v-model="formValues.recipient"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="recipients"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col> -->
            <b-col md="6">

        <b-form-group
          label="Recipient"
          label-for="autosuggest__input"
        >
          <vue-autosuggest
            v-model="formValues.resource_owner_name"

            :suggestions="filteredOptions"

            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID',}"
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
          label="Document Type"
          label-for="Recipient"
        >
          <v-select
            id="Recipient"
            v-model="formValues.recipient"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="recipients"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col>

        <b-col md="6">
        <label for="date">Date</label>
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


            <b-col md="6">
        <b-form-group
          label="Attachment (*)"
          label-for="attach"
        >

          <b-form-file
            id="attach"
            multiple
            v-model="attachment"
            accept="image/* , .pdf"
            placeholder="Upload attachment..."
            drop-placeholder="Drop file here..."
            @change="imageUpload($event)"
          />
        </b-form-group>
      </b-col>
            <b-col md="6">
        <b-form-group
          label="Method"
          label-for="Method"
        >
          <v-select
            id="Method"
            v-model="formValues.method"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="Methods"
            label="text"
            :reduce="text => text.value"


          />
        </b-form-group>
      </b-col>
                <b-col md="6">
                  <b-form-group
                    label="Received by:"
                    label-for="Received_by"
                  >
                    <b-form-input
                      @scroll.prevent
                      id="Received_by"
                      v-model="formValues.received_by"
                    />
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group
                    label="Received at:"
                    label-for="Received_at"
                  >
                    <b-form-input
                      @scroll.prevent
                      id="Received_at"
                      v-model="formValues.received_at"
                    />
                  </b-form-group>
                </b-col>
        <b-col md="12">
            <b-form-group
              label="Description"
              label-for="Description"
            >

              <b-form-textarea
                id="Description"
                v-model="formValues.description"
                placeholder="Write Description Here.."
                rows="5"
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
          @click="submitDocument"
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormRadio, BFormCheckboxGroup, BInputGroupAppend,BFormTextarea,
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
    BFormTextarea,

  },
  directives: {
    Ripple,
  },

  mixins: [heightTransition],

  data() {
    return {
      options: {

        date: {
          date: true,
          delimiter: '-',
          datePattern: ['Y', 'm', 'd'],
        },

      },
      isSubmitting: false,

      recipientType: [
        { value: 'Accommodation', text: 'Accommodation' },
        { value: 'Client', text: 'Client' },
        { value: 'Employee', text: 'Employee' },
        { value: 'Supplier', text: 'Supplier' },
        { value: 'Vendor', text: 'Vendor' },
        

      ],
      Methods: [
        { value: 'Whatsapp', text: 'Whatsapp' },
        { value: 'Email', text: 'Email' },
        { value: 'Mail', text: 'Mail' },
        { value: 'In-person', text: 'In-person' },
        { value: 'Other', text: 'Other' },
      ],
        recipients: [
        { value: 'New Contract', text: 'New Contract' },
        { value: 'Renewal Contract', text: 'Renewal Contract' },
        { value: 'Contract Termination', text: 'Contract Termination' },
        { value: 'Rent Payment', text: 'Rent Payment' },
        { value: 'Electricity Payment', text: 'Electricity Payment' },
        { value: 'Others', text: 'Others' },
      ],
        recipients_accommodation: [
        { value: 'New Contract', text: 'New Contract' },
        { value: 'Renewal Contract', text: 'Renewal Contract' },
        { value: 'Contract Termination', text: 'Contract Termination' },
        { value: 'Rent Payment', text: 'Rent Payment' },
        { value: 'Electricity Payment', text: 'Electricity Payment' },
        { value: 'Others', text: 'Others' },
      ],
        recipients_client: [
        { value: 'Addendum', text: 'Addendum' },
        { value: 'Statement Of Account', text: 'Statement Of Account' },
        { value: 'Balance Confirmation', text: 'Balance Confirmation' },
        { value: 'Delayed Payment', text: 'Delayed Payment' },
        { value: 'Dispatch Notice', text: 'Dispatch Notice' },
        { value: 'Non-Renewal', text: 'Non-Renewal' },
        { value: 'Renewal', text: 'Renewal' },
        { value: 'Termination / Suspension', text: 'Termination / Suspension' },
        { value: 'Others', text: 'Others' },


      ],
        recipients_vendor: [
        { value: 'AJEER Request', text: 'AJEER Request' },
        { value: 'Balance Confirmation', text: 'Balance Confirmation' },
        { value: 'Employee Runaway', text: 'Employee Runaway' },
        { value: 'Employee Termination', text: 'Employee Termination' },
        { value: 'Employee Vacation Request', text: 'Employee Vacation Request' },
        { value: 'Iqama Expiry', text: 'Iqama Expiry' },
        { value: 'Contract Compliance', text: 'Contract Compliance' },
        { value: 'Financial Compliance', text: 'Financial Compliance' },
        { value: 'Others', text: 'Others' },



      ],
        recipients_employee: [
        { value: 'Salary Slip', text: 'Salary Slip' },
        { value: 'Employment Certificate', text: 'Employment Certificate' },
        { value: 'Entry & Exit Certificate', text: 'Entry & Exit Certificate' },
        { value: 'Salary Certificate', text: 'Salary Certificate' },
        { value: 'Vacation Benefits', text: 'Vacation Benefits' },
        { value: 'EOSB', text: 'EOSB' },
        { value: 'Others', text: 'Others' },


      ],
        recipients_supplier: [
        { value: 'Balance Confirmation', text: 'Balance Confirmation' },
        { value: 'Statement of Account', text: 'Statement of Account' },
        { value: 'Cancellation', text: 'Cancellation' },
        { value: 'Refund', text: 'Refund' },
        { value: 'Others', text: 'Others' },

      ],

      filteredOptions: [],


      attachment: [],

      formValues: {
        recipient_type: 'Accommodation',
        recipient: '',
        resource_owner_id:'',
        resource_owner_name:'',

        description:'',
        
        date:'',
        method:'',
        received_by:'',
        received_at:'',



      },


    }
  },
  mounted() {
    this.initTrHeight()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {

    Type_Change(type) {
        // console.log('recipient_type',this.formValues.recipient_type);
        this.formValues.recipient = null;
        // console.log('recipient',this.formValues.recipient);

        this.recipients = null;
        this.clearResourceOwner();
        if(type == "Accommodation"){
            this.recipients = this.recipients_accommodation;
            return ;

        }
        if(type == "Client"){
            this.recipients = this.recipients_client;
            return ;

        }
        if(type == "Employee"){
            this.recipients = this.recipients_employee;
            return ;

        }
        if(type == "Supplier"){
            this.recipients = this.recipients_supplier;
            return ;

        }
        if(type == "Vendor"){
            this.recipients = this.recipients_vendor;
            return ;

        }
        console.log('type',type);
        this.recipients = this.recipients_accommodation;
        return ;

    },

    clearResourceOwner(){
      console.log('clear');
      
        this.formValues.resource_owner_id = '';
        this.formValues.resource_owner_name = '';
      this.filteredOptions = [];

      // console.log('filteredOptions',this.filteredOptions);
      // console.log('this.formValues.resource_owner_id',this.formValues.resource_owner_id);
      // console.log('this.formValues.resource_owner_name',this.formValues.resource_owner_name);


      
    },

    submitDocument() {
      // console.log('submit', this.formValues);
      // return ; 
      
      this.isSubmitting = true
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
          formData.append(key, value)
        }
      })
      
        if(this.attachment.length > 0){
          for(let i=0;i<this.attachment.length;i++){
            formData.append("file[]", this.attachment[i]);
          }
      }
      else{
        this.attachment = null;
      }
      axios.post('/createDocument',
        formData).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/document_management')
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

    onInputChange(text) {
      if (text === '' || text === undefined) {
        return
      }
      console.log('text',text);

      axios.get('/documentResourceOwner', {
        params: {
          id: text,
          type: this.formValues.recipient_type,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

            console.log('Document Resource Owners Fetched')
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
        this.formValues.resource_owner_id = item.item.id;
        this.formValues.resource_owner_name = item.item.name;
        console.log('this.formValues.resource_owner_idt',this.formValues.resource_owner_id);
        console.log('this.formValues.resource_owner_name',this.formValues.resource_owner_name);

      }
    },
    imageUpload(e) {
      console.log('attachment files',e.target.files);
      if(e.target.files.length > 0){
        for(let i=0;i<e.target.files.length;i++){
          this.attachment.push(e.target.files[i]);
        }
      }
      else{
        this.attachment = null;
      }
      console.log('attachment',this.attachment)
    },

    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        // this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
  },
}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>
