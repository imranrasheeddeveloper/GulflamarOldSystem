<template>
  <b-form @submit.prevent>
    <b-row>

      <b-col md="6">
        <b-form-group label="Name" label-for="v-name">
          <b-form-input id="v-name'" v-model="formValues.name" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Type" label-for="v-type">
           <v-select
            id="v-Status"
            v-model="formValues.type"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="RequestType"
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
        name: null,
        type:null,
        issue:null,
      },
      filteredOptions: [],
      userList: [],
        RequestType: [
         { value: "Material", text: "Material" },
        { value: "Repair", text: "Repair" },
        { value: "Salary Issue", text: "Salary Issue" },
        { value: "Iqama Issue", text: "Iqama Issue" },
      ],
    };
  },
  mounted() {
    this.initTrHeight();
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
        .post("/addRequestCenter", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/addRequestCenter").then(() => {
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