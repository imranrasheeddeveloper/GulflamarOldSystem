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
                <label for="date">Maintenance Date</label>
                <b-input-group>
                    <cleave id="date" v-model="formValues.maintenance_date" class="form-control" :raw="false"
                        :options="options.date" placeholder="YYYY-MM-DD" />

                    <b-input-group-append>
                        <b-form-datepicker v-model="formValues.maintenance_date" show-decade-nav button-only right
                            locale="en-US" aria-controls="date" />
                    </b-input-group-append>
                </b-input-group>
            </b-col>
            <b-col md="6">
                <b-form-group label="Maintenance Type" label-for="maintenance_type">
                    <v-select id="maintenance_type" v-model="formValues.maintenance_type"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="maintenanceType" label="text"
                        return-object :reduce="text => text.text" />
                </b-form-group>
            </b-col>
            <b-col md="6">
                <b-form-group label="last_reading" label-for="last_reading">
                    <b-form-input id="last_reading" v-model="formValues.last_reading" type="number" placeholder="0" />
                </b-form-group>
            </b-col>
            <b-col md="12">

                <b-col md="6">
                    <b-form-group label="Attachment (*)." :v-label-for="'attach'">
                        <b-form-file :id="'attach'" multiple v-model="attachment" accept="image/* , .pdf"
                            placeholder="Upload attachment..." drop-placeholder="Drop file here..."
                            @change="imageUpload($event)" />
                    </b-form-group>
                </b-col>
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
            filteredAssetsOptions: [],
            attachment: [],
            maintenanceType: [
                { value: "Repair", text: "Repair" },
                { value: "Oil Change", text: "Oil Change" },
                { value: "Routine Service", text: "Routine Service" },
                { value: "Breakdown", text: "Breakdown" },
                { value: "Accident", text: "Accident" },
            ],
            formValues: {
                asset_id: "",
                maintenance_date: "",
                maintenance_type: "",
                last_reading: "",
                notes: "",
                asset_name: "",
            },
        }
    },
    mounted() {

    },



    methods: {
        assignAssetField(item) {
            if (item) {
                this.formValues.asset_name = item.item.name
                this.formValues.asset_id = item.item.id
            }
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

        submitResource() {
            this.isSubmitting = true

            const formData = new FormData()

            Object.entries(this.formValues).forEach(([key, value]) => {
                if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                    if (value instanceof File) {
                        formData.append(key, value)
                    } else {
                        console.log(key, value)
                        formData.append(key, JSON.stringify(value))
                    }
                } else if (typeof value === 'object' && value !== null && Array.isArray(value)) {
                    formData.append(key, JSON.stringify(value))
                } else if (value !== null) {
                    formData.append(key, value)
                }
            })

            if (this.attachment.length > 0) {
                for (let i = 0; i < this.attachment.length; i++) {
                    formData.append("file[]", this.attachment[i]);
                }
            }
            else {
                this.attachment = null;
            }

            console.log(this.formValues)
            axios.post('/addAssetMaintenance', formData).then(response => {
                if (response.data.hasOwnProperty('success')) {
                    if (response.data.success === true) {
                        console.log(response.data.data)

                        this.$router.replace('/list_asset_maintenance')
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
        imageUpload(e) {
            console.log('attachment files', e.target.files);
            if (e.target.files.length > 0) {
                for (let i = 0; i < e.target.files.length; i++) {
                    this.attachment.push(e.target.files[i]);
                }
            }
            else {
                this.attachment = null;
            }
            console.log('attachment', this.attachment)
        },
    },
}
</script>
