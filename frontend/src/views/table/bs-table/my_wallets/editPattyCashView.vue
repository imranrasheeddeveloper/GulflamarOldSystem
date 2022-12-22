<template>
  <b-form @submit.prevent>
    <b-row>
      <!-- @input="Type_Change(item)" -->

      <b-col md="6">
        <b-form-group label="Transaction Type" label-for="'trans_type">
          <v-select
            id="'trans_type"
            v-model="formValues.transaction_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="RequestType"
            label="text"
            :reduce="(text) => text.value"
            @input="Transaction_Type_Change(formValues)"
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <label for="'v-date">Date</label>
        <b-input-group>
          <cleave
            id="'v-date"
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
              aria-controls="'v-date"
            />
          </b-input-group-append>
        </b-input-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Assign To Category" label-for="'Category">
          <v-select
            id="'Category"
            v-model="formValues.assign_to_category"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="AssignToCategory"
            label="text"
            :reduce="(text) => text.value"
            @input="Type_Change()"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Assign To Entity" label-for="'autosuggest__input">
          <vue-autosuggest
            v-model="formValues.assign_to_entity"
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{
              id: 'autosuggest__input',
              class: 'form-control',
              placeholder: 'Search with name or ID',
            }"
            @input="onInputChange(formValues)"
            @selected="assignOwnerField($event)"
          >
            <template slot-scope="{ suggestion }">
              <span class="my-suggestion-item"
                >{{ suggestion.item.name }}, {{ suggestion.item.id }}
              </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>

      <b-col md="4">
        <b-form-group label="Unit Price" label-for="'unit_price">
          <b-form-input
            @scroll.prevent
            id="'unit_price"
            v-model.number="formValues.unit_price"
            type="number"
            placeholder="0.00"
            min="0"
            @input="totalAmount(formValues)"
          />
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group label="Quantity" label-for="'quantity">
          <b-form-input
            @scroll.prevent
            id="'quantity"
            v-model.number="formValues.quantity"
            type="number"
            placeholder="0.00"
            min="0"
            @input="totalAmount(formValues)"
          />
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group label="Total" label-for="'total">
          <b-form-input
            id="'total"
            v-model.number="formValues.total"
            type="number"
            min="0"
            placeholder="0.00"
            :readonly="Readonly_Total_Amount"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Description" label-for="'description">
          <b-form-textarea
            id="'description"
            v-model="formValues.description"
            placeholder="Write Description's Here.."
            rows="2"
          />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Notes" label-for="'notes">
          <b-form-textarea
            id="'notes"
            v-model="formValues.notes"
            placeholder="Write Note's Here.."
            rows="2"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Attachment (*)." label-for="'attach">
          <b-form-file
            id="'attach"
            multiple
            v-model="new_attachment"
            accept="image/* , .pdf"
            placeholder="Upload attachment..."
            drop-placeholder="Drop file here..."
            @change="imageUpload($event)"
          />
        </b-form-group>
      </b-col>
      <b-col
        md="6 my-2 border rounded m-0 p-0 text-center"
        v-for="item in formValues.attachment"
        :key="item"
      >
        <b-form-group class="m-0 p-0">
          <a
            class="btn btn-primary mx-2 my-0"
            :href="base_url + '/' + item"
            target="_blank"
            :title="item"
            >View file</a
          >
          <button
            :title="item"
            class="btn btn-danger mx-2 my-0"
            @click="reomveFile(item)"
          >
            Delete file
          </button>
        </b-form-group>
      </b-col>
      

            <b-col cols="6" v-if="show_credit_debit">
            <div class="d-flex p-1">
            <b-form-checkbox class="d-flex" value="Credit" v-model="formValues.credit_debit" name="credit_debit">
              Credit
            </b-form-checkbox>
              <b-form-checkbox class="d-flex ml-2 pl-2" value="Debit" v-model="formValues.credit_debit" name="credit_debit">
              Debit
            </b-form-checkbox>
            </div>
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

          <span v-if="!isSubmitting">Update</span>
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
      base_url: '',

      isSubmitting: false,
      show_credit_debit: false,


      Readonly_Total_Amount: true,

        new_attachment:[],

      filteredOptions: [],

      formValues: {
        id: null,
        wallet_id: null,
        transaction_type: "",
        assign_to_category: "",
        assign_to_entity: "",
        assign_to_entity_id: "",
        date: "",
        unit_price: null,
        quantity: null,
        total: null,
        attachment: [],
        notes: "",
        description: "",
        approve: false,
        accept: false,
        credit_debit: "Debit",

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
        { value: "Others", text: "Others" },
      ],

      AssignToCategory: [
        { value: "Accommodation", text: "Accommodation" },
        { value: "Client", text: "Client" },
        { value: "Employee", text: "Employee" },
        { value: "Supplier", text: "Supplier" },
        { value: "Vendor", text: "Vendor" },
      ],
    };
  },
  mounted() {
    this.initTrHeight();
    this.getDocument();
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
      console.log("param", this.$route.params.id);

      // create new formData for post request to send files

      const formData = new FormData();


      if(this.new_attachment.length > 0){
          for(let i=0;i<this.new_attachment.length;i++){
            formData.append("file[]", this.new_attachment[i]);
          }
      }
      else{
        this.new_attachment = [];
      }


      this.isSubmitting = true;
      console.log("submt", this.formValues);
      // return;


      console.log("new form values", this.formValues);



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


      axios
        .post("/updateMyTransaction", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/My_List_Patty_Cash/"+this.formValues.wallet_id).then(() => {
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
        });
    },

    getDocument() {
      console.log(this.$route.params.id);

      axios
        .get("/getMyTransaction", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);


              this.formValues = response.data.data;
              this.base_url = response.data.base_url;
              this.filteredOptions = [];
              this.set_Transaction_Type_Change(this.formValues);

              this.initTrHeight();

              console.log("form", this.formValues);

              console.log("Transaction Fetched");
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
    imageUpload(e) {
      console.log("attachment files", e.target.files);
      if (e.target.files.length > 0) {
        for (let i = 0; i < e.target.files.length; i++) {
          this.new_attachment.push(e.target.files[i]);
        }
      } else {
        this.new_attachment = null;
      }
      console.log("attachment", this.new_attachment);
    },

    Type_Change() {
      console.log("clear item ", this.formValues);
      this.formValues.assign_to_entity = "";
      this.formValues.assign_to_entity_id = "";

      this.filteredOptions = [];
      console.log("clear item new", this.formValues);
    },

        Transaction_Type_Change(item) {
          if(item.transaction_type == 'Adjustment' || item.transaction_type == 'Others'){
            this.show_credit_debit = true;
            item.credit_debit = "Debit";
          }else if(item.transaction_type == 'Credit' || item.transaction_type == 'Fuel Credit'){
              item.credit_debit = "Credit";
            this.show_credit_debit = false;


          }else{
            this.show_credit_debit = false;
            item.credit_debit = "Debit";

          }
          console.log('item.credit_debit',item.credit_debit);

    },

            set_Transaction_Type_Change(item) {



          if(item.transaction_type == 'Adjustment' || item.transaction_type == 'Others'){
            this.show_credit_debit = true;
          }else{
            this.show_credit_debit = false;

          }


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
              this.filteredOptions = [];
              // this.filteredOptions = [{ data: response.data.data }];
              this.filteredOptions = [{ data: response.data.data }];

              console.log('item',item);
              console.log('this.formValues',this.formValues);


              console.log("Assign to entity Fetched");
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
      console.log("item", item);
      console.log("name", event.item);
      this.formValues.assign_to_entity = event.item.name;
      this.formValues.assign_to_entity_id = event.item.id;
      console.log("item new", item);

      return;
    },
    reomveFile(file) {
      console.log("this.formValues.attachment", this.formValues.attachment);

      const index = this.formValues.attachment.indexOf(file);
      if (index > -1) {
        this.formValues.attachment.splice(index, 1); // 2nd parameter means remove one item only
      }
      console.log("this.formValues.attachment", this.formValues.attachment);
    },


    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        // this.trSetHeight(this.$refs.form.scrollHeight);
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