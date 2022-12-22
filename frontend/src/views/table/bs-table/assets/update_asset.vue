<template>
  <b-form @submit.prevent>
    <b-row>
      <b-col md="6">
        <b-form-group label="Asset Name" label-for="'asset_name">
          <b-form-input @scroll.prevent id="'asset_name" v-model="formValues.asset_name" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Asset Type" label-for="asset_type">
          <v-select id="asset_type" v-model="formValues.asset_type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            @input="assetTypeInput" :options="AssetType" label="text" return-object :reduce="text => text.text" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Year" label-for="'year">
          <b-form-input @scroll.prevent id="'year" v-model="formValues.year" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Model" label-for="model">
          <v-select id="model" v-model="formValues.model" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="AssetModel" label="text" return-object :reduce="text => text.text" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Make" label-for="make">
          <v-select id="make" v-model="formValues.make" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="AssetMake" label="text" return-object :reduce="text => text.text" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Capacity" label-for="capacity">
          <v-select id="capacity" v-model="formValues.capacity" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="AssetCapacity" label="text" return-object :reduce="text => text.text" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Fuel Type" label-for="fuel_type">
          <v-select id="fuel_type" v-model="formValues.fuel_type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="FuleType" label="text" return-object :reduce="text => text.text" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Chassis Number" label-for="'chassis_number">
          <b-form-input @scroll.prevent id="'chassis_number" v-model="formValues.chassis_number" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="M  ileage" label-for="'mileage">
          <b-form-input @scroll.prevent id="'mileage" v-model="formValues.mileage" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Notes" label-for="'notes">
          <b-form-textarea id="'notes" v-model="formValues.notes" placeholder="Write Note's Here.." rows="2" />
        </b-form-group>
      </b-col>

      <b-col md="6">
        <b-form-group label="Attachment (*)." label-for="'attach">
          <b-form-file id="'attach" multiple v-model="new_attachment" accept="image/* , .pdf"
            placeholder="Upload attachment..." drop-placeholder="Drop file here..." @change="imageUpload($event)" />
        </b-form-group>
      </b-col>
      <b-col md="6 my-2 border rounded m-0 p-0 text-center" v-for="item in formValues.legal_documents" :key="item">
        <b-form-group class="m-0 p-0">
          <a class="btn btn-primary mx-2 my-0" :href="base_url + '/' + item" target="_blank" :title="item">View file</a>
          <button :title="item" class="btn btn-danger mx-2 my-0" @click="reomveFile(item)">
            Delete file
          </button>
        </b-form-group>
      </b-col>

      <!-- submit and reset -->
      <b-col md="12">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          :disabled="isSubmitting" @click="Submit">
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
      base_url: '',
      isSubmitting: false,
      new_attachment: [],

      formValues: {
        asset_name: "",
        asset_type: "",
        year: "",
        model: "",
        make: "",
        capacity: "",
        fuel_type: "",
        chassis_number: "",
        legal_documents: [],
        attachment: [],
        attachment_length: 0,
        attachment_file_name: "",
        mileage: "",
        notes: "",
        asset_type_id: "",
        asset_model_id: "",
        asset_make_id: "",
        asset_capacity_id: "",
      },
      AssetType: [],
      AssetModel: [],
      AssetMake: [],
      AssetCapacity: [],
      FuleType: [
        { value: "None", text: "None" },
        { value: "Electric", text: "Electric" },
        { value: "Diesel", text: "Diesel" },
        { value: "Petrol", text: "Petrol" },
      ]



    };
  },
  mounted() {
    this.initTrHeight();
    this.getDocument();
    this.getAssetType();
    this.getAssetModel();
    this.getAssetMake();
    this.getAssetCapacity();
  },
  created() {
    window.addEventListener("resize", this.initTrHeight);
  },
  destroyed() {
    window.removeEventListener("resize", this.initTrHeight);
  },
  methods: {

    Submit() {
      console.log("param", this.$route.params.id);

      // create new formData for post request to send files

      const formData = new FormData();


      if (this.new_attachment.length > 0) {
        for (let i = 0; i < this.new_attachment.length; i++) {
          formData.append("file[]", this.new_attachment[i]);
        }
      }
      else {
        this.new_attachment = [];
      }


      this.isSubmitting = true;
      console.log("submt", this.formValues);
      console.log("new form values", this.formValues);
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
        .post("/updateAsset", formData)
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);

              this.$router.replace("/assets").then(() => {
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
    assetTypeInput(text) {
      var result = text.split(',')[1].trim();
      console.log(result)
      this.formValues.asset_type_id = result
    },
    assetModelInput(text) {
            var result = text.model.split(',')[1].trim();
            this.formValues.asset_model_id = result
        },
        assetMakeInput(text) {
            var result = text.make.split(',')[1].trim();
            this.formValues.asset_make_id = result
        },
        assetCapacityInput(text) {
            var result = text.capacity.split(',')[1].trim();
            this.formValues.asset_capacity_id = result
        },

    getDocument() {
      console.log(this.$route.params.id);

      axios
        .get("/getAssetById", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.formValues = response.data.data;
              this.formValues.asset_type = response.data.data.asset_type.title;
              this.formValues.model = response.data.data.model.title;
              this.formValues.make = response.data.data.make.title;
              this.formValues.capacity = response.data.data.capacity.title;
              this.base_url = response.data.base_url;
              this.initTrHeight();
              console.log("form", this.formValues);
              console.log("Transaction Fetched");
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
    httpHelper(error) {
      console.log(error.response.status)

      if (error.response.status === 403) {
        this.$router.replace('/pages/miscellaneous/not-authorized')
      }
      else if (error.response.status === 500) {
        console.log("error axios", error.response.status)
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
    imageUpload(e) {
      console.log("attachment files", e.target.files);
      if (e.target.files.length > 0) {
        for (let i = 0; i < e.target.files.length; i++) {
          this.new_attachment.push(e.target.files[i]);
        }
      } else {
        this.new_attachment = null;
      }
      console.log("attachment", this.new_attachment);
    },

    Type_Change() {
      console.log("clear item ", this.formValues);
      this.formValues.assign_to_entity = "";
      this.formValues.assign_to_entity_id = "";
      this.formValues.client_location = "";
      this.fillerdLocations = [];
      if (this.formValues.assign_to_category == 'Client') {
        this.locationField = true;
        this.trAddHeight(100);
      }
      else {
        this.locationField = false;
        this.removeTrHeight(100);
      }

      this.filteredOptions = [];
      console.log("clear item new", this.formValues);
    },


    onLocationInputChange(item) {
      let text = item;
      if (text === "" || text === undefined) {
        return;
      }
      axios.get('/getClientLocations', {
        params: {
          client_code: text,
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            this.fillerdLocations = response.data.data
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
    reomveFile(file) {
      console.log("this.formValues.attachment", this.formValues.legal_documents);
      const index = this.formValues.legal_documents.indexOf(file);
      if (index > -1) {
        this.formValues.legal_documents.splice(index, 1); // 2nd parameter means remove one item only
      }
      console.log("this.formValues.attachment", this.formValues.legal_documents);
    },


    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        // this.trSetHeight(this.$refs.form.scrollHeight);
      });
    },
    getAssetType() {
      axios.get('/getAssetTypeDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.AssetType = response.data.data
            if (this.AssetType instanceof Array) {
              this.AssetType = response.data.data
            } else {
              this.AssetType = [response.data.data]
            }

            console.log('Assets Fetched')
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
    getAssetModel() {
      axios.get('/getAssetModelDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.AssetModel = response.data.data
            if (this.AssetModel instanceof Array) {
              this.AssetModel = response.data.data
            } else {
              this.AssetModel = [response.data.data]
            }

            console.log('Assets Fetched')
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
    getAssetMake() {
      axios.get('/getAssetMakeDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)

            this.AssetMake = response.data.data
            if (this.AssetMake instanceof Array) {
              this.AssetMake = response.data.data
            } else {
              this.AssetMake = [response.data.data]
            }

            console.log('Assets Fetched')
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
    getAssetCapacity() {
      axios.get('/getAssetCapacityDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.AssetCapacity = response.data.data
            if (this.AssetCapacity instanceof Array) {
              this.AssetCapacity = response.data.data
            } else {
              this.AssetCapacity = [response.data.data]
            }
            console.log('Assets Fetched')
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