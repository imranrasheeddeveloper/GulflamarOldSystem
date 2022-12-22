<template>
  <b-form @submit.prevent>
    <b-row>
      <!-- form repeate -->
      <b-col md="12">
        <b-card title="Add Petty Cash ">
          <div>
            <b-form
              ref="form"
              :style="{ height: trHeight }"
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
                <!-- @input="Type_Change(item)" -->

                <b-col md="6">
                  <b-form-group
                    label="Transaction Type"
                    :v-label-for="'trans_type' + index"
                  >
                    <v-select
                      :v-id="'trans_type' + index"
                      v-model="item.transaction_type"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="RequestType"
                      label="text"
                      :reduce="(text) => text.value"
                      @input="Transaction_Type_Change(item)"
                    />
                  </b-form-group>
                </b-col>
 <b-col md="6">
                  <label :v-for="'v-date' + index">Date</label>
                  <b-input-group>
                    <cleave
                      :v-id="'v-date' + index"
                      v-model="item.date"
                      class="form-control"
                      :raw="false"
                      :options="options.date"
                      placeholder="YYYY-MM-DD"
                    />

                    <b-input-group-append>
                      <b-form-datepicker
                        v-model="item.date"
                        show-decade-nav
                        button-only
                        right
                        locale="en-US"
                        :v-aria-controls="'v-date' + index"
                      />
                    </b-input-group-append>
                  </b-input-group>
                </b-col>
                <b-col md="6">
                  <b-form-group
                    label="Assign To Category"
                    :v-label-for="'Category' + index"
                  >
                    <v-select
                      :v-id="'Category' + index"
                      v-model="item.assign_to_category"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="AssignToCategory"
                      label="text"
                      :reduce="(text) => text.value"
                      @input="Type_Change(item)"
                    />
                  </b-form-group>
                </b-col>

                <b-col md="6">
                  <b-form-group
                    label="Assign To Entity"
                    :v-label-for="'autosuggest__input' + index"
                  >
                    <vue-autosuggest
                      v-model="item.assign_to_entity"
                      :suggestions="item.filteredOptions"
                      :limit="10"
                      :input-props="{
                        id: 'autosuggest__input' + index,
                        class: 'form-control',
                        placeholder: 'Search with name or ID',
                      }"
                      @input="onInputChange(item)"
                      @selected="assignOwnerField(item, $event)"
                    >
                      <template slot-scope="{ suggestion }">
                        <span class="my-suggestion-item"
                          >{{ suggestion.item.name }}, {{ suggestion.item.id }}
                        </span>
                      </template>
                    </vue-autosuggest>
                  </b-form-group>
                </b-col>

               <b-col  md="12" v-if="item.locationField">
                  <b-form-group
                    label="Client Location"
                    :v-label-for= "'client_location' + index"
                  >
                    <v-select
                      :v-id="'client_location' + index"
                      v-model="item.client_location"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="item.fillerdLocations"
                      label="text"
                      :reduce="(text) => text.text"
                    />
                  </b-form-group>
                </b-col>

                <b-col md="4">
                  <b-form-group
                    label="Unit Price"
                    :v-label-for="'unit_price' + index"
                  >
                    <b-form-input
                      @scroll.prevent
                      :v-id="'unit_price' + index"
                      v-model.number="item.unit_price"
                      type="number"
                      placeholder="0.00"
                      min="0"
                      @input="totalAmount(item)"
                    />
                  </b-form-group>
                </b-col>
                <b-col md="4">
                  <b-form-group
                    label="Quantity"
                    :v-label-for="'quantity' + index"
                  >
                    <b-form-input
                      @scroll.prevent
                      :v-id="'quantity' + index"
                      v-model.number="item.quantity"
                      type="number"
                      placeholder="0.00"
                      min="0"
                      @input="totalAmount(item)"
                    />
                  </b-form-group>
                </b-col>
                <b-col md="4">
                  <b-form-group label="Total" :v-label-for="'total' + index">
                    <b-form-input
                      :v-id="'total' + index"
                      v-model.number="item.total"
                      type="number"
                      min="0"
                      placeholder="0.00"
                      :readonly="Readonly_Total_Amount"
                    />
                  </b-form-group>
                </b-col>

                <b-col md="6">
                  <b-form-group
                    label="Description"
                    :v-label-for="'description' + index"
                  >
                    <b-form-textarea
                      :v-id="'description' + index"
                      v-model="item.description"
                      placeholder="Write Description's Here.."
                      rows="2"
                    />
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Notes" :v-label-for="'notes' + index">
                    <b-form-textarea
                      :v-id="'notes' + index"
                      v-model="item.notes"
                      placeholder="Write Note's Here.."
                      rows="2"
                    />
                  </b-form-group>
                </b-col>

                <b-col md="6">
                  <b-form-group
                    label="Attachment (*)."
                    :v-label-for="'attach' + index"
                  >
                    <b-form-file
                      :v-id="'attach' + index"
                      multiple
                      v-model="item.attachment"
                      accept="image/* , .pdf"
                      placeholder="Upload attachment..."
                      drop-placeholder="Drop file here..."
                      @change="imageUpload($event, item)"
                    />
                  </b-form-group>
                </b-col>
                <!-- <b-col cols="2">
                  <b-form-group class="m-1 p-2">
                    <b-form-checkbox type="checkbox" v-model="item.approve">
                      <span class="text-primary"> Approve </span>

                    </b-form-checkbox>
                  </b-form-group>
                </b-col> -->
      <b-col cols="4" v-if="item.show_credit_debit">
            <div class="d-flex pt-1 mt-1">
            <b-form-checkbox class="d-flex" value="Credit" v-model="item.credit_debit" name="credit_debit">
              Credit
            </b-form-checkbox>
              <b-form-checkbox class="d-flex ml-2 pl-2" value="Debit" v-model="item.credit_debit" name="credit_debit">
              Debit
            </b-form-checkbox>
            </div>
      </b-col>
                <!-- Remove Button -->
                <b-col md="2" class="mb-50">
                  <b-button
                    v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                    variant="outline-danger"
                    class="mt-0 mt-md-2"
                    @click="removeItem(index)"
                  >
                    <feather-icon icon="XIcon" class="mr-25" />
                    <span>Delete</span>
                  </b-button>
                </b-col>
                <b-col cols="12">
                  <hr class="hr_divider" />
                </b-col>
              </b-row>
            </b-form>
          </div>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            @click="repeateAgain"
          >
            <feather-icon icon="PlusIcon" class="mr-25" />
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
          <b-spinner v-if="isSubmitting" small />
          <span v-if="isSubmitting">Please Wait</span>

          <span v-if="!isSubmitting">Submit</span>
        </b-button>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormCheckbox,
  BForm,
  BButton,
  BFormDatepicker,
  BCard,
  BSpinner,
  BFormFile,
  BFormTextarea,
  BInputGroupAppend,
  BInputGroup,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import axios from "@axios";
import { heightTransition } from "@core/mixins/ui/transition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { VueAutosuggest } from "vue-autosuggest";

import Cleave from "vue-cleave-component";
import "cleave.js/dist/addons/cleave-phone.us";

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
          delimiter: "-",
          datePattern: ["Y", "m", "d"],
        },
      },

      isSubmitting: false,

      nextTodoId: 2,

      Readonly_Total_Amount: true,

      formValues: {
        id:null,
        items: [
          {
            transaction_type: "",
            assign_to_category: "",
            assign_to_entity: "",
            assign_to_entity_id: "",
            date: "",
            unit_price: null,
            resourceOwner:"",
            quantity: null,
            total: null,
            attachment: [],
            client_location: "",
            attachment_length: 0,
            attachment_file_name: "",
            notes: "",
            description: "",
            filteredOptions: [],
            fillerdLocations: [],
            wallets:[],
            approve: false,
            accept: false,
            credit_debit: "Debit",
            show_credit_debit: false,
            locationField: false,
            wallet: false,
          },
        ],
      },
      RequestType: [
        { value: "Equipment Purchase", text: "Equipment Purchase" },
        { value: "Material Purchase", text: "Material Purchase" },
        { value: "Accommodation Purchase", text: "Accommodation Purchase" },
        { value: "Employee Advance", text: "Employee Advance" },
        { value: "Employee Payment", text: "Employee Payment" },
        { value: "Utility Purchase", text: "Utility Purchase" },
        { value: "Fuel Credit", text: "Fuel Credit" },
        { value: "Fuel Expense", text: "Fuel Expense"},
        { value: "Adjustment", text: "Adjustment" },
        { value: "Credit", text: "Credit" },
        { value: "Service Purchase", text: "Service Purchase" },
        { value: "Transportation Expense", text: "Transportation Expense" },
        { value: "Transfer to Wallet", text: "Transfer to Wallet" },
        { value: "Others", text: "Others" },
      ],

      AssignToCategory: [
        { value: "Accommodation", text: "Accommodation" },
        { value: "Client", text: "Client" },
        { value: "Employee", text: "Employee" },
        { value: "Supplier", text: "Supplier" },
        { value: "Vendor", text: "Vendor" },
        { value: "Wallet", text: "Wallet" },
      ],
      
    };
  },
  mounted() {
    this.initTrHeight();
  },
  created() {
    window.addEventListener("resize", this.initTrHeight);
  },
  destroyed() {
    window.removeEventListener("resize", this.initTrHeight);
  },
  methods: {
    totalAmount(item) {
      var TotalAmount = 0;
      TotalAmount = Number(item.unit_price) * Number(item.quantity);
      item.total = Number(TotalAmount);
    },

    Submit() {
      console.log('param',this.$route.params.id);
      this.formValues.id = this.$route.params.id;
      this.isSubmitting = true;
      // Remove all filteredOptions from formValues.items
      for (let i = 0; i < this.formValues.items.length; i++) {
        this.formValues.items[i]['filteredOptions'] = [];
        this.formValues.items[i]['fillerdLocations'] = [];
        this.formValues.items[i]['wallets'] = [];
        this.formValues.items[i]["attachment_file_name"] = "file" + i;
      }

      console.log("new form values", this.formValues);

      // create new formData for post request to send files

      const formData = new FormData();

      Object.entries(this.formValues).forEach(([key, value]) => {
        if (
          typeof value === "object" &&
          value !== null &&
          !Array.isArray(value)
        ) {
          if (value instanceof File) {
            formData.append(key, value);
          } else {
            console.log(key, value);
            formData.append(key, JSON.stringify(value));
          }
        } else if (
          typeof value === "object" &&
          value !== null &&
          Array.isArray(value)
        ) {
          formData.append(key, JSON.stringify(value));
        } else if (value !== null) {
          formData.append(key, value);
        }
      });

      // append selected files into formData with unique array name
      for (let i = 0; i < this.formValues.items.length; i++) {
        for (
          let j = 0;
          j < this.formValues.items[i]["attachment_length"];
          j++
        ) {
          formData.append(
            "file" + i + "[]",
            this.formValues.items[i]["attachment"][j]
          );
        }
      }



      axios
        .post("/addMyTransaction", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/My_List_Patty_Cash/"+this.$route.params.id).then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: "top-right",
                  props: {
                    title: response.data.message,
                    icon: "EditIcon",
                    variant: "success",
                  },
                });
              });
            } else {
              this.isSubmitting = false;

              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: response.data.message,
                  icon: "AlertCircleIcon",
                  variant: "danger",
                },
              });
            }
          } else {
            this.isSubmitting = false;

            this.$toast({
              component: ToastificationContent,
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later",
              },
            });
          }
        })
        .catch((error) => {
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

    imageUpload(e, item) {
      console.log("attachment files", e.target.files);
      if (e.target.files.length > 0) {
        for (let i = 0; i < e.target.files.length; i++) {
          item.attachment.push(e.target.files[i]);
        }
        item.attachment_length = e.target.files.length;
      } else {
        item.attachment = null;
        item.attachment_length = 0;
      }
      console.log("attachment", item);
      console.log("attachment_length", item.attachment_length);
    },

    Type_Change(item) {

      this.clearResourceOwner(item);

    },
        Transaction_Type_Change(item) {
          if(item.transaction_type == 'Adjustment' || item.transaction_type == 'Others'){
            item.show_credit_debit = true;
            item.credit_debit = "Debit";

          }else if(item.transaction_type == 'Credit' || item.transaction_type == 'Fuel Credit'){

            item.credit_debit = "Credit";
            item.show_credit_debit = false;
          }
          else{
            item.show_credit_debit = false;
            item.credit_debit = "Debit";
          }
          console.log('item.credit_debit',item.credit_debit);
          

    },
    clearResourceOwner(item) {
      console.log("clear item ", item);
      if ( item.assign_to_category == 'Client') {
      item.locationField = true;
      this.trAddHeight(100);
      } else {
          item.locationField = false;
      }
      item.assign_to_entity = "";
      item.assign_to_entity_id = "";
      item.filteredOptions = [];
      item.fillerdLocations = [];
      item.wallets = [];
      console.log("clear item new", item);

},
        onLocationInputChange(item) {
        let text = item.assign_to_entity_id;
        if (text === "" || text === undefined) {
        return;
        }
            axios.get('/getClientLocations', {
              params: {
                client_code: text,
              },
            }).then(response => {
              if (response.data.hasOwnProperty('success')) {
                if (response.data.success === true) {
                  item.fillerdLocations = response.data.data
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

    onInputChange(item) {
      let text = item.assign_to_entity;
      let type = item.assign_to_category;
      if (text === "" || text === undefined) {
        return;
      }
      if (type === "" || type === undefined) {
        return;
      }
      console.log("text", text);


      axios
        .get("/documentResourceOwner", {
          params: {
            id: text,
            type: item.assign_to_category,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              item.filteredOptions = [{ data: response.data.data }];
              console.log('item',item.filteredOptions);
              
            } else {
              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: "Error",
                  icon: "AlertCircleIcon",
                  variant: "danger",
                  text: response.data.message,
                },
              });
            }
          } else {
            this.$toast({
              component: ToastificationContent,
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later",
              },
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },

    assignOwnerField(item, event) {
      console.log("item farax", item);
      console.log("name", event.item);
      this.resourceOwner = item.assign_to_entity_id
      item.assign_to_entity = event.item.name;
      item.assign_to_entity_id = event.item.id;
      this.onLocationInputChange(item);
      return;
    },

    repeateAgain() {
      this.formValues.items.push({
        transaction_type: "",
        assign_to_category: "",
        assign_to_entity: "",
        assign_to_entity_id: "",
        client_location: "",
        date: "",
        unit_price: null,
        quantity: null,
        total: null,
        attachment: [],
        attachment_length: 0,
        attachment_file_name: "",
        notes: "",
        description: "",
        filteredOptions: [],
        fillerdLocations: [],
        wallets:[],
        approve: false,
        accept: false,
        credit_debit: "Debit",
        show_credit_debit: false,
        locationField: false,
        wallet:false,
      });
      console.log(this.formValues.items);
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight);
      });
    },
    removeItem(index) {
      this.formValues.items.splice(index, 1);
      this.trTrimHeight(this.$refs.row[0].offsetHeight);
    },
    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight);
      });
    },
  },
};
</script>

<style>
textarea {
  resize: none;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.hr_divider {
  border-bottom: 2px solid #82868b;
}
</style>
<style lang="scss">
@import "@core/scss/vue/libs/vue-autosuggest.scss";
</style>