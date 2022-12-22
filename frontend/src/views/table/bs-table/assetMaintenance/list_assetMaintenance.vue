<template>
    <div>
        <!-- search input -->
        <div class="custom-search d-flex justify-content-between">
            <b-form-group>
                <div class="d-flex align-items-center">
                    <!-- <label class="mr-1">Search</label> -->

                    <b-input-group>
                        <b-input-group-prepend>
                            <b-button variant="outline-primary" @click="(page = 1), getDocuments()">
                                <feather-icon icon="SearchIcon" />
                            </b-button>
                        </b-input-group-prepend>
                        <b-form-input v-model="searchTerm" placeholder="Search" type="text" class="d-inline-block"
                            @keyup="searchTimeOut()" />

                        <b-input-group-append>
                            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-modal.modal-advancesearch
                                variant="outline-primary">
                                Advance search
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </div>
            </b-form-group>

            <b-form-group v-if="$ability.can('add/copy', 'asset_maintenances')">
                <div class="d-flex align-items-center">
                    <b-input-group>
                        <b-input-group-prepend>
                            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                :to="{ name: 'create_asset_maintenance' }" title="Add Asset Maintenance">
                                ADD+
                            </b-button>
                        </b-input-group-prepend>
                    </b-input-group>
                </div>
            </b-form-group>
        </div>

        <!-- Advance Search Model -->
        <b-modal hide-footer id="modal-advancesearch" title="Advance Search" ref="searchModel" size="lg"
            cancel-variant="outline-secondary">
            <b-form @submit.prevent>
                <b-row>
                    <b-col md="6">
                        <b-form-group label="Asset Name" label-for="client">
                            <vue-autosuggest :suggestions="filteredAssetsOptions" :limit="10"
                                :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search with name or ID'}"
                                @input="onAssetInputChange" @selected="assignAssetField">
                                <template slot-scope="{suggestion}">
                                    <span class="my-suggestion-item">{{ suggestion.item.name }}, {{ suggestion.item.id
                                    }}
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
                                <b-form-datepicker v-model="formValues.maintenance_date" show-decade-nav button-only
                                    right locale="en-US" aria-controls="date" />
                            </b-input-group-append>
                        </b-input-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Maintenance Type" label-for="maintenance_type">
                            <v-select id="maintenance_type" v-model="formValues.maintenance_type"
                                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="maintenanceType"
                                label="text" return-object :reduce="text => text.text" />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="last_reading" label-for="last_reading">
                            <b-form-input id="last_reading" v-model="formValues.last_reading" type="number"
                                placeholder="0" />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Notes">
                            <b-form-textarea id="notes" v-model="formValues.notes" placeholder="Search Note's Here.."
                                rows="2" />
                        </b-form-group>
                    </b-col>



                    <b-col md="12">
                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
                            class="mr-1" @click="
                              (page = 1), (searchTerm = ''), closeSearch(), getDocuments()
                            ">
                            <span>GO</span>
                        </b-button>

                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="outline-primary"
                            class="mr-1" @click="resetSearch">
                            <span>Reset</span>
                        </b-button>

                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="outline-secondary"
                            class="mr-1" @click="closeSearch">
                            <span>Cancel</span>
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </b-modal>

        <b-table :items="items" :fields="fields" responsive class="mb-0 bg-white" :busy="isBusy">
            <template #cell(Options)="row">
                <b-form-group>
                    <div class="d-flex align-items-center w-fit-content">
                        <b-input-group>
                            <b-input-group-prepend>
                                <b-button size="sm" v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                    v-bind:class="
                                        row.detailsShowing ? 'active' : ''
                                    " @click="row.toggleDetails">
                                    <feather-icon icon="ChevronUpIcon" v-if="row.detailsShowing" />
                                    <feather-icon icon="ChevronDownIcon" v-else />
                                </b-button>
                            </b-input-group-prepend>

                            <b-input-group-append v-if="$ability.can('edit', 'asset_maintenances')">
                                <b-button size="sm" :to="{
                                    name: 'update_assetMaintenance',
                                    params: { id: row.item.id }
                                }" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-primary text-success">
                                    <feather-icon icon="EditIcon" />
                                </b-button>
                            </b-input-group-append>
                            <b-input-group-append v-if="$ability.can('delete', 'asset_maintenances')">
                                <b-button size="sm" v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                    variant="outline-primary text-danger" @click="deleteDocument(row.item.id)">
                                    <feather-icon icon="Trash2Icon" />
                                </b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </div>
                </b-form-group>
            </template>

            <template #cell(maintenance_date)="data">
              {{ data.value | moment("DD/MM/YYYY") }}
            </template>
            <!-- full detail on click -->
            <template #row-details="row">
                <b-card no-body>
                    <b-row>
                        <b-col cols="12" class="m-0 p-2">
                            <b-card-text>
                                <b-row class="border-bottom" v-for="item in row.item.attachment" :key="item">
                                    <b-col cols="2">
                                        <strong>Attachment</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        <a class="btn btn-primary" :href="base_url + '/' + item" target="_blank">View
                                            file</a>
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
            <b-pagination v-model="page" :total-rows="count" :per-page="pageSize" pills @change="handlePageChange" />
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
    BSpinner
} from "bootstrap-vue";
import Cleave from "vue-cleave-component";
import "cleave.js/dist/addons/cleave-phone.us";
import { VueAutosuggest } from 'vue-autosuggest'
import Ripple from "vue-ripple-directive";
import EnlargeableImage from "@diracleo/vue-enlargeable-image";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BCardCode,
        VueAutosuggest,
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
        Cleave
    },
    directives: {
        Ripple
    },
    data() {
        return {
            popoverShow: false,
            base_url: "",
            filteredAssetsOptions: [],
            formValues: {
                id: "",
                asset_name: "",
                maintenance_date: "",
                maintenance_type: "",
                last_reading: "",
                notes: "",
                created_by: "",
                updated_by: "",
            },
            options: {
                date: {
                    date: true,
                    delimiter: "-",
                    datePattern: ["Y", "m", "d"]
                }
            },
            exportLimit: "",
            isBusy: true,
            fields: [
                { key: "Options", thClass: "customHead" },
                {
                    key: "id",
                    label: "ID",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "asset_name",
                    label: "Asset Name",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "maintenance_date",
                    label: "Maintenance Date",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "maintenance_type",
                    label: "Maintenance Type",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "last_reading",
                    label: "Last Reading",
                    sortable: true,
                    thClass: "customHead"
                },
            ],
            items: [],
            maintenanceType: [
                { value: "Repair", text: "Repair" },
                { value: "Oil Change", text: "Oil Change" },
                { value: "Routine Service", text: "Routine Service" },
                { value: "Breakdown", text: "Breakdown" },
                { value: "Accident", text: "Accident" },
            ],
            /* eslint-disable global-require */

            searchTerm: "",
            pageSize: 10,
            page: 1,
            count: 0
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
                    cancelButton: "btn btn-outline-danger ml-1"
                },
                buttonsStyling: false
            }).then(result => {
                if (result.value) {
                    axios
                        .post("/deleteAssetMaintenance", {
                            id: id
                        })
                        .then(response => {
                            if (response.data.hasOwnProperty("success")) {
                                if (response.data.success === true) {
                                    this.getDocuments();
                                    this.$toast({
                                        component: ToastificationContent,
                                        position: "top-right",
                                        props: {
                                            title:
                                                "Asset Deleted Successfully",
                                            icon: "EditIcon",
                                            variant: "success"
                                        }
                                    });
                                    console.log(
                                        "Asset Deleted Successfully"
                                    );
                                } else {
                                    this.$toast({
                                        component: ToastificationContent,
                                        position: "top-right",
                                        props: {
                                            title: "Error",
                                            icon: "AlertCircleIcon",
                                            variant: "danger",
                                            text:
                                                "Something went wrong, try again later"
                                        }
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
                                        text:
                                            "Something went wrong, try again later"
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });
        },
        getDocuments() {
            this.isBusy = true;

            axios
                .get("/getAssetMaintenance", {
                    params: {
                        searchTerm: this.searchTerm,
                        page_no: this.page,
                        advanceSearch: this.formValues
                    }
                })

                .then(response => {
                    console.log("response", response.data.data);
                    this.items = response.data.data;
                    this.base_url = response.data.base_url;
                    this.count = response.data.totalRows;
                    this.isBusy = false;

                    // TODO
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

        handlePageChange(value) {
            this.page = value;
            this.isBusy = true;
            this.getDocuments();
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

        assignAssetField(item) {
            if (item) {
                this.formValues.asset_name = item.item.name
                this.formValues.asset_id = item.item.id
            }
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
        }
    }
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
