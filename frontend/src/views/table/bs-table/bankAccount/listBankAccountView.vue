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
                @click="(page = 1), getBankAccounts()"
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
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </div>
      </b-form-group>
      
      <b-form-group v-if="$ability.can('add/copy', 'bank_account')">
        <div class="d-flex align-items-center" >
          <b-input-group>
            <b-input-group-prepend>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                :to="{ name: 'create-new-bank-account' }"
                title="Add Bank Account"
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
          <!-- <b-col md="6">
        <b-form-group label="User" label-for="v-user">
          <b-form-input id="v-user" v-model="formValues.user" />
        </b-form-group>
      </b-col> -->

          <b-col md="6">
            <b-form-group label="Account Name" label-for="v-account_name">
              <b-form-input
                id="v-account_name'"
                v-model="formValues.account_name"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Bank Name" label-for="v-bank_name">
              <b-form-input id="v-bank_name'" v-model="formValues.bank_name" />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="IBAN" label-for="v-iban">
              <b-form-input id="v-iban'" v-model="formValues.iban" />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Balance" label-for="balance">
              <b-form-input
                id="balance"
                v-model.number="formValues.balance"
                type="number"
                placeholder="0.00"
                @scroll.prevent
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
                (page = 1), (searchTerm = ''), closeSearch(), getBankAccounts()
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

    <b-table
      :items="items"
      :fields="fields"
      responsive
      class="mb-0 bg-white"
      :busy="isBusy"
    >
      <template #cell(Options)="row">
        <div>
        <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
        <b-form-group>
          <div class="d-flex align-items-center w-fit-content">
            <b-input-group>
              <b-input-group-prepend v-if="$ability.can('edit', 'bank_account')">
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                  variant="outline-primary text-success"
                  :to="{name: 'edit-bank-account', params: { id: row.item.id } }"
                  
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-prepend>
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
              <b-input-group-append>
              <b-button v-if="$ability.can('view', 'bank_account')" 
                  size="sm"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'list-bank-account-transactions', params: { id: row.item.id }}"
              variant="outline-primary"
            >
              <feather-icon icon="SearchIcon" />
            </b-button>
              </b-input-group-append>

            </b-input-group>
          </div>
        </b-form-group>  
        </div>


      </template>
            <template #cell(balance)="data">
          {{ thousands_seperator(data.value) }}
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
      popoverShow: false,
      base_url: "",

      formValues: {
        balance: null,
        account_name: null,
        bank_name: null,
        iban: null,
      },
      options: {
        date: {
          date: true,
          delimiter: "-",
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
          key: "account_name",
          label: "Account Name",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "bank_name",
          label: "Bank Name",
          sortable: true,
          thClass: "customHead",
        },
        { key: "iban", label: "IBAN", sortable: true, thClass: "customHead" },

        {
          key: "balance",
          label: "Balance",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "created_by",
          label: "Created By",
          sortable: true,
          thClass: "customHead",
        },
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
    this.getBankAccounts();
  },
  methods: {
    onClose() {
      this.popoverShow = false;
    },
    thousands_seperator(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },

    deleteDocument(id) {
      // return ;
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
            .post("/deleteBankAccount", {
              id: id,
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getBankAccounts();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Bank Account Deleted Successfully",
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Bank Account Deleted Successfully");
                } else {
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Error " + response.data.message,
                      icon: "AlertCircleIcon",
                      variant: "danger",
                      // text: 'Something went wrong, try again later',
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

    getBankAccounts() {
      this.isBusy = true;

      axios
        .get("/getAllBankAccounts", {
          params: {
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
    statusVariant(ownerType) {
      if (ownerType == "Accommodation") {
        return "warning";
      } else if (ownerType == "Client") {
        return "success";
      } else if (ownerType == "Employee") {
        return "primary";
      } else if (ownerType == "Supplier") {
        return "info";
      } else if (ownerType == "Vendor") {
        return "secondary";
      } else {
        return "primary";
      }
    },

    handlePageChange(value) {
      this.page = value;
      this.isBusy = true;
      this.getBankAccounts();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 1000ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getBankAccounts();
      }, 1000);
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
