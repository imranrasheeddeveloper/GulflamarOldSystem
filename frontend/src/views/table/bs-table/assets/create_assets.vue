<template>
    <b-form @submit.prevent>
        <b-row>
            <!-- form repeate -->
            <b-col md="12">
                <b-card title="Add Assets ">
                    <div>
                        <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                            @submit.prevent="repeateAgain">
                            <!-- Row Loop -->
                            <b-row v-for="(item, index) in formValues.items" :id="item.id" :key="item.id" ref="row">
                                <b-col md="6">
                                    <b-form-group label="Asset Name" :v-label-for="'asset_name' + index">
                                        <b-form-input :v-id="'asset_name' + index" v-model="item.asset_name" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Asset Type" :v-label-for="'asset_type' + index">
                                        <v-select :v-id="'asset_type' + index" v-model="item.asset_type"
                                            @input="assetTypeInput(item)"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="AssetType"
                                            label="text" return-object :reduce="text => text.text" />
                                    </b-form-group>
                                </b-col>

                                <b-col md="6">
                                    <b-form-group label="Year" :v-label-for="'year' + index">
                                        <b-form-input :v-id="'year' + index" v-model="item.year" />
                                    </b-form-group>
                                </b-col>

                                <!-- <b-col md="6">
                                    <label :v-for="'year' + index">Year</label>
                                    <b-input-group>
                                        <cleave :v-id="'year' + index" v-model="item.year" class="form-control"
                                            :raw="false" :options="options.date" placeholder="YYYY-MM-DD" />

                                        <b-input-group-append>
                                            <b-form-datepicker v-model="item.year" show-decade-nav button-only right
                                                locale="en-US" :v-aria-controls="'year' + index" />
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-col> -->
                                <b-col md="6">
                                    <b-form-group label="Model" :v-label-for="'model' + index">
                                        <v-select :v-id="'model' + index" v-model="item.model"
                                            @input="assetModelInput(item)"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="AssetModel"
                                            label="text" return-object :reduce="text => text.text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Make" :v-label-for="'make' + index">
                                        <v-select :v-id="'make' + index" v-model="item.make"
                                            @input="assetMakeInput(item)"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="AssetMake"
                                            label="text" return-object :reduce="text => text.text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Capacity" :v-label-for="'capacity' + index">
                                        <v-select :v-id="'capacity' + index" v-model="item.capacity"
                                            @input="assetCapacityInput(item)"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="AssetCapacity"
                                            label="text" return-object :reduce="text => text.text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Fuel Type" :v-label-for="'fuel_type' + index">
                                        <v-select :v-id="'fuel_type' + index" v-model="item.fuel_type"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="FuleType"
                                            label="text" return-object :reduce="text => text.text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="6">
                                    <b-form-group label="Chassis Number" :v-label-for="'chassis_number' + index">
                                        <b-form-input :v-id="'chassis_number' + index" v-model="item.chassis_number" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="12">
                                    <b-form-group label="Mileage" :v-label-for="'mileage' + index">
                                        <b-form-input :v-id="'mileage' + index" v-model="item.mileage" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="12">
                                    <b-form-group label="Notes" :v-label-for="'notes' + index">
                                        <b-form-textarea :v-id="'notes' + index" v-model="item.notes"
                                            placeholder="Write Note's Here.." rows="4" />
                                    </b-form-group>
                                </b-col>


                                <b-col md="6">
                                    <b-form-group label="Attachment (*)." :v-label-for="'attach' + index">
                                        <b-form-file :v-id="'attach' + index" multiple v-model="item.attachment"
                                            accept="image/* , .pdf" placeholder="Upload attachment..."
                                            drop-placeholder="Drop file here..." @change="imageUpload($event, item)" />
                                    </b-form-group>
                                </b-col>
                                <!-- Remove Button -->
                                <b-col md="2" class="mb-50">
                                    <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-danger"
                                        class="mt-0 mt-md-2" @click="removeItem(index)">
                                        <feather-icon icon="XIcon" class="mr-25" />
                                        <span>Delete</span>
                                    </b-button>
                                </b-col>
                                <b-col cols="12">
                                    <hr class="hr_divider" />
                                </b-col>
                            </b-row>
                        </b-form>
                    </div>
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain">
                        <feather-icon icon="PlusIcon" class="mr-25" />
                        <span>Add more</span>
                    </b-button>
                </b-card>
            </b-col>

            <!-- submit and reset -->
            <b-col md="12">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    :disabled="isSubmitting" @click="Submit">
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
    BCard,
    BSpinner,
    BFormFile,
    BFormTextarea,
    BInputGroupAppend,
    BInputGroup
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
        BInputGroup
    },
    directives: {
        Ripple
    },

    mixins: [heightTransition],

    data() {
        return {
            options: {
                date: {
                    date: true,
                    datePattern: ["Y"]
                }
            },

            isSubmitting: false,

            nextTodoId: 2,





            formValues: {
                id: null,
                items: [
                    {
                        asset_name: "",
                        asset_type: "",
                        year: "",
                        model: "",
                        make: "",
                        capacity: "",
                        fuel_type: "",
                        chassis_number: "",
                        legal_documents: [],
                        attachment_length: 0,
                        attachment_file_name: "",
                        mileage: "",
                        notes: "",
                        asset_type_id: "",
                        asset_model_id: "",
                        asset_make_id: "",
                        asset_capacity_id: "",
                        attachment: []
                    }
                ]
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
        onChangeAssetType(e) {
            console.log(e)
        },
        assetTypeInput(item) {
            var result = item.asset_type.split(',')[1].trim();
            console.log(result)
            item.asset_type_id = result
        },
        assetModelInput(item) {
            var result = item.model.split(',')[1].trim();
            item.asset_model_id = result
        },
        assetMakeInput(item) {
            var result = item.make.split(',')[1].trim();
            item.asset_make_id = result
        },
        assetCapacityInput(item) {
            var result = item.capacity.split(',')[1].trim();
            item.asset_capacity_id = result
        },
        Submit() {
            this.isSubmitting = true;


            for (let i = 0; i < this.formValues.items.length; i++) {
                this.formValues.items[i]["attachment_file_name"] = "file" + i;
            }

            console.log("new form values", this.formValues);
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

            // append selected files into formData with unique array name
            for (let i = 0; i < this.formValues.items.length; i++) {
                for (
                    let j = 0;
                    j < this.formValues.items[i]["attachment_length"];
                    j++
                ) {
                    formData.append(
                        "file" + i + "[]",
                        this.formValues.items[i]["attachment"][j]
                    );
                }
            }

            axios
                .post("/createAsset", formData)
                .then(response => {
                    if (response.data.hasOwnProperty("success")) {
                        if (response.data.success === true) {
                            console.log(response.data.data);

                            this.$router
                                .replace(
                                    "/assets"
                                )
                                .then(() => {
                                    this.$toast({
                                        component: ToastificationContent,
                                        position: "top-right",
                                        props: {
                                            title: response.data.message,
                                            icon: "EditIcon",
                                            variant: "success"
                                        }
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
                                    variant: "danger"
                                }
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
                                text: "Something went wrong, try again later"
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    this.httpHelper(error);
                });
        },
        httpHelper(error) {
            console.log(error.response.status);

            if (error.response.status === 403) {
                this.$router.replace("/pages/miscellaneous/not-authorized");
            } else if (error.response.status === 500) {
                console.log("error axios", error.response.status);
                this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                        title: "Error",
                        icon: "AlertCircleIcon",
                        variant: "danger",
                        text: "Something went wrong, try again later"
                    }
                });
            }
        },

        imageUpload(e, item) {
            console.log("attachment files", e.target.files);
            if (e.target.files.length > 0) {
                for (let i = 0; i < e.target.files.length; i++) {
                    item.attachment.push(e.target.files[i]);
                }
                item.attachment_length = e.target.files.length;
            } else {
                item.attachment = null;
                item.attachment_length = 0;
            }
            console.log("attachment", item);
            console.log("attachment_length", item.attachment_length);
        },



        repeateAgain() {
            this.formValues.items.push({
                asset_name: "",
                asset_type: "",
                year: "",
                model: "",
                make: "",
                capacity: "",
                fuel_type: "",
                chassis_number: "",
                legal_documents: [],
                attachment_length: 0,
                attachment: [],
                attachment_file_name: "",
                mileage: "",
                notes: "",
            });
            console.log(this.formValues.items);
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        removeItem(index) {
            this.formValues.items.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        initTrHeight() {
            this.trSetHeight(null);
            this.$nextTick(() => {
                this.trSetHeight(this.$refs.form.scrollHeight);
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
    }
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
