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
                @click="
                  page = 1;
                  Day = '';
                  getDocuments();
                "
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
                @click="Day = ''"
              >
                Advance search
              </b-button>
            </b-input-group-append>
            <b-input-group-append>
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
              <b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary"
                v-bind:class="{ active: Day == 'my_requests' }"
                @click="
                  Day = 'my_requests';
                  getDocuments();
                "
              >
                My Tickets
              </b-button>
            </b-input-group-append>
            
          </b-input-group>
        </div>
      </b-form-group>
      <!-- v-if="$ability.can('add/copy', 'petty_cash')" -->
      <b-form-group v-if="$ability.can('add/copy', 'call_center')">
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
                          :href="'/api/call_center_export_excel?user_id=' +
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
                :to="{ name: 'create-call' }"
                title="Create Call"
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
            <b-form-group label="GL ID" label-for="v-name">
              <b-form-input id="v-name'" v-model="formValues.name" />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <label for="v-from_date">From Date</label>
            <b-input-group>
              <cleave
                id="from_date"
                v-model="formValues.fromDate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.fromDate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-from_date"
                />
              </b-input-group-append>
            </b-input-group>
          </b-col>
          <b-col md="6">
            <label for="v-to_date">To Date</label>
            <b-input-group>
              <cleave
                id="to_date"
                v-model="formValues.toDate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.toDate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-to_date"
                />
              </b-input-group-append>
            </b-input-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Status" label-for="v-name">
              <v-select
                v-model="formValues.status"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="StatusType"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Priority" label-for="v-Priority">
              <v-select
                id="v-Priority"
                v-model="formValues.resolution"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="PriorityType"
                label="text"
                :reduce="(text) => text.value"
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

    <b-table
      :items="items"
      :fields="fields"
      responsive
      class="mb-0 bg-white"
      :busy="isBusy"
    >
      <template #cell(Options)="row">
        <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
        <b-form-group>
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

              <b-input-group-append v-if="$ability.can('edit', 'call_center')">
                <b-button
                  size="sm"
                  :to="{ name: 'edit-call', params: { id: row.item.id } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append
                v-if="$ability.can('delete', 'call_center')"
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
        </b-form-group>
      </template>

      <template #cell(status)="data">
        <v-select
          v-model="data.value"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
          :options="StatusType"
          label="text"
          :reduce="(text) => text.value"
          @input="Type_Change(data.item, $event)"
        />
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card no-body>
          <b-row>
            <b-col cols="12" class="m-0 p-2">
              <b-card-text>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Department Name</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.department_name }}
                  </b-col>
                </b-row>

                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Department Id</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.department }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Issue</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.issue }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Project</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.project }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Date</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.date }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Time</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.time }}
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Priority</strong>
                  </b-col>
                  <b-col cols="10">
                    <b-badge pill :variant="statusVariant(row.item.resolution)">
                      {{ row.item.resolution }}
                    </b-badge>
                  </b-col>
                </b-row>
                <b-row class="border-bottom">
                  <b-col cols="2">
                    <strong>Issue Resolution</strong>
                  </b-col>
                  <b-col cols="10">
                    {{ row.item.issue_resolution }}
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
import { VueAutosuggest } from "vue-autosuggest";

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
    VueAutosuggest,
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
      isSubmitting: false,

      filteredOptions: [],

      formValues: {
        name: "",
        fromDate: "",
        toDate: "",
        status: "",
        resolution: "",
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
      StatusType: [
        { value: "New", text: "New" },
        { value: "Under Process", text: "Under Process" },
        { value: "Respond To Employee", text: "Respond To Employee" },
        { value: "Refer To Vendor", text: "Refer To Vendor" },
        { value: "Awaiting Vendor Response", text: "Awaiting Vendor Response" },
        { value: "Resolved", text: "Resolved" },
      ],
      PriorityType: [
        { value: "Low", text: "Low" },
        { value: "Medium", text: "Medium" },
        { value: "High", text: "High" },
      ],
      fields: [
        {
          key: "Options",
          thClass: "customHead",
        },
        {
          key: "id",
          label: "ID",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "name",
          label: "Name , GL ID",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "department_name",
          label: "Department Name",
          sortable: true,
          thClass: "customHead",
        },
        // {
        //   key: "project",
        //   label: "project",
        //   sortable: true,
        //   thClass: "customHead",
        // },

        // {
        //   key: "date",
        //   label: "Date",
        //   sortable: true,
        //   thClass: "customHead",
        // },
        // {
        //   key: "time",
        //   label: "time",
        //   sortable: true,
        //   thClass: "customHead",
        // },
        // {
        //   key: "issue",
        //   label: "issue",
        //   sortable: true,
        //   thClass: "customHead",
        // },
        {
          key: "status",
          label: "status",
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
    this.getDocuments();
  },
  methods: {
    onClose() {
      this.popoverShow = false;
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
            .post("/deleteCallCenter", {
              id: id,
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getDocuments();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Transaction Deleted Successfully",
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Transaction Deleted Successfully");
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
            });
        }
      });
    },

    Type_Change(item, event) {
      item.status = event;
      console.log("item id", item.id);
      console.log("status", event);
      let id = item.id;
      let status = event;

      console.log(id);
      this.isSubmitting = true;

      this.$toast({
        component: ToastificationContent,
        position: "top-right",
        props: {
          title: "wait a second",
          icon: "InfoIcon",
          variant: "primary",
        },
      });

      axios
        .post("/updateCallCenterStatus", {
          id: id,
          status: status,
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              // this.getDocuments();

              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: "Status updated Successfully",
                  icon: "EditIcon",
                  variant: "success",
                },
              });
              console.log("Status Updated Successfully");
              this.isSubmitting = false;
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
              this.isSubmitting = false;
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

    // isPdf(url) {
    //   if(url == null || url=='') {
    //     return false
    //   }
    //   if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
    //     return true
    //   }

    //   return false
    // },

    getDocuments() {
      this.isBusy = true;

      axios
        .get("/getAllCallCenterData", {
          params: {
            id: 1,
            // id: this.$route.params.id,

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
    statusVariant(ownerType) {
      if (ownerType == "Low") {
        return "primary";
      } else if (ownerType == "Medium") {
        return "warning";
      } else if (ownerType == "High") {
        return "danger";
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

<style lang="css">
.enlargeable-image .full {
  z-index: 9999 !important;

  background-color: rgba(0, 0, 0, 0.5) !important;
}

.customHead {
  background-color: #06608f !important;
  color: #fff;
}
</style><style>
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
</style><style lang="scss">
@import "@core/scss/vue/libs/vue-autosuggest.scss";
</style>
