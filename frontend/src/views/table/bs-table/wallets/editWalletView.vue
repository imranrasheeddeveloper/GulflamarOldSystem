<template>
  <b-form @submit.prevent>
    <b-row>
      <!-- <b-col md="6">
        <b-form-group label="User" label-for="v-user">
          <b-form-input id="v-user" v-model="formValues.user" />
        </b-form-group>
      </b-col> -->


        <b-col md="6">
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
            
            @input="Type_Change($event)"


          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Account Name" label-for="v-account_name">
          <b-form-input
            id="v-account_name'"
            v-model="formValues.account_name"
          />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Balance" label-for="balance">
          <b-form-input
            id="balance"
            v-model.number="formValues.balance"
            type="number"
            placeholder="0.00"
            :readonly="true"
            @scroll.prevent
          />
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

      isSubmitting: false,


      formValues: {
        id:null,
        user_id: null,
        date: null,
        account_name: null,
        balance: null,
      },

            userList: [
      ],

    };
  },
  mounted() {
    this.initTrHeight();
    this.getUsers();
    this.getWallet();
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
        .post("/updateWallet", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/wallets").then(() => {
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

    getUsers() {
      this.isBusy = true
      axios.get('/getAllSystemUsers', {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,

        },
      })

        .then(response => {
          console.log('response', response.data.data)
          this.userList = response.data.data
          this.isBusy = false

          // TODO
        }).catch(error => {
          console.error(error)
        })
    },

      getWallet() {
        console.log(this.$route.params.id)
    
      axios.get('/getWallet', {
          params: {
          id: this.$route.params.id,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
             console.log(response.data.data); 
            // var recipient = response.data.data.recipient;
            // var resource_owner_name = response.data.data.resource_owner_name;
            // var resource_owner_id = response.data.data.resource_owner_id;


            // console.log('recipient',recipient);

            this.formValues = response.data.data;

            // this.Type_Change(this.formValues.recipient_type);
            // this.formValues.recipient = recipient;
            // this.formValues.resource_owner_name = resource_owner_name;
            // this.formValues.resource_owner_id = resource_owner_id;




            
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
        Type_Change(type) {
          console.log('type', type);
          
        // console.log('recipient_type',this.formValues.recipient_type);
        // this.formValues.recipient = null;
        // console.log('recipient',this.formValues.recipient);


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