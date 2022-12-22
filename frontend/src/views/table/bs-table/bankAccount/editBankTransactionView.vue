<template>
  <b-form @submit.prevent>
    <b-row>
      <b-col md="6">
        <b-form-group label="Transaction Type" label-for="transaction_type">
          <v-select
            id="transaction_type"
            v-model="formValues.transaction_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="transactionType"
            label="text"
            :reduce="(text) => text.value"
            @input="Type_Change(formValues.transaction_type)"
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

      <b-col md="6" v-if="client_payment || vendor_payment">
        <b-form-group label="Period (sub type)" label-for="period">
          <vue-monthly-picker
            v-model="formValues.sub_type"
            :month-labels="monthLabelArray"
          />
        </b-form-group>
      </b-col>

      <b-col md="6" v-if="client_payment || vendor_payment || supplier_payment">
        <b-form-group label="To/From" label-for="autosuggest__input">
          <vue-autosuggest
            v-model="formValues.to_from_name"
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{
              id: 'autosuggest__input',
              class: 'form-control',
              placeholder: 'Search with name or ID',
            }"
            @input="onInputChange"
            @selected="assignOwnerField"
          >
            <template slot-scope="{ suggestion }">
              <span class="my-suggestion-item"
                >{{ suggestion.item.name }}, {{ suggestion.item.id }}
              </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>

      <b-col md="6" v-if="employee_payment">
        <b-form-group label="Sub Type" label-for="sub_type_emp">
          <v-select
            id="sub_type_emp"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="employeePaymentType"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="hr_payments">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="hrPaymentType"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="withdrawal">
        <b-form-group label="Sub Type" label-for="sub_type_emp">
          <v-select
            id="sub_type_emp"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="withdrawalTypes"
            label="text"
            :reduce="(text) => text.value"
            @input="Type_Change_withdrawalTypes(formValues.sub_type)"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="petty_cash">
        <b-form-group label="Sub Type" label-for="sub_type_emp">
          <v-select
            id="sub_type_emp"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="pettyCashTypes"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="employee_payment || hr_payments">
        <b-form-group label="To/From" label-for="To/From_emp1">
          <v-select
            id="To/From_emp1"
            v-model="formValues.to_from"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="filteredOptions"
            label="text"
            :multiple="true"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="travelEpense">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="travelEpenseType"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="purchase_type">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="purchaseTypes"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="return_transfer">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="returnTransferTypes"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="others">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="othersTypes"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="utility_payments">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="utilityPaymentTypes"
            label="text"
            :reduce="(text) => text.value"
            @input="Type_Change_UtilityPaymentTypes(formValues.sub_type)"
          />
        </b-form-group>
      </b-col>
      <b-col
        md="6"
        v-if="utility_payments_except || withdrawal_pettyCashAccount || pettyCashAccount || transfer_to_owner"
      >
        <b-form-group label="To/From" label-for="autosuggest__input">
          <vue-autosuggest
            v-model="formValues.to_from_name"
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{
              id: 'autosuggest__input',
              class: 'form-control',
              placeholder: 'Search with name or ID',
            }"
            @input="onInputChange"
            @selected="assignOwnerField"
          >
            <template slot-scope="{ suggestion }">
              <span class="my-suggestion-item"
                >{{ suggestion.item.name }}, {{ suggestion.item.id }}
              </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>
            <b-col
        md="6"
        v-if="transfer_to_other"
      >
        <b-form-group label="Sub Type" label-for="autosuggest__input">
          <vue-autosuggest
            v-model="formValues.sub_type"
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{
              id: 'autosuggest__input',
              class: 'form-control',
              placeholder: 'Search with name or ID',
            }"
            @input="onInputChange"
            @selected="assignOwnerField_type_transfer_to_other_bank"
          >
            <template slot-scope="{ suggestion }">
              <span class="my-suggestion-item"
                >{{ suggestion.item.name }}, {{ suggestion.item.id }}
              </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="supplier_payment|| transfer_to_owner">
        <b-form-group label="Sub Type" label-for="sub_type_hr">
          <v-select
            id="sub_type_hr"
            v-model="formValues.sub_type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="filteredOptions_supplier"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Amount" label-for="v-amount_">
          <b-form-input
            @scroll.prevent
            id="v-amount_"
            v-model.number="formValues.amount"
            type="number"
            placeholder="0.00"
            min="0"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Notes" label-for="Notes">
          <b-form-textarea
            id="Notes"
            v-model="formValues.notes"
            placeholder="Write Notes Here.."
            rows="2"
          />
        </b-form-group>
      </b-col>
      <b-col md="6" v-if="un_verified || others">
        <div class="d-flex mb-2">
          <b-form-checkbox
            class="d-flex"
            value="Credit"
            v-model="formValues.credit_debit"
            name="Credit"
          >
            Credit
          </b-form-checkbox>
          <b-form-checkbox
            class="d-flex ml-2 pl-2"
            value="Debit"
            v-model="formValues.credit_debit"
            name="Debit"
          >
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
          @click="submitDocument"
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
  BFormFile,
  BSpinner,
  BInputGroup,
  BInputGroupPrepend,
  BFormRadio,
  BFormCheckboxGroup,
  BInputGroupAppend,
  BFormTextarea,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import { VueAutosuggest } from "vue-autosuggest";
import axios from "@axios";
import { heightTransition } from "@core/mixins/ui/transition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import VueMonthlyPicker from "vue-monthly-picker";

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
    BFormFile,
    BSpinner,
    BInputGroup,
    BInputGroupPrepend,
    BFormRadio,
    BFormCheckboxGroup,
    BInputGroupAppend,
    Cleave,
    BFormTextarea,
    VueMonthlyPicker,
  },
  directives: {
    Ripple,
  },

  mixins: [heightTransition],

  data() {
    return {
      client_payment: false,
      employee_payment: false,
      hr_payments: false,
      travelEpense: false,
      purchase_type: false,
      return_transfer: false,
      vendor_payment: false,
      un_verified: false,
      others: false,
      utility_payments: false,
      utility_payments_except: false,
      supplier_payment: false,
      withdrawal: false,
      withdrawal_pettyCashAccount: false,
      petty_cash: false,
      pettyCashAccount: false,
      transfer_to_owner: false,
      transfer_to_other: false,


      options: {
        date: {
          date: true,
          delimiter: "-",
          datePattern: ["Y", "m", "d"],
        },
      },
      isSubmitting: false,

      transactionType: [
        { value: "Client Payment", text: "Client Payment" },
        { value: "Employee Payments", text: "Employee Payments" },
        { value: "HR Payments", text: "HR Payments" },
        { value: "Supplier Payment", text: "Supplier Payment" },
        { value: "Transfer to Owner", text: "Transfer to Owner" },
        { value: "Transfer to Other Bank", text: "Transfer to Other Bank" },
        { value: "Travel Expense", text: "Travel Expense" },
        { value: "Utility Payments", text: "Utility Payments" },
        { value: "Vendor Payment", text: "Vendor Payment" },
        { value: "Withdrawal", text: "Withdrawal" },
        { value: "Petty Cash", text: "Petty Cash" },
        { value: "Purchase", text: "Purchase" },
        { value: "Un-Verified", text: "Un-Verified" },
        { value: "Others", text: "Others" },
        // { value: 'Transfer from other bank', text: 'Transfer from other bank' },
        { value: "Return Transfer", text: "Return Transfer" },
      ],
      employeePaymentType: [
        { value: "Salary", text: "Salary" },
        { value: "Vacation Pay", text: "Vacation Pay" },
        { value: "EOSB", text: "EOSB" },
        { value: "Allowance", text: "Allowance" },
        { value: "Others", text: "Others" },
      ],
      hrPaymentType: [
        { value: "New ID", text: "New ID" },
        { value: "ID Renewal", text: "ID Renewal" },
        { value: "Work Permit", text: "Work Permit" },
        { value: "Visa", text: "Visa" },
        { value: "Re-entry Visa", text: "Re-entry Visa" },
        { value: "Medical Insurance", text: "Medical Insurance" },
      ],
      travelEpenseType: [
        { value: "Ticket Purchase", text: "Ticket Purchase" },
        { value: "Hotel Expense", text: "Hotel Expense" },
        { value: "Others", text: "Others" },
      ],
      purchaseTypes: [
        { value: "Project Expense", text: "Project Expense" },
        { value: "Office Expense", text: "Office Expense" },
        { value: "Accommodation Expense", text: "Accommodation Expense" },
        { value: "Transport Expense", text: "Transport Expense" },
        { value: "Others", text: "Others" },
      ],
      returnTransferTypes: [
        { value: "Client", text: "Client" },
        { value: "Vendor", text: "Vendor" },
        { value: "Employee", text: "Employee" },
        { value: "HR", text: "HR" },
        { value: "Supplier", text: "Supplier" },
        { value: "Owner", text: "Owner" },
        { value: "Other Bank", text: "Other Bank" },
        { value: "Vendors", text: "Vendors" },
        { value: "Others", text: "Others" },
      ],
      othersTypes: [
        { value: "Adjustment", text: "Adjustment" },
        { value: "Others", text: "Others" },
      ],
      utilityPaymentTypes: [
        { value: "Rents", text: "Rents" },
        { value: "Electricity", text: "Electricity" },
        { value: "Water", text: "Water" },
        { value: "Internet", text: "Internet" },
        { value: "Phone", text: "Phone" },
        { value: "Fuel", text: "Fuel" },
        { value: "Others", text: "Others" },
      ],
      withdrawalTypes: [
        { value: "Petty Cash", text: "Petty Cash" },
        { value: "Expenses", text: "Expenses" },
        { value: "Profit", text: "Profit" },
        { value: "Others", text: "Others" },
      ],
      pettyCashTypes: [
        { value: "Bank Transfer", text: "Bank Transfer" },
        { value: "Cash Withdrawal", text: "Cash Withdrawal" },
      ],
      filteredOptions: [],
      filteredOptions_supplier: [],

      monthLabelArray: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],

      recipient_type: "",
      recipient_type_supplier_payment: false,

      formValues: {
        id: null,
        bank_account_id: null,
        transaction_type: "",
        date: "",
        sub_type: "",
        amount: 0,
        to_from: null,
        to_from_name: "",
        notes: "",
        credit_debit: "",
        created_by: "",
        updated_by: "",
      },
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
    Type_Change(type) {
      this.formValues.sub_type = "";
      // this.formValues.amount =  0,
      this.formValues.to_from = null;
      this.formValues.to_from_name = "";
      this.formValues.credit_debit = "";

      // Set variable true/false for show/hide dynamic fields on change transaction type

      this.client_payment = false;
      this.employee_payment = false;
      this.hr_payments = false;
      this.travelEpense = false;
      this.purchase_type = false;
      this.return_transfer = false;
      this.vendor_payment = false;
      this.un_verified = false;
      this.others = false;
      this.utility_payments = false;
      this.utility_payments_except = false;
      this.supplier_payment = false;
      this.recipient_type_supplier_payment = false;
      this.withdrawal = false;
      this.withdrawal_pettyCashAccount = false;
      this.petty_cash = false;
      this.pettyCashAccount = false;
      this.transfer_to_owner = false;
      this.transfer_to_other = false;


      this.clearResourceOwner();
      if (type == "Client Payment") {
        this.recipient_type = "Client";
        this.formValues.credit_debit = "Credit";
        this.client_payment = true;
        return;
      } else if (type == "Employee Payments") {
        this.recipient_type = "Employee";
        this.formValues.credit_debit = "Debit";
        this.employee_payment = true;
        this.onInputChange("");
        return;
      } else if (type == "HR Payments") {
        this.recipient_type = "Employee";
        this.formValues.credit_debit = "Debit";
        this.hr_payments = true;
        this.onInputChange("");
        return;
      } else if (type == "Travel Expense") {
        this.formValues.credit_debit = "Debit";
        this.travelEpense = true;
        return;
      } else if (type == "Purchase") {
        this.formValues.credit_debit = "Debit";
        this.purchase_type = true;
        return;
      } else if (type == "Return Transfer") {
        this.formValues.credit_debit = "Credit";
        this.return_transfer = true;
        return;
      } else if (type == "Vendor Payment") {
        this.recipient_type = "Vendor";
        this.formValues.credit_debit = "Debit";
        this.vendor_payment = true;
        this.onInputChange("");

        return;
      } else if (type == "Others") {
        this.formValues.credit_debit = "Credit";
        this.others = true;

        return;
      } else if (type == "Utility Payments") {
        this.formValues.credit_debit = "Debit";
        this.utility_payments = true;
        return;
      } else if (type == "Un-Verified") {
        this.formValues.credit_debit = "Credit";
        this.un_verified = true;
        return;
      } else if (type == "Supplier Payment") {
        this.formValues.credit_debit = "Debit";
        this.supplier_payment = true;
        this.recipient_type = "Payment Request";
        this.recipient_type_supplier_payment = true;

        this.onInputChange("");

        this.recipient_type = "Supplier";

        return;
      } else if (type == "Withdrawal") {
        this.formValues.credit_debit = "Debit";
        this.recipient_type = "PettyCash";
        this.withdrawal = true;
        return;
      } else if (type == "Petty Cash") {
        this.formValues.credit_debit = "Debit";
        this.recipient_type = "PettyCash";
        this.petty_cash = true;
        this.pettyCashAccount = true;

        
        return;
      } else if (type == "Transfer to Owner") {
        this.formValues.credit_debit = "Debit";
        this.transfer_to_owner = true;
        this.recipient_type_supplier_payment = true;
        this.recipient_type = "Payment Request";
        this.onInputChange("");
        this.recipient_type = "owner_bank";

        return;
      } else if (type == "Transfer to Other Bank") {
        this.formValues.credit_debit = "Debit";
        this.transfer_to_other = true;
        this.recipient_type = "Bank";

        return;
      }
    },
    Type_Change_fields(type, data) {
      if (data != null) {
        this.formValues = data;
        console.log("new form values", this.formValues);
      }
      if (type == "Client Payment") {
        this.recipient_type = "Client";
        this.client_payment = true;
      } else if (type == "Employee Payments") {
        this.recipient_type = "Employee";
        this.onInputChange("");
        this.employee_payment = true;
      } else if (type == "HR Payments") {
        this.recipient_type = "Employee";
        this.hr_payments = true;
        this.onInputChange("");
      } else if (type == "Travel Expense") {
        this.travelEpense = true;
      } else if (type == "Purchase") {
        this.purchase_type = true;
      } else if (type == "Return Transfer") {
        this.return_transfer = true;
      } else if (type == "Vendor Payment") {
        this.recipient_type = "Vendor";
        this.vendor_payment = true;
        this.onInputChange("");
      } else if (type == "Un-Verified") {
        this.un_verified = true;
      } else if (type == "Others") {
        this.others = true;
      } else if (type == "Utility Payments") {
        this.utility_payments = true;
        this.UtilityPayment_sub_type(this.formValues.sub_type);
        return;
      } else if (type == "Supplier Payment") {
        this.supplier_payment = true;
        this.recipient_type = "Payment Request";
        this.recipient_type_supplier_payment = true;

        this.onInputChange("");

        this.recipient_type = "Supplier";

        return;
      } else if (type == "Withdrawal") {
        this.recipient_type = "PettyCash";
        this.withdrawal = true;
        // this.onInputChange("");
        this.withdrawal_sub_type(this.formValues.sub_type);

        return;
      } else if (type == "Petty Cash") {
        this.recipient_type = "PettyCash";
        this.petty_cash = true;
        this.pettyCashAccount = true;
        this.onInputChange("");
        return;
      } else if (type == "Transfer to Owner") {
        this.transfer_to_owner = true;
        this.recipient_type_supplier_payment = true;
        this.recipient_type = "Payment Request";
        this.onInputChange("");
        this.recipient_type = "owner_bank";
        this.onInputChange("");

        return;
      } else if (type == "Transfer to Other Bank") {
        this.transfer_to_other = true;
        this.recipient_type = "Bank";
        this.onInputChange("");

        return;
      }
    },
    Type_Change_UtilityPaymentTypes(type) {
      this.utility_payments_except = false;

      // this.clearResourceOwner();
      if (type != "Fuel" && type != "Others") {
        this.recipient_type = "Accommodation";
        this.utility_payments_except = true;
        this.onInputChange("");
        return;
      } else {
        this.clearResourceOwner();
      }
    },
    UtilityPayment_sub_type(type) {
      this.utility_payments_except = false;

      if (type != "Fuel" && type != "Others" && type != "") {
        this.recipient_type = "Accommodation";
        this.utility_payments_except = true;
        this.onInputChange("");
        return;
      }
    },
    withdrawal_sub_type(type) {
      this.withdrawal_pettyCashAccount = false;

      if (type == "Petty Cash") {
        this.recipient_type = "PettyCash";
        this.withdrawal_pettyCashAccount = true;
        this.onInputChange("");
        return;
      }
    },
    Type_Change_withdrawalTypes(type) {
      this.withdrawal_pettyCashAccount = false;
      if (type == "Petty Cash") {
        this.recipient_type = "PettyCash";
        this.withdrawal_pettyCashAccount = true;
        // this.onInputChange("");
        return;
      } else {
        this.clearResourceOwner();
      }
    },
    clearResourceOwner() {
      console.log("clear");

      this.formValues.to_from = "";
      this.formValues.to_from_name = "";
      this.filteredOptions = [];
    },
    getDocument() {
      console.log(this.$route.params.id);

      axios
        .get("/getBankTransaction", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.formValues = response.data.data;
              // this.base_url = response.data.base_url;

              this.Type_Change_fields(
                this.formValues.transaction_type,
                response.data.data
              );
              // this.formValues = response.data.data;

              // this.formValues.recipient = recipient;
              // this.formValues.resource_owner_name = resource_owner_name;
              // this.formValues.resource_owner_id = resource_owner_id;

              this.initTrHeight();

              console.log("form", this.formValues);

              console.log("Document Fetched");
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
    submitDocument() {
      this.isSubmitting = true;

      console.log("submit", this.formValues);
      if (
        this.formValues.transaction_type == "Employee Payments" ||
        this.formValues.transaction_type == "HR Payments"
      ) {
        if (this.formValues.to_from.length > 0) {
          this.formValues.to_from = this.formValues.to_from;
        }
      }

      const formData = this.formValues;

      axios
        .post("/updateBankAccountTransaction", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router
                .replace(
                  "/bank_accounts_transactions/" +
                    this.formValues.bank_account_id
                )
                .then(() => {
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

    onInputChange(text) {
      // if (text === "" || text === undefined) {
      //   return;
      // }
      console.log("text", text);
      // return;

      // https://app.gulflamar.com/api/getEmployeeDropdown

      axios
        .get("/bankAccountResourceOwner", {
          params: {
            id: text,
            type: this.recipient_type,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              if (this.recipient_type == "Employee") {
                this.filteredOptions = response.data.data.map((item) => {
                  return {
                    value: item.id,
                    text: item.name,
                  };
                });
              } else if (this.recipient_type_supplier_payment == true) {
                this.filteredOptions_supplier = response.data.data.map(
                  (item) => {
                    return {
                      value: item.id,
                      text: item.name,
                    };
                  }
                );
                this.recipient_type_supplier_payment = false;
              } else {
                this.filteredOptions = [{ data: response.data.data }];
              }
              // this.filteredOptions = [{ data: response.data.data }];

              console.log("Fetched");
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
    assignOwnerField(item) {
      if (item) {
        this.formValues.to_from = item.item.id;
        this.formValues.to_from_name = item.item.name;
      }
    },
    assignOwnerField_type_transfer_to_other_bank(item) {
      if (item) {
        this.formValues.sub_type = item.item.id;
      }
    },

    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        // this.trSetHeight(this.$refs.form.scrollHeight)
      });
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-autosuggest.scss";
</style>
