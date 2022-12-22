<template>
    <b-form @submit.prevent>
        <b-row>
            <b-col md="6">
                <b-form-group label="Asset Name" label-for="client">
                    <vue-autosuggest :suggestions="filteredAssetsOptions" :limit="10"
                        :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID'}"
                        @input="onAssetInputChange" @selected="assignAssetField">
                        <template slot-scope="{suggestion}">
                            <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }}
                            </span>
                        </template>
                    </vue-autosuggest>

                </b-form-group>
            </b-col>

            <b-col md="6">
                <b-form-group label="Client Name" label-for="client">
                    <vue-autosuggest :suggestions="filteredOptions" :limit="10"
                        :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID'}"
                        @input="onInputChange" @selected="assignOwnerField">
                        <template slot-scope="{suggestion}">
                            <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id }}
                            </span>
                        </template>
                    </vue-autosuggest>
                </b-form-group>
            </b-col>

            <b-col md="6">
                <b-form-group label="Location" label-for="location_name">
                    <v-select id="location_name" v-model="formValues.location_name" @input="assignLocationField"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="fillerdLocations" label="text"
                        return-object :reduce="text => text.text" />
                </b-form-group>
            </b-col>

            <b-col md="6">
                <label for="date">From Date</label>
                <b-input-group>

                    <cleave id="date" v-model="formValues.fromDate" class="form-control" :raw="false"
                        :options="options.date" placeholder="YYYY-MM-DD" />

                    <b-input-group-append>
                        <b-form-datepicker v-model="formValues.fromDate" show-decade-nav button-only right
                            locale="en-US" aria-controls="date" />
                    </b-input-group-append>

                </b-input-group>
            </b-col>

            <b-col md="6">
                <label for="date">To Date</label>
                <b-input-group>

                    <cleave id="date" v-model="formValues.toDate" class="form-control" :raw="false"
                        :options="options.date" placeholder="YYYY-MM-DD" />

                    <b-input-group-append>
                        <b-form-datepicker v-model="formValues.toDate" show-decade-nav button-only right locale="en-US"
                            aria-controls="date" />
                    </b-input-group-append>

                </b-input-group>
            </b-col>

            <b-col md="6">
                <b-form-group label="Rate Basis" label-for="rate_basis">
                    <v-select id="rate_basis" v-model="formValues.rate_basis"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="rateBasis" label="text"
                        return-object :reduce="text => text.text" />
                </b-form-group>
            </b-col>


            <b-col md="6">
                <b-form-group label="Rate (Excluding VAT)" label-for="rate">
                    <b-form-input id="rate" v-model="formValues.rate" type="number" placeholder="0" />
                </b-form-group>
            </b-col>


            <b-col md="6">
                <b-form-group label="Check out Reading" label-for="check_out_reading">
                    <b-form-input id="check_out_reading" v-model="formValues.check_out_reading" type="number"
                        placeholder="0" />
                </b-form-group>
            </b-col>


            <b-col md="6">
                <b-form-group label="Check In Reading" label-for="check_in_reading">
                    <b-form-input id="check_out_reading" v-model="formValues.check_in_reading" type="number"
                        placeholder="0" />
                </b-form-group>
            </b-col>

            <b-col md="6">
                <b-form-group label="Scope" label-for="v-scope">
                    <b-form-checkbox-group id="checkbox-group-2" v-model="formValues.scope" name="scope"
                        class="demo-inline-spacing">
                        <b-form-checkbox value="Transportation Deploy">
                            Transportation Deploy
                        </b-form-checkbox>
                        <b-form-checkbox value="Transportation Return">
                            Transportation Return
                        </b-form-checkbox>
                        <b-form-checkbox value="Maintenance">
                            Maintenance
                        </b-form-checkbox>
                        <b-form-checkbox value="Operator">
                            Operator
                        </b-form-checkbox>
                        <b-form-checkbox value="Oil Change">
                            Oil Change
                        </b-form-checkbox>
                        <b-form-checkbox value="Fuel">
                            Fuel
                        </b-form-checkbox>
                        <b-form-checkbox value="Others">
                            Others
                        </b-form-checkbox>

                    </b-form-checkbox-group>
                </b-form-group>
            </b-col>
            <b-col md="12">
                <b-form-group label="Notes" label-for="notes">
                    <b-form-textarea id="notes" v-model="formValues.notes" placeholder="Write Notes Here.." rows="3" />
                </b-form-group>
            </b-col>

            <!-- submit and reset -->

            <b-col md="12">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    :disabled="isSubmitting" @click="submitResource">
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
    BRow, BFormTextarea, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormDatepicker, BCard, BFormFile, BSpinner, BInputGroup, BInputGroupPrepend, BFormCheckboxGroup, BInputGroupAppend,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import VueMonthlyPicker from 'vue-monthly-picker'
import { MonthPickerInput } from 'vue-month-picker'
import { VueAutosuggest } from 'vue-autosuggest'
import axios from '@axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'

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
        BFormFile,
        BSpinner,
        BInputGroup,
        BInputGroupPrepend,
        BFormCheckboxGroup,
        VueMonthlyPicker,
        MonthPickerInput,
        BInputGroupAppend,
        Cleave,
        BFormTextarea,
    },
    directives: {
        Ripple,
    },



    data() {
        return {
            options: {
                date: {
                    date: true,
                    delimiter: '-',
                    datePattern: ['Y', 'm', 'd'],
                },
            },
            isSubmitting: false,
            filteredOptions: [],
            filteredAssetsOptions: [],
            fillerdLocations: [],
            rateBasis: [
                { value: "Hourly", text: "Hourly" },
                { value: "Daily", text: "Daily" },
                { value: "Weekly", text: "Weekly" },
                { value: "Monthly", text: "Monthly" },
                { value: "Yearly", text: "Yearly" },
            ],
            formValues: {
                asset_id: "",
                fromDate: "",
                toDate: "",
                c_id: "",
                location_id: "",
                rate_basis: "",
                rate: "",
                check_out_reading: "",
                check_in_reading: "",
                scope: [],
                notes: "",
                asset_name: "",
                location_name: "",
                client_name: "",
                notes: "",
                created_by:"",
            },
        }
    },
    mounted() {

    },



    methods: {
        assignOwnerField(item) {
            if (item) {
                this.formValues.c_id = item.item.id
                this.formValues.client_name = item.item.name
                console.log(this.formValues.client_name)
                this.onLocationInputChange(this.formValues.c_id)
            }
        },
        assignLocationField(item) {
            var val = this.fillerdLocations.find(location => location.text === item).value
            this.formValues.location_id = val
        },
        assignAssetField(item) {
            if (item) {
                this.formValues.asset_name = item.item.name
                this.formValues.asset_id = item.item.id
            }
        },

        onInputChange(text) {
            if (text === '' || text === undefined) {
                return
            }

            axios.get('/getEmployeeDropdown', {
                params: {
                    id: text,
                    resourceType: 'Project',
                },
            }).then(response => {
                if (response.data.hasOwnProperty('success')) {
                    if (response.data.success === true) {
                        console.log(response.data.data)
                        this.filteredOptions = [{ data: response.data.data }]

                        console.log('Clients Fetched')
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
        onAssetInputChange(text) {
            if (text === '' || text === undefined) {
                return
            }
            axios.get('/getAssetsDropdown', {
                params: {
                    id: text,
                },
            }).then(response => {
                if (response.data.hasOwnProperty('success')) {
                    if (response.data.success === true) {
                        console.log(response.data.data)
                        this.filteredAssetsOptions = [{ data: response.data.data }]

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

        onLocationInputChange(text) {
            if (text === '' || text === undefined) {
                return
            }

            axios.get('/getClientLocations', {
                params: {
                    client_code: text,

                },
            }).then(response => {
                if (response.data.hasOwnProperty('success')) {
                    if (response.data.success === true) {
                        console.log(response.data.data.location_name)
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

        submitResource() {
            this.isSubmitting = true
        
            const formData = new FormData()

            Object.entries(this.formValues).forEach(([key, value]) => {
                if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                    console.log(key, value)
                    if (value instanceof File) {
                        formData.append(key, value)
                    } else {
                        formData.append(key, JSON.stringify(value))
                    }
                } else if (value !== null) {
                    formData.append(key, value)
                }
            })


            console.log(this.formValues)
            axios.post('/addWorkOrder', formData).then(response => {
                if (response.data.hasOwnProperty('success')) {
                    if (response.data.success === true) {
                        console.log(response.data.data)

                        this.$router.replace('/list_assets_work_order')
                            .then(() => {
                                this.$toast({
                                    component: ToastificationContent,
                                    position: 'top-right',
                                    props: {
                                        title: response.data.message,
                                        icon: 'EditIcon',
                                        variant: 'success',
                                    },
                                })
                            })
                    } else {
                        this.isSubmitting = false

                        this.$toast({
                            component: ToastificationContent,
                            position: 'top-right',
                            props: {
                                title: response.data.message,
                                icon: 'AlertCircleIcon',
                                variant: 'danger',
                            },
                        })
                    }
                } else {
                    this.isSubmitting = false

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
    },
}
</script>
