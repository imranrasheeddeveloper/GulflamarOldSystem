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
                @click="(page = 1), getEmployees()"
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

      <b-form-group v-if="$ability.can('add/copy', 'resource')">
        <div class="d-flex align-items-center">
          <b-input-group>
            <b-input-group-prepend>
              <div id="my-container">
                <b-button
                  id="popover-reactive-1"
                  ref="button"
                  title="Export"
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
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
                            '/api/resourceCsv?searchTerm=' +
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
                :to="{ name: 'allocate-resource' }"
                title="Allocate Resource"
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
            <b-form-group label="Resource Type" label-for="v-resourceType">
              <v-select
                id="v-resourceType"
                v-model="formValues.resourceType"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="resourceTypes"
                label="text"
                :reduce="(text) => text.value"
                @input="resourceTypeChange"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Resource Owner" label-for="autosuggest__input">
              <vue-autosuggest
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

          <b-col md="6">
            <label for="v-dob">Date</label>
            <b-input-group>
              <cleave
                id="date"
                v-model="formValues.allocationDate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.allocationDate"
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
            <b-form-group label="Notes" label-for="notes">
              <b-form-textarea
                id="notes"
                v-model="formValues.notes"
                placeholder="Notes"
                rows="1"
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
                (page = 1), (searchTerm = ''), closeSearch(), getEmployees()
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
                <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails">
                    {{ row.detailsShowing ? 'Hide' : 'View Details' }}
                </b-form-checkbox>
                <div class="d-flex justify-content-start">
                    <b-button v-if="$ability.can('edit', 'resource')" :to="{ name: 'allocated-resource-edit', params: { id: row.item.id }}" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="flat-success" class="btn-icon rounded-circle">
                        <feather-icon icon="EditIcon" />
                    </b-button>

                    <b-button v-if="$ability.can('delete', 'resource')" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="flat-danger" class="btn-icon rounded-circle" @click="deleteAllocation(row.item.id)">
                        <feather-icon icon="Trash2Icon" />
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

              <b-input-group-append v-if="$ability.can('edit', 'resource')">
                <b-button
                  size="sm"
                  :to="{
                    name: 'allocated-resource-edit',
                    params: { id: row.item.id },
                  }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append v-if="$ability.can('delete', 'resource')">
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

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card no-body class="pb-0 mb-0">
          <div>
            <b-table
              :items="row.item.resourceItems"
              :fields="itemFields"
              responsive
              class="mb-0 bg-white"
              :busy="isBusy"
              show-empty
            >
              <template #empty="scope">
                <h4>{{ scope.emptyText }}</h4>
              </template>
            </b-table>
          </div>
        </b-card>
        <b-card no-body>
          <b-row class="">
            <b-col cols="12" class="text-right">
              <strong>Created By : </strong>
              {{ row.item.created_by }}
            </b-col>
          </b-row>
          <b-row class="" v-if="row.item.updated_by">
            <b-col cols="12" class="text-right">
              <strong>Updated By : </strong>
              {{ row.item.updated_by }}
            </b-col>
          </b-row>
        </b-card>
      </template>

      <!-- <template #cell(iqama)="data">
          <b-avatar :src="data.value" />
        </template> -->

      <template #cell(ownerType)="data">
        <b-badge pill :variant="statusVariant(data.value)">
          {{ data.value }}
        </b-badge>
      </template>

      <template #cell(signature)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            v-if="data.value != ''"
            style="max-height: 80px"
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #cell(attachment)="data">
        <a
          class="btn btn-primary"
          v-if="isPdf(data.value)"
          :href="data.value"
          target="_blank"
          >View file</a
        >

        <enlargeable-image
          v-else
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            v-if="data.value != ''"
            style="max-height: 80px"
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #cell(date)="data">
        {{ data.value | moment("DD/MM/YYYY") }}
      </template>

      <template #cell(passport)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(passport_2)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(ajeer)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(insurance_card)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>

      <!-- <template #cell(status)="data">
          <b-badge :variant="status[1][data.value]">
            {{ status[0][data.value] }}
          </b-badge>
        </template> -->
    </b-table>

    <div class="d-flex justify-content-end">
      <b-pagination
        v-model="page"
        :total-rows="count"
        :per-page="pageSize"
        pills
        @change="handlePageChange"
      ></b-pagination>
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

import Ripple from "vue-ripple-directive";
import EnlargeableImage from "@diracleo/vue-enlargeable-image";
import Cleave from "vue-cleave-component";
import "cleave.js/dist/addons/cleave-phone.us";
import { VueAutosuggest } from "vue-autosuggest";

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
    VueAutosuggest,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      options: {
        penColor: "#000",
        backgroundColor: "#fff",
        phone: {
          prefix: "9665",
          blocks: [12],
          uppercase: true,
          noImmediatePrefix: true,
        },
        date: {
          date: true,
          delimiter: "-",
          datePattern: ["Y", "m", "d"],
        },
        account_number: {
          blocks: [15],
          uppercase: true,
        },
        iqama_number: {
          blocks: [10],
          uppercase: true,
        },
        cr_number: {
          blocks: [10],
          uppercase: true,
        },
        iban: {
          prefix: "SA",
          blocks: [24],
          uppercase: true,
          noImmediatePrefix: true,
        },
      },

      popoverShow: false,
      exportLimit: "",
      isBusy: true,
      fields: [
        {
          key: "Options",
          thClass: "customHead",
        },
        {
          key: "ownerType",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "resourceOwner",
          sortable: true,
          thClass: "customHead",
        },
        // {
        //     key: 'signature',
        //     sortable: true,
        //     thClass: 'customHead'
        // },
        {
          key: "attachment",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "notes",
          sortable: true,
          thClass: "customHead",
        },
        {
          key: "date",
          sortable: true,
          thClass: "customHead",
        },
        // {
        //     key: 'created_by',
        //     label: "Created By",
        //     sortable: true,
        //     thClass: 'customHead'
        // }, {
        //     key: 'updated_by',
        //     label: "Updated By",
        //     sortable: true,
        //     thClass: 'customHead'
        // }
      ],
      itemFields: ["name", "quantity"],
      items: [],
      emp_detail: [
        {
          age: 40,
          first_name: "Dickerson",
          last_name: "Macdonald",
        },
      ],
      /* eslint-disable global-require */
      status: [
        {
          1: "Current",
          2: "Professional",
          3: "Rejected",
          4: "Resigned",
          5: "Applied",
        },
        {
          1: "light-primary",
          2: "light-success",
          3: "light-danger",
          4: "light-warning",
          5: "light-info",
        },
      ],

      searchTerm: "",
      pageSize: 10,
      page: 1,
      count: 0,

      filteredOptions: [],

      formValues: {
        resourceType: "",
        resourceOwner: "",
        allocationDate: "",
        notes: "",
      },

      resourceTypes: [
        {
          value: "Employee",
          text: "Employee",
        },
        {
          value: "Project",
          text: "Project",
        },
        {
          value: "Accommodation",
          text: "Accommodation",
        },
      ],
    };
  },

  mounted() {
    this.getEmployees();
  },
  methods: {
    closeSearch() {
      this.$refs["searchModel"].hide();
    },

    assignOwnerField(item) {
      if (item) {
        this.formValues.resourceOwner = item.item.id;
      }
    },

    onInputChange(text) {
      if (text === "" || text === undefined) {
        return;
      }

      axios
        .get("/getEmployeeDropdown", {
          params: {
            id: text,
            resourceType: this.formValues.resourceType,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.filteredOptions = [
                {
                  data: response.data.data,
                },
              ];

              console.log("Resource Owners Fetched");
            } else {
              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: `Error`,
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
                title: `Error`,
                icon: "AlertCircleIcon",
                variant: "danger",
                text: `Something went wrong, try again later`,
              },
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },

    resourceTypeChange(val) {
      this.getResourceItems();
    },

    getResourceItems() {
      axios
        .get("/getResourceItemsDropdown", {
          params: {
            resourceType: this.formValues.resourceType,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              /*console.log(response.data.data);*/
              this.resourceItems = response.data.data;

              console.log("Resource Items Fetched");
            } else {
              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: `Error`,
                  icon: "AlertCircleIcon",
                  variant: "danger",
                  text: `Something went wrong, try again later`,
                },
              });
            }
          } else {
            this.$toast({
              component: ToastificationContent,
              position: "top-right",
              props: {
                title: `Error`,
                icon: "AlertCircleIcon",
                variant: "danger",
                text: `Something went wrong, try again later`,
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


    resetSearch() {
      Object.entries(this.formValues).forEach(([key, value]) => {
        if (key == "skills") {
          this.formValues[key] = [];
        } else if (
          key == "lang_eng" ||
          key == "lang_ar" ||
          key == "lang_hind" ||
          key == "appearance_presentable"
        ) {
          this.formValues[key] = null;
        } else {
          this.formValues[key] = "";
        }
      });

      console.log(this.formValues);
    },

    onClose() {
      this.popoverShow = false;
    },

    statusVariant(ownerType) {
      if (ownerType == "Accommodation") {
        return "warning";
      } else if (ownerType == "Project") {
        return "success";
      } else if (ownerType == "Employee") {
        return "primary";
      } else {
        return "light";
      }
    },

    isPdf(url) {
      if (url.substr(url.lastIndexOf(".") + 1) == "pdf") {
        return true;
      } else {
        return false;
      }
    },

    deleteAllocation(id) {
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
            .get("/deleteAllocation", {
              params: {
                id: id,
              },
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getEmployees();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: response.data.message,
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Employee deleted");
                } else {
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: `Error`,
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
                    title: `Error`,
                    icon: "AlertCircleIcon",
                    variant: "danger",
                    text: `Something went wrong, try again later`,
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

    getEmployees() {
      this.isBusy = true;

      axios
        .get("/getAllocatedResources", {
          params: {
            searchTerm: this.searchTerm,
            page_no: this.page,
            advanceSearch: this.formValues,
          },
        })

        .then((response) => {
          console.log("response", response.data.data);
          this.items = response.data.data;
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
      this.getEmployees();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getEmployees();
      }, 800);
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
</style>
