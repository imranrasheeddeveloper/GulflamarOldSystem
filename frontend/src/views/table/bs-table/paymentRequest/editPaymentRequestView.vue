<template>
  <b-form @submit.prevent>
    <b-row>
        <b-col md="6">
            <b-form-group
              label="Initiated By"
              label-for="v-Initiated_By"
            >
              <b-form-input
                id="v-Initiated_By"
                v-model="formValues.initiated_by"
                :readonly="Readonly_Initiated_By"
              />
            </b-form-group>
          </b-col>
            <b-col md="6">
            <b-form-group
              label="Total Amount"
              label-for="v-total_amount"
            >
              <b-form-input
                id="v-total_amount"
                v-model.number="formValues.total_amount"
                :readonly="Readonly_Total_Amount"
                type="number"
              />
            </b-form-group>
          </b-col>
      <!-- <b-col md="6">
        <b-form-group
          label="Status"
          label-for="v-status"
        >
          <v-select
            id="v-status"
            v-model="formValues.status"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="statusType"

            label="text"
            :reduce="text => text.value"
          />
        </b-form-group>
      </b-col> -->

            <b-col md="6">
                    <b-form-group
              label="Statust"
              label-for="v-status"
            >
              <b-form-input
                id="v-status"
                v-model="formValues.status"
                :readonly="Readonly_Status"
                type="text"
              />
            </b-form-group>
      </b-col>


      <b-col md="6">

        <label for="v-dob">Date</label>
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
              aria-controls="v-dob"
            />
          </b-input-group-append>

        </b-input-group>
      </b-col>



      <!-- form repeate -->
      <b-col md="12">

        <b-card title="Add Payment">
          <div>
            <b-form
              ref="form"
              :style="{height: trHeight}"
              class="repeater-form"
              @submit.prevent="repeateAgain"
            >

              <!-- Row Loop -->
              <b-row
                v-for="(item, index) in formValues.items"
                :id="item.id"
                :key="item.id"
                ref="row"
              >

                <b-col md="6">
                  <b-form-group
                    label="Request Type"
                    :v-label-for="'request_type'+index"

                  >

                    <v-select
                      :v-id="'request_type'+index"

                      v-model="item.request_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="RequestType"

                      label="text"
                      :reduce="text => text.value"
                       @input="Type_Change(item)"
                    />

                  </b-form-group>
                </b-col>

                <!-- <b-col md="6">
                  <b-form-group
                    label="Request Owner"
                    label-for="request_owner"
                  >
                    <b-form-input
                      id="request_owner"
                      v-model="item.request_owner"
                      type="text"
                    />
                  </b-form-group>
                </b-col> -->
                            <b-col md="6">

        <b-form-group
          label="Resource Owner"
                    :v-label-for="'autosuggest__input'+index"

        >
          <vue-autosuggest
            v-model="item.request_owner"

            :suggestions="item.filteredOptions"

            :limit="10"
            :input-props="{id:'autosuggest__input'+index,class:'form-control', placeholder:'Search with name or ID',}"
            @input="onInputChange(item)"
            @selected="assignOwnerField(item,$event)"
          >
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }} </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>

                <b-col md="6">
                  <b-form-group
                    label="Payemnt Type"
                    :v-label-for="'payment_type'+index"

                  >

                    <v-select
                      :v-id="'payment_type'+index"
                      v-model="item.payment_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="paymentType"

                      label="text"
                      :reduce="text => text.value"
                    />

                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group
                    label="Amount"
                    :v-label-for="'amount'+index"

                  >
                    <b-form-input
                       :v-id="'amount'+index"

                      @input="totalAmount"
                      v-model.number="item.amount"
                      type="number"
                      placeholder="0.00"
                    @scroll.prevent
                    />
                  </b-form-group>
                </b-col>
                      <b-col md="6">
                        <b-form-group
                        label="Status"
                        :v-label-for="'status'+index"

                        >                      
                        <v-select

                            :v-id="'status'+index"

                            v-model="item.status"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="statusType"
                            @input="ChangePaymentStatus()"

                            label="text"
                            :reduce="text => text.value"
                        />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                        label="Attachment (*)."
                        :v-label-for="'attach'+index"
                        >

                        <b-form-file
                            :v-id="'attach'+index"
                            multiple
                            v-model="item.new_attachment"
                            accept="image/* , .pdf"

                            placeholder="Upload attachment..."
                            drop-placeholder="Drop file here..."
                            @change="imageUpload($event,item)"
                        />
                        </b-form-group>
                    </b-col>
                            <b-col md="6 my-2 border rounded m-0 p-0 text-center" v-for="file in item.attachment" :key="file">
                        <b-form-group
                            class="m-0 p-0"
                        >

                        <a
                                class="btn btn-primary mx-2 my-0"
                                :href="base_url+'/'+file"
                                target="_blank"
                                :title="file"
                            >View file</a>
                            <button
                            :title="file"
                                class="btn btn-danger mx-2 my-0"
                                @click="reomveFile(item,file)"
                            >Delete file</button>
                        </b-form-group>
                    </b-col>
                    <b-col md="8">
                    <b-form-group
                        label="Notes"
                        :v-label-for="'notes'+index"

                    >

                    <b-form-textarea
                        :v-id="'notes'+index"

                        v-model="item.notes"
                        placeholder="Write Note's Here.."
                        rows="2"
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
                  <hr class="hr_divider">
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
          @click="Submit"
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
import axios from '@axios'
import { heightTransition } from '@core/mixins/ui/transition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VueAutosuggest } from 'vue-autosuggest'

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
        date: {
          date: true,
          delimiter: '-',
          datePattern: ['Y', 'm', 'd'],
        },
      },
    //   filteredOptions: [],

      isSubmitting: false,
      base_url:'',
      statusType: [
        { value: 'Unpaid', text: 'Unpaid' },
        // { value: 'Partially Paid', text: 'Partially Paid' },
        { value: 'Paid', text: 'Paid' },
      ],
        paymentType: [
        { value: 'Cash', text: 'Cash' },
        { value: 'Bank Transfer', text: 'Bank Transfer' },
      ],




    //   items: [{
    //     request_type:'',
    //     request_owner:'',
    //     request_owner_id:'',

    //     payment_type:'',
    //     amount:'',
    //     status:'',
    //     attachment:[],
    //     notes:'',
    //   filteredOptions: [],
    //   }],
      nextTodoId: 2,


// new code

      Readonly_Initiated_By:true,
      Readonly_Total_Amount:true,
      Readonly_Status: true,

    //   initiated_by : 'User Name',
        formValues: {
            total_amount:0,
            status:'Unpaid',
            date:'',
            initiated_by : '',

        items: [{
        request_type:'',
        request_owner:'',
        request_owner_id:'',

        payment_type:'',
        amount:'',
        status:'',
        attachment:[],
        new_attachment:[],

        attachment_length:0,
        attachment_file_name:'',

        notes:'',
      filteredOptions: [],
      }
      ],


      },
    RequestType: [
        { value: 'Accommodation', text: 'Accommodation' },
        { value: 'Client', text: 'Client' },
        { value: 'Employee', text: 'Employee' },
        { value: 'Supplier', text: 'Supplier' },
        { value: 'Vendor', text: 'Vendor' },

      ],

    }
  },
  mounted() {
    this.initTrHeight()
    // this.getUserLogin()
    this.getDocument()

  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {


    totalAmount(){
        var TotalAmount = 0;

        for (let i = 0; i < this.formValues.items.length;i++){
            let amount = this.formValues.items[i]['amount'];
                TotalAmount = Number(TotalAmount) + Number(amount);            
        }
            this.formValues.total_amount = Number(TotalAmount);


    },
        getDocument() {
        console.log(this.$route.params.id)
    
      axios.get('/getPaymentRequest', {
          params: {
          id: this.$route.params.id,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
          console.log(response.data.data); 
          this.formValues = response.data.data;
          this.formValues.items = response.data.data.payments;

          this.base_url = response.data.base_url;
          this.initTrHeight();
          this.ChangePaymentStatus();

          console.log('form final' , this.formValues);
          console.log('Document Fetched')
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
    ChangePaymentStatus(){
        let type = '';
        if (this.formValues.items.length > 0){
                type = this.formValues.items[0]['status'];
            
                if(type == '' || type == null){ type = "Unpaid"; }

                    const allEqual = arr => arr.every(v => v['status'] === type);
                    if(allEqual(this.formValues.items)){
                        this.formValues.status = type;
                    }else{
                        this.formValues.status = "Partially Paid";
                    }
        }else{
            type = "Unpaid";
            this.formValues.status = type;
            return ;
        }

        

    },

    Submit(){
        this.isSubmitting = true;
        this.totalAmount();
        console.log('submt',this.formValues);
        console.log('form items',this.formValues.items);
        
        delete this.formValues.payments;
        console.log('after remove payments',this.formValues);
              // Remove all filteredOptions from formValues.items
        for(let i=0;i<this.formValues.items.length;i++){
            this.formValues.items[i]['filteredOptions'] = [];
        //    delete this.formValues.items[i].filteredOptions;

            this.formValues.items[i]['attachment_file_name'] = 'file'+i;


        }
        console.log('new form values',this.formValues);

        
// return ;

        console.log('new form values',this.formValues);

        // create new formData for post request to send files

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


                // append selected files into formData with unique array name
              for(let i=0;i<this.formValues.items.length;i++){

                  for(let j=0;j<this.formValues.items[i]['attachment_length'];j++){

                      formData.append("file"+i+"[]", this.formValues.items[i]['new_attachment'][j]);

                  }


              }

     //   this.isSubmitting = false;
//       for (var pair of formData.entries()) {
//     console.log(pair[0]+ ', ' + pair[1]); 
// }
// console.log('values');
// for (var value of formData.values()) {
//    console.log(value);
// }





      axios.post('/updatePaymentRequest',
        formData).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.$router.replace('/List_Payment_Request')
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
    

    onInputChange(item) {
        let text = item.request_owner;
        let type = item.request_type;
      if (text === '' || text === undefined) {
        return
      }
        if (type === '' || type === undefined) {
        return
      }
      console.log('text',text);
    //   return;
      
      // https://app.gulflamar.com/api/getEmployeeDropdown

      axios.get('/documentResourceOwner', {
        params: {
          id: text,
          type: item.request_type,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            item.filteredOptions = [{ data: response.data.data }];

            console.log('item text change',item);
            

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
    assignOwnerField(item,event) {
          console.log('item',item);
          console.log('name',event.item);
          item.request_owner = event.item.name;
          item.request_owner_id = event.item.id;
          console.log('item new',item);


          return;
    },

    Type_Change(item) {
        // console.log('recipient_type',this.formValues.recipient_type);
        // this.formValues.recipient = null;
        // console.log('recipient',this.formValues.recipient);

        // this.recipients = null;
        this.clearResourceOwner(item);
        // if(type == "Accommodation"){
        //     this.recipients = this.recipients_accommodation;
        //     return ;

        // }
        // if(type == "Client"){
        //     this.recipients = this.recipients_client;
        //     return ;

        // }
        // if(type == "Employee"){
        //     this.recipients = this.recipients_employee;
        //     return ;

        // }
        // if(type == "Supplier"){
        //     this.recipients = this.recipients_supplier;
        //     return ;

        // }
        // if(type == "Vendor"){
        //     this.recipients = this.recipients_vendor;
        //     return ;

        // }
        // console.log('type',type);
        // this.recipients = this.recipients_accommodation;
        // return ;

    },

    clearResourceOwner(item){
      console.log('clear item ',item);
      item.request_owner = '';
      item.request_owner_id = '';

      item.filteredOptions = [];   
      console.log('clear item new',item);

    //     this.formValues.resource_owner_id = '';
    //     this.formValues.resource_owner_name = '';
    //   this.filteredOptions = [];

      // console.log('filteredOptions',this.filteredOptions);
      // console.log('this.formValues.resource_owner_id',this.formValues.resource_owner_id);
      // console.log('this.formValues.resource_owner_name',this.formValues.resource_owner_name);


      
    },
    // getUserLogin() {

    //   axios.get('/user', {
    //     params: {
    //     },
    //   }).then(response => {
    //       console.log('response User', response.data.data)
    //       console.log('user',response.data);
    //       this.formValues.initiated_by = response.data.name;

    //     }).catch(error => {
    //       console.error(error)
    //     })
    // },

    imageUpload(e,item) {
      console.log('attachment files',e.target.files);
      if(e.target.files.length > 0){
        for(let i=0;i<e.target.files.length;i++){
          item.new_attachment.push(e.target.files[i]);
        }
        item.attachment_length = e.target.files.length;
      }
      else{
        item.new_attachment = null;
        item.attachment_length = 0;

      }
      console.log('attachment',item)
      console.log('attachment_length',item.attachment_length)

    },


        reomveFile(item,file) {
        console.log('this.formValues.attachment',item.attachment);

        const index = item.attachment.indexOf(file);
        if (index > -1) {
        item.attachment.splice(index, 1); // 2nd parameter means remove one item only
        }
                console.log('this.formValues.attachment',item.attachment);

                this.initTrHeight();

    },

    repeateAgain() {
        this.totalAmount();


      this.formValues.items.push({
        // request_type:'',
        // request_owner:'',
        // payment_type:'',
        // amount:'',
        // status:'',
        // attachment:null,
        // notes:'',


        request_type:'',
        request_owner:'',
        payment_type:'',
        amount:'',
        status:'',
        new_attachment:[],
        notes:'',
        attachment_length:0,
        attachment_file_name:'',
        request_owner_id:'',
        filteredOptions: [],
        attachment:[],



      })
      console.log(this.formValues.items);
        this.totalAmount();
      this.ChangePaymentStatus();
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
      console.log('new form push',this.formValues);
                this.initTrHeight();

    },
    removeItem(index) {
      this.formValues.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
        this.totalAmount();
        this.ChangePaymentStatus();
                this.initTrHeight();

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

textarea {
    resize:none;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
.hr_divider{
  border-bottom: 2px solid #82868b;
}

</style>
<style lang="scss">
@import '@core/scss/vue/libs/vue-autosuggest.scss';
</style>

