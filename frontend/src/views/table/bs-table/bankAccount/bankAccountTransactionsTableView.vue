<template>
  <div>
    <!-- search input -->
    <div class="custom-search d-flex justify-content-between">
      <b-form-group>
        <div class="d-flex align-items-center">
          <!-- <label class="mr-1">Search</label> -->

          <b-input-group>
            <b-input-group-prepend>
              <b-button
                variant="outline-primary"
                @click="(page = 1), getDocuments()"
              >
                <feather-icon icon="SearchIcon" />
              </b-button>
            </b-input-group-prepend>
            <b-form-input
              v-model="searchTerm"
              placeholder="Search"
              type="text"
              class="d-inline-block"
              @keyup="searchTimeOut()"
            />

            <b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                v-b-modal.modal-advancesearch
                variant="outline-primary"
              >
                Advance search
              </b-button> </b-input-group-append
            ><b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary"
                v-bind:class="{ active: Day == 'yesterday' }"
                @click="
                  Day = 'yesterday';
                  getDocuments();
                "
              >
                Yesterday
              </b-button>
            </b-input-group-append>
            <b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary"
                v-bind:class="{ active: Day == 'today' }"
                @click="
                  Day = 'today';
                  getDocuments();
                "
              >
                Today
              </b-button>
            </b-input-group-append>
            <b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary"
                v-bind:class="{ active: Day == '' }"
                @click="
                  Day = '';
                  getDocuments();
                "
              >
                All
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </div>
      </b-form-group>
      <b-form-group v-if="$ability.can('add/copy', 'bank_account')" >
        <div class="d-flex align-items-center">
          <b-input-group>
            <b-input-group-prepend>

              <div id="my-container">

                <b-button
                  id="popover-reactive-1"
                  ref="button"
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  title="Export"
                  variant="outline-primary"
                >
                  <feather-icon icon="DownloadIcon" />
                </b-button>

                <b-popover
                  ref="popover"
                  target="popover-reactive-1"
                  triggers="click"
                  :show.sync="popoverShow"
                  placement="auto"
                  container="my-container"
                >
                  <template #title>
                    <div class="d-flex justify-content-between align-items-center">
                      <span>Export Table</span>
                    </div>
                  </template>

                  <div>
                    <b-form-group
                      label="Search Filter"
                    >
                      <b-form-input
                        v-model="searchTerm"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <b-form-group
                      label="Records Limit"
                    >
                      <b-form-input
                        v-model="exportLimit"
                        type="text"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <!-- button -->
                    <b-row>
                      <b-col cols="6">
                        <b-button
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          size="sm"
                          variant="danger"
                          class="mr-1"
                          @click="onClose"
                        >
                          <feather-icon icon="XIcon" />
                        </b-button>
                      </b-col>

                      <b-col cols="6">
                        <b-button
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          size="sm"
                          variant="primary"
                          :href="'/api/bank_accounts_transactions_export_excel?user_id='+bankAccountId+'&amp;searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
                          target="_blank"
                        >
                          <feather-icon icon="DownloadIcon" />
                        </b-button>
                      </b-col>
                    </b-row>
                  </div>
                </b-popover>

              </div>

            </b-input-group-prepend>
            <b-input-group-prepend>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                :to="{
                  name: 'create-new-bank-account-transaction',
                  params: { id: bankAccountId },
                }"
                title="Add Bank Account Transaction"
              >
                ADD+
              </b-button>
            </b-input-group-prepend>
          </b-input-group>
        </div>
      </b-form-group>
    </div>

    <!-- Advance Search Model -->
    <b-modal
      hide-footer
      id="modal-advancesearch"
      title="Advance Search"
      ref="searchModel"
      size="lg"
      cancel-variant="outline-secondary"
    >
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
                @input="getDocuments()"
              />
            </b-form-group>
          </b-col>
          <!-- <b-col md="6">
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
      </b-col> -->

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
          <b-col md="6">
            <b-form-group label="Notes" label-for="v-notes">
              <b-form-input
                id="v-notes'"
                v-model="formValues.notes"
              />
            </b-form-group>
          </b-col>
          <b-col md="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="
                (page = 1), (searchTerm = ''), closeSearch(), getDocuments()
              "
            >
              <span>GO</span>
            </b-button>

            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="outline-primary"
              class="mr-1"
              @click="resetSearch"
            >
              <span>Reset</span>
            </b-button>

            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="outline-secondary"
              class="mr-1"
              @click="closeSearch"
            >
              <span>Cancel</span>
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>

    <div class="container">
      <b-row>
        <b-col md="3" class="border border-primary p-1 m-1  text-white rounded " style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));" >
          <strong>Bank Name : </strong>
          {{ wallet__data_values.bank_name }}
        </b-col>

        <b-col md="3" class="border border-primary p-1 m-1  text-white rounded " style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Account Name : </strong>
          {{ wallet__data_values.account_name }}
        </b-col>
        <b-col md="3" class="border border-primary p-1 m-1  text-white rounded " style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Balance : </strong>
          {{ thousands_seperator(wallet__data_values.balance) }}
        </b-col>
        <b-col md="2" class="border border-primary p-1 m-1  text-white rounded " style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>IBAN : </strong>
          {{ wallet__data_values.iban }}
        </b-col>
      </b-row>
    </div>

    <b-table
      :items="items"
      :fields="fields"
      responsive
      class="mb-0 bg-white"
      :busy="isBusy"
    >
      <template #cell(Options)="row">
           <div>
            <div class="d-flex align-items-center w-fit-content">
              <b-input-group>
                <b-input-group-prepend>
                  <b-button
                    size="sm"
                    v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                    variant="outline-primary"
                    v-bind:class="row.detailsShowing ? 'active' : ''"
                    @click="row.toggleDetails"
                  >
                    <feather-icon
                      icon="ChevronUpIcon"
                      v-if="row.detailsShowing"
                    />

                    <feather-icon icon="ChevronDownIcon" v-else />
                  </b-button>
                </b-input-group-prepend>

                <b-input-group-append v-if="$ability.can('edit', 'bank_account')">
                  <b-button
                    size="sm"
                    :to="{
                name: 'edit-bank-account-transaction',
                params: { id: row.item.id } }"
                    v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                    variant="outline-primary text-success"
                  >
                    <feather-icon icon="EditIcon" />
                  </b-button>
                </b-input-group-append>
                <b-input-group-append
                  v-if="$ability.can('delete', 'bank_account')"
                >
                  <b-button
                    size="sm"
                    v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                    variant="outline-primary text-danger"
                    @click="deleteDocument(row.item.id)"
                  >
                    <feather-icon icon="Trash2Icon" />
                  </b-button>
                </b-input-group-append>
              </b-input-group>
            </div>
          </div>
      </template>

      <template #cell(transaction_type)="data">
        <b-badge pill :variant="transaction_typeVariant(data.value)">
          {{ data.value }}
        </b-badge>
      </template>
      <template #cell(sub_type)="data">
        <span v-if="data.item.transaction_type == 'Transfer to Other Bank'">{{ data.item.to_from_name }}</span>
        <span v-else>{{ data.value }}</span>
            
      </template>
      <template #cell(amount)="data">
        <span :class="data.item.credit_debit == 'Debit' ? 'text-danger' : ''" >
          {{ thousands_seperator(data.value) }}
        </span>
          
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card no-body>
          <b-row>
            <b-col cols="12" class="m-0 p-2">
              <b-card-text>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Bank Account ID</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.bank_account_id }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Notes</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.notes }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom" v-if="row.item.to_from">
                  <b-col cols="2">
                    <strong>To/From ID</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.to_from }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom" v-if="row.item.to_from_name">
                  <b-col cols="2">
                    <strong>To/From Name</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.to_from_name }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom"  v-if="row.item.credit_debit">
                  <b-col cols="2">
                    <strong>Credit/Debit</strong>
                  </b-col>
                  <b-col cols="10">
                    <!-- {{ row.item.credit_debit }} -->
                    <b-badge
                      pill
                      :variant="creditDebitVariant(row.item.credit_debit)"
                    >
                      {{ row.item.credit_debit }}
                    </b-badge>
                  </b-col>
                </b-row>

                <b-row class="border-bottom">
                  <b-col cols="12" class="text-right">
                    <strong>Created By : </strong>
                    {{ row.item.created_by }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom" v-if="row.item.updated_by">
                  <b-col cols="12" class="text-right">
                    <strong>Updated By : </strong>
                    {{ row.item.updated_by }}
                  </b-col>
                </b-row>
              </b-card-text>
            </b-col>
          </b-row>
        </b-card>
      </template>
        <template #cell(date)="data">

        {{ data.value | moment("DD/MM/YYYY") }}
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle" />
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>

    <div class="d-flex justify-content-end">
      <b-pagination
        v-model="page"
        :total-rows="count"
        :per-page="pageSize"
        pills
        @change="handlePageChange"
      />
    </div>
  </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import axios from "@axios";
import vSelect from "vue-select";
import {
  BTable,
  BFormCheckbox,
  BButton,
  BCard,
  BRow,
  BCol,
  BAvatar,
  BBadge,
  BImg,
  BTabs,
  BTab,
  BCardText,
  BPagination,
  BFormGroup,
  BFormInput,
  BFormSelect,
  BDropdown,
  BDropdownItem,
  BInputGroup,
  BInputGroupAppend,
  BInputGroupPrepend,
  BFormDatepicker,
  BFormFile,
  BFormTextarea,
  BFormRadio,
  BForm,
  BFormCheckboxGroup,
  BFormRating,
  BListGroupItem,
  BListGroup,
  BPopover,
  BSpinner,
} from "bootstrap-vue";
import Cleave from "vue-cleave-component";
import "cleave.js/dist/addons/cleave-phone.us";

import Ripple from "vue-ripple-directive";
import EnlargeableImage from "@diracleo/vue-enlargeable-image";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BCardCode,
    BTable,
    BButton,
    BFormCheckbox,
    BCard,
    BRow,
    BCol,
    BBadge,
    BAvatar,
    BImg,
    EnlargeableImage,
    BTabs,
    BTab,
    BCardText,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BInputGroup,
    BInputGroupAppend,
    BInputGroupPrepend,
    BFormDatepicker,
    BFormFile,
    BFormTextarea,
    BFormRadio,
    BForm,
    vSelect,
    BFormCheckboxGroup,
    BFormRating,
    BListGroup,
    BListGroupItem,
    BPopover,
    BSpinner,
    Cleave,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      Day: "",
      popoverShow: false,
      base_url: "",
      bankAccountId: null,
      wallet__data_values: {},

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

      formValues: {
        transaction_type: "",
        date: "",
        notes: "",
      },
      options: {
        date: {
          date: true,
          delimiter: "",
          datePattern: ["Y", "m", "d"],
        },
      },
      exportLimit: "",
      isBusy: true,
      fields: [
        { key: "Options", thClass: "customHead" },
        {
          key: "id",
          label: "ID",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "transaction_type",
          label: "Transaction Type",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "amount",
          label: "Amount",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "sub_type",
          label: "Sub Type",
          sortable: true,
          thClass: "customHead",
        },
        { key: "date", label: "Date", sortable: true, thClass: "customHead" },
        //  {  key: 'created_by', label: 'Created By', sortable: true, thClass: 'customHead' },
      ],
      items: [],
      /* eslint-disable global-require */

      searchTerm: "",
      pageSize: 10,
      page: 1,
      count: 0,
    };
  },

  mounted() {
    this.bankAccountId = this.$route.params.id;
    this.getBankAccount();
    this.getDocuments();
  },
  methods: {
    onClose() {
      this.popoverShow = false;
    },
    thousands_seperator(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },
    deleteDocument(id) {
      console.log(id);
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          axios
            .post("/deleteBankAccountTransaction", {
              id: id,
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getBankAccount();
                  this.getDocuments();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Bank Account Transaction Deleted Successfully",
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Bank Account Transaction Deleted Successfully");
                } else {
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Error : "+response.data.message,
                      icon: "AlertCircleIcon",
                      variant: "danger",
                      text: "Something went wrong",
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
        }
      });
    },

    getDocuments() {
      // return ;
      this.isBusy = true;
      this.getBankAccount();
      // getAllDocuments
      axios
        .get("/getAllBankTransactions", {
          params: {
            id: this.$route.params.id,
            day: this.Day,
            searchTerm: this.searchTerm,
            page_no: this.page,
            advanceSearch: this.formValues,
          },
        })

        .then((response) => {
          console.log("response", response.data.data);
          this.items = response.data.data;
          this.base_url = response.data.base_url;
          this.count = response.data.totalRows;
          this.isBusy = false;

          // TODO
        })
        .catch((error) => {
          console.error(error);
        });
    },

    transaction_typeVariant(ownerType) {
      if (ownerType == "Client Payment") {
        return "warning";
      } else if (ownerType == "Employee Payments") {
        return "success";
      } else if (ownerType == "HR Payments") {
        return "primary";
      } else if (ownerType == "Supplier Payment") {
        return "info";
      } else if (ownerType == "Transfer to Owner") {
        return "secondary";
      } else {
        return "primary";
      }
    },

    getBankAccount() {
      console.log(this.$route.params.id);

      axios
        .get("/getBankAccount", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.wallet__data_values = response.data.data;
              this.initTrHeight();
              console.log("Wallet Fetched");
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

    handlePageChange(value) {
      this.page = value;
      this.isBusy = true;
      this.getDocuments();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 1000ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getDocuments();
      }, 1000);
    },
    creditDebitVariant(ownerType) {
      if (ownerType == "Debit") {
        return "danger";
      } else if (ownerType == "Credit") {
        return "success";
      } else {
        return "primary";
      }
    },
    closeSearch() {
      this.$refs["searchModel"].hide();
    },
    resetSearch() {
      console.log(this.formValues);

      Object.entries(this.formValues).forEach(([key, value]) => {
        {
          this.formValues[key] = "";
        }
      });

      console.log(this.formValues);
    },
  },
};
</script>

<style lang="css" >
.enlargeable-image .full {
  z-index: 9999 !important;

  background-color: rgba(0, 0, 0, 0.5) !important;
}

.customHead {
  background-color: #06608f !important;
  color: #fff;
}
</style>
<style lang="scss">
@import "@core/scss/vue/libs/vue-autosuggest.scss";
</style>
