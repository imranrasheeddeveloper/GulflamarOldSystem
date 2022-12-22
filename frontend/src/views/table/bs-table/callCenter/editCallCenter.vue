<template>
  <b-form @submit.prevent>
    <b-row>

        <b-col md="6">

        <b-form-group
          label="Name"
                    label-for="autosuggest__input"

        >
          <vue-autosuggest
            v-model="formValues.name"

            :suggestions="filteredOptions"

            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID',}"
            @input="onInputChange(formValues.name)"
            @selected="assignOwnerField($event)"
          >
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }} </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Project" label-for="v-Project">
          <b-form-input id="v-Project'" v-model="formValues.project" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <label for="v-date">Date</label>
        <b-input-group>
          <cleave
            id="v-date"
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
              aria-controls="v-date"
            />
          </b-input-group-append>
        </b-input-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Time" label-for="v-time">
          <!-- <b-form-input id="v-time'" v-model="formValues.time" /> -->
          <b-form-timepicker v-model="formValues.time" locale="en"></b-form-timepicker>

        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group
          label="Department"
          label-for="v-Department"
        >
          <v-select
            id="v-Department"
            v-model="formValues.department"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="userList"
            label="name"
            :reduce="name => name.id"
        
            @input="Type_Change($event)"
          />

        </b-form-group>
      </b-col>

      <!-- <b-col md="6">
        <b-form-group label="Department" label-for="autosuggest__input">
          <vue-autosuggest
            v-model="formValues.department_name"
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{
              id: 'autosuggest__input',
              class: 'form-control',
              placeholder: 'Search with name or ID',
            }"
            @input="onInputChange($event)"
            @selected="assignOwnerField($event)"
          >
            <template slot-scope="{ suggestion }">
              <span class="my-suggestion-item"
                >{{ suggestion.item.name }}, {{ suggestion.item.id }}
              </span>
            </template>
          </vue-autosuggest>
        </b-form-group>
      </b-col> -->
      <b-col md="6">
        <b-form-group label="Status" label-for="v-Status">
          <!-- <b-form-input id="v-Status'" v-model="formValues.status" /> -->
          <v-select
            id="v-Status"
            v-model="formValues.status"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="StatusType"
            label="text"
            :reduce="(text) => text.value"
        
          />

        </b-form-group>
      </b-col>
      <!-- <b-col md="6">
        <b-form-group label="Resolution" label-for="v-Resolution">
          <b-form-input id="v-Resolution'" v-model="formValues.resolution" />
        </b-form-group>
      </b-col> -->
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
       <b-col md="6">
        <b-form-group label="Contact Detail" label-for="v-contact_detail">
          <b-form-input id="v-Project'" v-model="formValues.contact_detail" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Method Of Contact" label-for="v-method_of_contact">
          <v-select
          id="v-method_of_contact"
            v-model="formValues.method_of_contact"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="MethodOfContact"
            label="text"
            :reduce="(text) => text.value"
          />
        </b-form-group>
      </b-col>
      <b-col md="12">
        <b-form-group label="Issue" label-for="Issue">
          <b-form-textarea
            id="Issue"
            v-model="formValues.issue"
            placeholder="Write Issue Here.."
            rows="3"
          />
        </b-form-group>
      </b-col>
      <b-col md="12">
        <b-form-group label="Issue Resolution" label-for="IssueResolution">
          <b-form-textarea
            id="IssueResolution"
            v-model="formValues.issue_resolution"
            placeholder="Write Issue Resolution Here.."
            rows="3"
          />
        </b-form-group>
      </b-col>
      <b-col md="12">
        <b-form-group label="Notes" label-for="notes">
          <b-form-textarea
            id="notes"
            v-model="formValues.notes"
            placeholder="Write Notes Here.."
            rows="3"
          />
        </b-form-group>
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
  BFormTimepicker,
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
    BFormTimepicker,

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

      formValues: {
        id:null,
        name: null,
        name_id:null,
        project:null,
        department: null,
        date: null,
        method_of_contact:null,
        time:null,
        contact_detail:null,
        status:null,
        resolution:null,
        issue:null,
        issue_resolution:null,
        notes:null,
      },
      filteredOptions: [],

      userList: [],


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
      MethodOfContact: [
        { value: "Phone Call", text: "Phone Call" },
        { value: "Whatsapp", text: "Whatsapp" },
        { value: "IMO", text: "IMO" },
        { value: "Reference", text: "Reference" },
        { value: "Others", text: "Others" },

      ],
    };
  },
  mounted() {
    this.initTrHeight();
    this.getUsers();
    this.getWallet();
    this.formValues.id = this.$route.params.id;

  },
  created() {
    window.addEventListener("resize", this.initTrHeight);
  },
  destroyed() {
    window.removeEventListener("resize", this.initTrHeight);
  },
  methods: {
    Submit() {
      this.isSubmitting = true;
      console.log("submt", this.formValues);

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

      axios
        .post("/updateCallCenter", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/Call_Center").then(() => {
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
          getWallet() {
        console.log(this.$route.params.id)
    
      axios.get('/getCallData', {
          params: {
          id: this.$route.params.id,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
             console.log(response.data.data); 


            this.formValues = response.data.data;


                this.initTrHeight();

            console.log('Wallet Fetched')
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
    onInputChange(item) {
        let text = item.request_owner;
        let type = "Employee";
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
          type: type,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.filteredOptions = [{ data: response.data.data }]

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
    // onInputChange(text) {
    //   console.log('text', text);
      
    //   if (text === "" || text === undefined) {
    //     return;
    //   }

    //   console.log("text", text);

    //   axios
    //     .get("/documentResourceOwner", {
    //       params: {
    //         id: text,
    //       },
    //     })
    //     .then((response) => {
    //       if (response.data.hasOwnProperty("success")) {
    //         if (response.data.success === true) {
    //           console.log(response.data.data);
    //           this.filteredOptions = [
    //             {
    //               data: response.data.data,
    //             },
    //           ];

    //           console.log("Assign to entity Fetched");
    //         } else {
    //           this.$toast({
    //             component: ToastificationContent,
    //             position: "top-right",
    //             props: {
    //               title: "Error",
    //               icon: "AlertCircleIcon",
    //               variant: "danger",
    //               text: response.data.message,
    //             },
    //           });
    //         }
    //       } else {
    //         this.$toast({
    //           component: ToastificationContent,
    //           position: "top-right",
    //           props: {
    //             title: "Error",
    //             icon: "AlertCircleIcon",
    //             variant: "danger",
    //             text: "Something went wrong, try again later",
    //           },
    //         });
    //       }
    //     })
    //     .catch((error) => {
    //       console.error(error);
    //     });
    // },

    getUsers() {
      this.isBusy = true;
      axios
        .get("/getAllSystemUsers", {
          params: {
            searchTerm: this.searchTerm,
            page_no: this.page,
          },
        })

        .then((response) => {
          console.log("response", response.data.data);
          this.userList = response.data.data;
          this.isBusy = false;

          // TODO
        })
        .catch((error) => {
          console.error(error);
        });
    },
    Type_Change(id) {
      console.log("type", id);

      this.formValues.department = id;
    },
    // assignOwnerField(event) {
    //   console.log("name", event.item);
    //   this.formValues.department_name = event.item.name;
    //   this.formValues.department = event.item.id;

    //   return;
    // },
        assignOwnerField(event) {
      console.log("name", event.item);
      this.formValues.name = event.item.name;
      this.formValues.name_id = event.item.id;

      return;
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
  resize: vertical;
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