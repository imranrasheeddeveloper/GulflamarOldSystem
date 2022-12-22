<template>
  <div>
    <!-- search input -->
    <div class="custom-search d-flex justify-content-between">
      <b-form-group>
        <div class="d-flex align-items-center">

          <b-input-group>
            <b-input-group-prepend>
              <b-button
                variant="outline-primary"
                @click="(page = 1), getSupplierTypes()"
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
              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-modal.modal-advancesearch variant="outline-primary" 
                 :to="{ name: 'expense-category-detail', params: { id: this.$route.params.id } }"
                 >
                  Show All Transactions
              </b-button>
            </b-input-group-append>
          </b-input-group>
          
        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'expense_accounts')" >
        <div class="d-flex align-items-center">
          <b-input-group>
            <b-input-group-prepend>
                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" :to="{ name: 'add-expense-entry-view' ,params: { id: walletId } }" title="Add Expense">
                            ADD+
                        </b-button>
                    </b-input-group-prepend>
          </b-input-group>
        </div>
      </b-form-group>
    </div>
    <div class="container">
      <b-row class="">
        <b-col md="2" class="border border-primary text-white p-1 m-1 rounded" style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Account ID : </strong>
          {{ wallet__data_values.id }}
        </b-col>

        <b-col md="3" class="border border-primary text-white p-1 m-1 rounded" style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Expense Account Name : </strong>
          {{ wallet__data_values.account_name }}
        </b-col>
        <b-col md="2" class="border border-primary text-white p-1 m-1 rounded" style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Period : </strong>
          {{ wallet__data_values.period | moment("MMMM, YYYY") }}
        </b-col>
        <b-col md="3" class="border border-primary text-white p-1 m-1 rounded" style="background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));">
          <strong>Total Expense : </strong>
          {{ wallet__data_values.total }}
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
          <div class="d-flex justify-content-start">
            <b-input-group>
            <b-input-group-prepend>
            <b-button
              v-if="$ability.can('view', 'expense_accounts')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-primary"
              class="btn-icon rounded-circle"
              title="Category Detail View"
              :to="{ name: 'expense-category-detail', params: { id: row.item.account_id,category: row.item.category } }"

            >
              <feather-icon icon="SearchIcon" />
            </b-button>
            </b-input-group-prepend>
            </b-input-group>
            <!-- :to="{ name: 'expense-account-summary', params: { id: row.item.id }}" -->
          </div>
        </div>
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
  BModal,
  BSpinner,
} from "bootstrap-vue";

import Ripple from "vue-ripple-directive";
import EnlargeableImage from "@diracleo/vue-enlargeable-image";
import pdf from "vue-pdf";

import VuePdfApp from "vue-pdf-app";

import "vue-pdf-app/dist/icons/main.css";

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
    BModal,
    pdf,
    VuePdfApp,
    BSpinner,
  },
  directives: {
    Ripple,
  },

  errorCaptured() {
    return false;
  },

  data() {
    return {
      isBusy: true,
      fields: [
        { key: "Options", thClass: "customHead" },
        {
          key: "category",
          label: "Category",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "amount",
          label: "Amount",
          sortable: true,
          thClass: "customHead",
        },
      ],
      items: [],
      /* eslint-disable global-require */
      wallet__data_values: {},

      searchTerm: "",
      pageSize: 10,
      page: 1,
      count: 0,
      walletId: null,
      base_url: "",
    };
  },

  mounted() {
    this.walletId = this.$route.params.id;
    this.getWallet();
    this.getSupplierTypes();
  },
  methods: {
    getSupplierTypes() {
      this.isBusy = true;
      axios
        .get("/getExpenseAccountAllSummary", {
          params: {
            id: this.$route.params.id,
            searchTerm: this.searchTerm,
            page_no: this.page,
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

    getWallet() {
      console.log(this.$route.params.id);

      axios
        .get("/getExpenseAccount", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.wallet__data_values = response.data.data;

              console.log("tableData", this.wallet__data_values);

              // this.initTrHeight();

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
      this.getSupplierTypes();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getSupplierTypes();
      }, 800);
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
