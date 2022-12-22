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
                @click="(page = 1), getWallets()"
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

      <b-form-group v-if="$ability.can('add/copy', 'my_wallets')">
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
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <span>Export Table</span>
                    </div>
                  </template>

                  <div>
                    <b-form-group label="Search Filter">
                      <b-form-input
                        v-model="searchTerm"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <b-form-group label="Records Limit">
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
                          :href="
                            '/api/my_walletCsv?user_id=' +
                            user_id +
                            '&amp;searchTerm=' +
                            searchTerm +
                            ' &amp;limit=' +
                            exportLimit
                          "
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
                :to="{ name: 'create-my-wallet' }"
                title="Add My Wallet"
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
        <b-form-group
          label="User"
          label-for="v-user-dropdown"
        >
          <v-select
            id="v-user-dropdown"
            v-model="formValues.user_id"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="userList"
            label="name"
            :reduce="name => name.id"
            


          />
        </b-form-group>
      </b-col> -->
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

          <b-col md="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="
                (page = 1), (searchTerm = ''), closeSearch(), getWallets()
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
        <!-- <div>

          <div class="d-flex justify-content-start">
              

            <b-button v-if="$ability.can('edit', 'my_wallets')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'edit-my_wallet', params: { id: row.item.id }}"
              variant="flat-success"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="EditIcon" />
            </b-button>


              

            <b-button v-if="$ability.can('delete', 'my_wallets')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-danger"
              class="btn-icon rounded-circle"
              @click="deleteDocument(row.item.id)"
            >
              <feather-icon icon="Trash2Icon" />
            </b-button>
            <b-button  
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'my-list-patty-cash', params: { id: row.item.id }}"
              variant="flat-primary"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="InfoIcon" />
            </b-button>
          </div>
        </div> -->

        <b-form-group>
          <div class="d-flex align-items-center w-fit-content">
            <b-input-group>
              <b-input-group-prepend>
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                  variant="outline-primary"
                  :to="{
                    name: 'my-list-patty-cash',
                    params: { id: row.item.id },
                  }"
                >
                  <feather-icon icon="SearchIcon" />
                </b-button>
              </b-input-group-prepend>

              <b-input-group-append v-if="$ability.can('edit', 'my_wallets')">
                <b-button
                  size="sm"
                  :to="{ name: 'edit-my_wallet', params: { id: row.item.id } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append v-if="$ability.can('delete', 'my_wallets')">
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
        </b-form-group>
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
      user_id: null,
      popoverShow: false,
      base_url: "",

      formValues: {
        date: "",
        balance: null,
        user_id: null,
        account_name: null,
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
          key: "user_name",
          label: "User Name",
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
          key: "balance",
          label: "Balance",
          sortable: true,
          thClass: "customHead",
        },
        { key: "date", label: "Date", sortable: true, thClass: "customHead" },
        {
          key: "created_by",
          label: "Created By",
          sortable: true,
          thClass: "customHead",
        },
      ],
      items: [],
      userList: [],
      /* eslint-disable global-require */

      searchTerm: "",
      pageSize: 10,
      page: 1,
      count: 0,
    };
  },

  mounted() {
    this.getWallets();
    // this.getUsers();
  },
  methods: {
    onClose() {
      this.popoverShow = false;
    },

    // getUsers() {
    //   this.isBusy = true
    //   axios.get('/getAllSystemUsers', {
    //   })

    //     .then(response => {
    //       console.log('response', response.data.data)
    //       this.userList = response.data.data
    //       this.isBusy = false

    //       // TODO
    //     }).catch(error => {
    //       console.error(error)
    //     })
    // },

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
            .post("/deleteMyWallet", {
              id: id,
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getWallets();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Wallet Deleted Successfully",
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Wallet Deleted Successfully");
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

    // isPdf(url) {
    //   if(url == null || url=='') {
    //     return false
    //   }
    //   if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
    //     return true
    //   }

    //   return false
    // },

    getWallets() {
      this.isBusy = true;

      axios
        .get("/getAllMyWallets", {
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
          this.user_id = response.data.user_id;

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
      this.getWallets();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 1000ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getWallets();
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
