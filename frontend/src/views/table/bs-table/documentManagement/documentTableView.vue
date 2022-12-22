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
                                @click="(page = 1), getDocuments()"
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

            <b-form-group v-if="$ability.can('add/copy', 'document')">
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
                                                    title="Close"
                                                    v-ripple.400="
                                                        'rgba(255, 255, 255, 0.15)'
                                                    "
                                                    size="sm"
                                                    variant="danger"
                                                    class="mr-1"
                                                    @click="onClose"
                                                >
                                                    <feather-icon
                                                        icon="XIcon"
                                                    />
                                                </b-button>
                                            </b-col>

                                            <b-col cols="6">
                                                <b-button
                                                    title="EXCEL"
                                                    v-ripple.400="
                                                        'rgba(255, 255, 255, 0.15)'
                                                    "
                                                    size="sm"
                                                    variant="primary"
                                                    :href="
                                                        '/api/document_management_export_excel?searchTerm=' +
                                                            searchTerm +
                                                            ' &amp;limit=' +
                                                            exportLimit
                                                    "
                                                    target="_blank"
                                                >
                                                    <feather-icon
                                                        icon="DownloadIcon"
                                                    />
                                                </b-button>
                                            </b-col>

                                            <!-- <b-col cols="12" class="text-center pt-1">

                        <b-button
                        title="PDF"
                                size="sm"
                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                            :href="'/document_management_export_pdf/?searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
                            target="_blank"
                            variant="info"
                        >
                          <feather-icon icon="PrinterIcon" />
                        </b-button>
                      </b-col> -->
                                        </b-row>
                                    </div>
                                </b-popover>
                            </div>
                        </b-input-group-prepend>
                        <b-input-group-prepend>
                            <b-button
                                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                variant="primary"
                                :to="{ name: 'create-new-document' }"
                                title="Add Client"
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
                        <b-form-group
                            label="Recipient Type"
                            label-for="Recipient_Type"
                        >
                            <v-select
                                id="Recipient_Type"
                                v-model="formValues.recipient_type"
                                :dir="
                                    $store.state.appConfig.isRTL ? 'rtl' : 'ltr'
                                "
                                :options="recipientType"
                                label="text"
                                :reduce="text => text.value"
                                @input="Type_Change(formValues.recipient_type)"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                            label="Document Type"
                            label-for="Recipient"
                        >
                            <v-select
                                id="Recipient"
                                v-model="formValues.recipient"
                                :dir="
                                    $store.state.appConfig.isRTL ? 'rtl' : 'ltr'
                                "
                                :options="recipients"
                                label="text"
                                :reduce="text => text.value"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col md="6">
                        <label for="v-dob">Date</label>
                        <b-input-group>
                            <cleave
                                id="date"
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
                                    aria-controls="v-dob"
                                />
                            </b-input-group-append>
                        </b-input-group>
                    </b-col>

                    <b-col md="12">
                        <b-button
                            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                            type="submit"
                            variant="primary"
                            class="mr-1"
                            @click="
                                (page = 1),
                                    (searchTerm = ''),
                                    closeSearch(),
                                    getDocuments()
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
          <b-form-checkbox
            v-model="row.detailsShowing"
            @change="row.toggleDetails"
          >
            {{ row.detailsShowing ? 'Hide' : 'View Details' }}
          </b-form-checkbox>
          <div class="d-flex justify-content-start">
            <b-button
              v-if="$ability.can('edit', 'document')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'edit-document', params: { id: row.item.id }}"
              variant="flat-success"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="EditIcon" />
            </b-button>

            <b-button
              v-if="$ability.can('delete', 'document')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-danger"
              class="btn-icon rounded-circle"
              @click="deleteDocument(row.item.id)"
            >
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
                                    v-bind:class="
                                        row.detailsShowing ? 'active' : ''
                                    "
                                    @click="row.toggleDetails"
                                >
                                    <feather-icon
                                        icon="ChevronUpIcon"
                                        v-if="row.detailsShowing"
                                    />

                                    <feather-icon
                                        icon="ChevronDownIcon"
                                        v-else
                                    />
                                </b-button>
                            </b-input-group-prepend>

                            <b-input-group-append
                                v-if="$ability.can('edit', 'document')"
                            >
                                <b-button
                                    size="sm"
                                    :to="{
                                        name: 'edit-document',
                                        params: { id: row.item.id }
                                    }"
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

            <template #cell(recipient_type)="data">
                <b-badge pill :variant="statusVariant(data.value)">
                    {{ data.value }}
                </b-badge>
            </template>

            <!-- full detail on click -->
            <template #row-details="row">
                <b-card no-body>
                    <b-row>
                        <b-col cols="12" class="m-0 p-2">
                            <b-card-text>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Description</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.description }}
                                    </b-col>
                                </b-row>

                                <b-row
                                    class="border-bottom"
                                    v-for="item in row.item.attachment"
                                    :key="item"
                                >
                                    <b-col cols="2">
                                        <strong>Attachment</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        <a
                                            class="btn btn-primary"
                                            :href="base_url + '/' + item"
                                            target="_blank"
                                            >View file</a
                                        >
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="12">
                                        <strong>Method : </strong>
                                        {{ row.item.method }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="12">
                                        <strong>Received at : </strong>
                                        {{ row.item.received_at }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="12">
                                        <strong>Received by : </strong>
                                        {{ row.item.received_by }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="12" class="text-right">
                                        <strong>Created By : </strong>
                                        {{ row.item.created_by }}
                                    </b-col>
                                </b-row>
                                <b-row
                                    class="border-bottom"
                                    v-if="row.item.updated_by"
                                >
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
            recipientType: [
                { value: "Accommodation", text: "Accommodation" },
                { value: "Client", text: "Client" },
                { value: "Employee", text: "Employee" },
                { value: "Supplier", text: "Supplier" },
                { value: "Vendor", text: "Vendor" }
            ],
            recipients: [
                { value: "New Contract", text: "New Contract" },
                { value: "Renewal Contract", text: "Renewal Contract" },
                { value: "Contract Termination", text: "Contract Termination" },
                { value: "Rent Payment", text: "Rent Payment" },
                { value: "Electricity Payment", text: "Electricity Payment" },
                { value: "Others", text: "Others" }
            ],
            recipients_accommodation: [
                { value: "New Contract", text: "New Contract" },
                { value: "Renewal Contract", text: "Renewal Contract" },
                { value: "Contract Termination", text: "Contract Termination" },
                { value: "Rent Payment", text: "Rent Payment" },
                { value: "Electricity Payment", text: "Electricity Payment" },
                { value: "Others", text: "Others" }
            ],
            recipients_client: [
                { value: "Addendum", text: "Addendum" },
                { value: "Statement Of Account", text: "Statement Of Account" },
                { value: "Balance Confirmation", text: "Balance Confirmation" },
                { value: "Delayed Payment", text: "Delayed Payment" },
                { value: "Dispatch Notice", text: "Dispatch Notice" },
                { value: "Non-Renewal", text: "Non-Renewal" },
                { value: "Others", text: "Others" },
                { value: "Renewal", text: "Renewal" },
                { value: "Termination", text: "Termination" }
            ],
            recipients_vendor: [
                { value: "AJEER Request", text: "AJEER Request" },
                { value: "Balance Confirmation", text: "Balance Confirmation" },
                { value: "Employee Runaway", text: "Employee Runaway" },
                { value: "Employee Termination", text: "Employee Termination" },
                {
                    value: "Employee Vacation Request",
                    text: "Employee Vacation Request"
                },
                { value: "Iqama Expiry", text: "Iqama Expiry" }
            ],
            recipients_employee: [
                { value: "Salary Slip", text: "Salary Slip" },
                {
                    value: "Employment Certificate",
                    text: "Employment Certificate"
                },
                {
                    value: "Entry & Exit Certificate",
                    text: "Entry & Exit Certificate"
                },
                { value: "Salary Certificate", text: "Salary Certificate" },
                { value: "Vacation Benefits", text: "Vacation Benefits" },
                { value: "EOSB", text: "EOSB" }
            ],
            recipients_supplier: [
                { value: "Balance Confirmation", text: "Balance Confirmation" },
                { value: "Statement of Account", text: "Statement of Account" },
                { value: "Others", text: "Others" }
            ],
            formValues: {
                recipient_type: "",
                recipient: "",
                date: ""
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
                    key: "recipient_type",
                    label: "Recipient Type",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "recipient",
                    label: "Document Type",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "resource_owner",
                    label: "Recipient",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "date",
                    label: "Date",
                    sortable: true,
                    thClass: "customHead"
                }
            ],
            items: [],
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

        Type_Change(type) {
            // console.log('recipient_type',this.formValues.recipient_type);
            this.formValues.recipient = null;
            // console.log('recipient',this.formValues.recipient);

            this.recipients = null;
            if (type == "Accommodation") {
                this.recipients = this.recipients_accommodation;
                return;
            }
            if (type == "Client") {
                this.recipients = this.recipients_client;
                return;
            }
            if (type == "Employee") {
                this.recipients = this.recipients_employee;
                return;
            }
            if (type == "Supplier") {
                this.recipients = this.recipients_supplier;
                return;
            }
            if (type == "Vendor") {
                this.recipients = this.recipients_vendor;
                return;
            }
            console.log("type", type);
            this.recipients = this.recipients_accommodation;
            return;
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
                        .post("/deleteDocument", {
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
                                                "Document Deleted Successfully",
                                            icon: "EditIcon",
                                            variant: "success"
                                        }
                                    });
                                    console.log(
                                        "Document Deleted Successfully"
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
                .get("/getAllDocuments", {
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

        statusVariant(ownerType) {
            if (ownerType == "Accommodation") {
                return "warning";
            } else if (ownerType == "Client") {
                return "success";
            } else if (ownerType == "Employee") {
                return "primary";
            } else if (ownerType == "Supplier") {
                return "info";
            } else if (ownerType == "Vendor") {
                return "secondary";
            } else {
                return "primary";
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
