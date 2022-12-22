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
                @click="page=1, getEmployees()"
              >
                <feather-icon icon="SearchIcon" />
              </b-button>
            </b-input-group-prepend>
            <b-form-input
              v-model="searchTerm"
              placeholder="Search"
              type="text"
              class=" d-inline-block"
              @keyup="searchTimeOut()"
            />
            <!-- <b-input-group-append>
                <b-button
                :to="{ name: 'create-new-client'}"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary">
                  ADD +
                </b-button>
              </b-input-group-append> -->
            <!--  -->
          </b-input-group>

        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'client')">
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
                    <div class="d-flex justify-content-between align-items-center">
                      <span>Export Table</span>
                    </div>
                  </template>

                  <div>
                    <b-form-group
                      label="Search Filter"
                    >
                      <b-form-input
                        v-model="searchTerm"
                        placeholder=""
                        size="sm"
                      />
                    </b-form-group>

                    <b-form-group
                      label="Records Limit"
                    >
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
                          :href="'/api/clientCsv?searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
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

                :to="{ name: 'create-new-client'}"

                title="Add Client"
              >
                ADD+
              </b-button>
            </b-input-group-prepend>

          </b-input-group>

        </div>
      </b-form-group>
    </div>

    <b-table
      :items="items"
      :fields="fields"
      responsive
      class="mb-0 bg-white"
      :busy="isBusy"
    >
      <template #cell(Options)="row">
        <div>
        <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
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

              <b-input-group-append v-if="$ability.can('edit', 'client')">
                <b-button
                  size="sm"
                  :to="{ name: 'edit-client', params: {client_code: row.item.client_code } }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append
                v-if="$ability.can('delete', 'client')"
              >
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-danger"
                  @click="deleteEmployee(row.item.client_code)"
                >
                  <feather-icon icon="Trash2Icon" />
                </b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-form-group>  
        </div>
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card no-body>
          <b-row>
            <b-col cols="12">
              <b-tabs
                pills
                card
                horizontal
              >

                <b-tab title="Client Details">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Client Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.client_name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Client Name (ar)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.client_name_ar }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Client Code</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.client_code }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Address</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.address }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>CR Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.cr_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>VAT Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.vat_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom  ">
                      <b-col cols="2">
                        <strong>CR Attachment</strong>
                      </b-col>
                      <b-col
                        v-if="isPdf(row.item.cr)"
                        cols="10"
                      >

                        <a
                          class="btn btn-primary"
                          :href="row.item.cr"
                          target="_blank"
                        >View file</a>

                      </b-col>
                      <b-col
                        v-else
                        cols="10"
                      >
                        <enlargeable-image
                          :src="row.item.cr"
                          :src_large="row.item.cr"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.cr"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom  ">
                      <b-col cols="2">
                        <strong>VAT Attachment</strong>
                      </b-col>
                      <b-col
                        v-if="isPdf(row.item.vat_cert)"
                        cols="10"
                      >

                        <a
                          class="btn btn-primary"
                          :href="row.item.vat_cert"
                          target="_blank"
                        >View file</a>

                      </b-col>
                      <b-col
                        v-else
                        cols="10"
                      >
                        <enlargeable-image
                          :src="row.item.vat_cert"
                          :src_large="row.item.vat_cert"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.vat_cert"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>AJEER License</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.ajeer_license }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Department</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.dept_en }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Department (ar)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.dept_ar }}
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contactName }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contact Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contactNo }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Account Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.account_name }}
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Bank Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.bank_name }}
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>IBAN</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.iban }}
                      </b-col>
                    </b-row>

                    <b-row class="border-bottom  ">
                      <b-col cols="2">
                        <strong>Contract File</strong>
                      </b-col>
                      <b-col
                        v-if="isPdf(row.item.contract)"
                        cols="10"
                      >

                        <a
                          class="btn btn-primary"
                          :href="row.item.contract"
                          target="_blank"
                        >View file</a>

                      </b-col>
                      <b-col
                        v-else
                        cols="10"
                      >
                        <enlargeable-image
                          :src="row.item.contract"
                          :src_large="row.item.contract"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px;"
                            thumbnail
                            :src="row.item.contract"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contract Type</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contract_type }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Contract Status</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contract_status }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Operational Services</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-list-group horizontal>
                          <b-list-group-item
                            v-for="oneSkill in row.item.fat_details"
                            :key="oneSkill"
                          >
                            {{ oneSkill }}
                          </b-list-group-item>

                        </b-list-group>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Project Start Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.start_date | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Project End Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.end_date | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Working Days</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.daysWeek }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom ">
                      <b-col cols="2">
                        <strong>Working Hours</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.hoursDay }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Client Services">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="12">
                        <b-table
                          :items="row.item.services"
                          :fields="serviceFields"
                          striped
                          responsive
                          class="mb-0"
                          :busy="isBusy"
                          show-empty
                        >
                          <template #empty="scope">
                            <h4>{{ scope.emptyText }}</h4>
                          </template>
                          <template #cell(rate)="data">
                            {{ Number( data.value).toLocaleString() }}
                          </template>

                        </b-table>
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Client Location">
                  <b-card-text>
                    <b-row class="border-bottom ">
                      <b-col cols="12">
                        <b-table
                          :items="row.item.location"
                          :fields="LocationFields"
                          striped
                          responsive
                          class="mb-0"
                          :busy="isBusy"
                          show-empty
                        >
                          <template #empty="scope">
                            <h4>{{ scope.emptyText }}</h4>
                          </template>
                          <template #cell(location_map)="data">
                           <a href="https://www.google.com/maps/search/51.5088233,-0.1296787/@51.5088233,-0.1296787,13z" target="_blank">{{data.value}}</a>
                          </template>
                        </b-table>
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-col>
          </b-row>
          <b-row class="border-bottom ">
            <b-col cols="12" class="text-right">
                <strong>Created By : </strong>
                {{ row.item.created_by }}
            </b-col>
          </b-row>
          <b-row class="border-bottom " v-if="row.item.updated_by">
            <b-col cols="12" class="text-right">
                <strong>Updated By : </strong>
                {{ row.item.updated_by }}
            </b-col>
          </b-row>

          <!-- <b-button
              size="sm"
              variant="outline-secondary"
              @click="row.toggleDetails"
            >
              Hide Details
            </b-button> -->
        </b-card>
      </template>


      <template #cell(client_name)="data">

        {{ data.item.client_name }}
        </br>
        {{ data.item.client_name_ar }}

      </template>

      <template #cell(contract_status)="data">
        <b-badge
          pill
          :variant="statusVariant(data.value)"
        >
          {{ data.value }}
        </b-badge>
      </template>

      <template #cell(passport)="data">

        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #cell(passport_2)="data">

        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #cell(ajeer)="data">

        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #cell(insurance_card)="data">

        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img
            thumbnail
            :src="data.value"
          />
        </enlargeable-image>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <b-spinner class="align-middle" />
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
      />
    </div>
  </div>

</template>

<script>
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import axios from '@axios'
import vSelect from 'vue-select'
import {
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile, BFormTextarea, BFormRadio, BForm, BFormCheckboxGroup, BFormRating, BListGroupItem, BListGroup, BPopover, BSpinner,
} from 'bootstrap-vue'

import Ripple from 'vue-ripple-directive'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      popoverShow: false,
      exportLimit: '',
      isBusy: true,
      fields: [{ key: 'Options', thClass: 'customHead' }, {
        key: 'client_code', label: 'Client Code', sortable: true, thClass: 'customHead',
      }, { key: 'client_name', sortable: true, thClass: 'customHead' }, {
        key: 'contract_status', label: 'Status', sortable: true, thClass: 'customHead',
      }],
      items: [
      ],
      serviceFields: ['name', 'period', 'rate'],
      LocationFields: ['location_name' , 'location_map'],
      emp_detail: [
        { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
      ],
      /* eslint-disable global-require */
      status: [{
        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',
      },
      {
        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',
      }],

      empName: '',
      empId: '',
      empNationality: '',
      empReligion: '',
      empDob: '',
      empAge: '',
      empJoiningDate: '',
      empMobileNumber: '',
      empProfilePhoto: '',
      searchTerm: '',
      pageSize: 10,
      page: 1,
      count: 0,

      formValues: {

        name: '',
        emp_id: '',
        nationality: 'select_value',
        religion: 'select_value',
        dob: '',
        age: '',
        contact_number: '',
        joining_date: '',
        emp_photo: null,

        benefits: '',
        iban: '',
        vacation_date: '',
        notes: '',

        iqama_no: '',
        iqama_expiry_date: '',
        iqama_profession: 'select_value',
        iqama: null,
        passport_number: '',
        passport_expiry_date: '',
        passport: null,
        passport_2: null,
        ajeer: null,
        ajeer_expiration_date: '',
        insurance_card: null,
        insurance_card_expirationDate: '',

        vendor: 'select_value',
        salary_rate: '',

        client: '',
        status: '',
        accommodation: '',
        contract_start: '',
        project_stop_date: '',

        lang_eng: null,
        lang_ar: null,
        lang_hind: null,
        appearance_presentable: null,
        apprearance_beard: '',
        skills: [],
        misconduct: '',

      },

      nationalities: [
        { value: 'select_value', text: 'Select Value' },
        { value: 'Bangladesh', text: 'Bangladesh' },
        { value: 'India', text: 'India' },
        { value: 'Pakistan', text: 'Pakistan' },
        { value: 'Nepal', text: 'Nepal' },
        { value: 'Sirilanka', text: 'Sirilanka' },
        { value: 'Philippine', text: 'Philippine' },
        { value: 'Sudan', text: 'Sudan' },
      ],
      religions: [
        { value: 'select_value', text: 'Select Value' },
        { value: 'Islam', text: 'Islam' },
        { value: 'Hindu', text: 'Hindu' },
        { value: 'Buddhist', text: 'Buddhist' },
        { value: 'Christian', text: 'Christian' },
        { value: 'Other', text: 'Other' },
      ],
      professions: [],
      vendors: [],
      clients: [],
      statuses: [
        { value: 'select_value', text: 'Select Value' },
        { value: 'Deployed', text: 'Deployed' },
        { value: 'Terminated', text: 'Terminated' },
      ],
      accommodations: [],

      lang_engs: [
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' },
      ],
      lang_ars: [
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' },
      ],
      lang_hinds: [
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' },
      ],
      appearance_presentables: [
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' },
      ],

    }
  },

  mounted() {
    this.getEmployees()
    this.getVendors()
    this.getClients()
    this.getProfessions()
    this.getAccommodations()
  },
  methods: {

    onClose() {
      this.popoverShow = false
    },

    statusVariant(period) {
      if (period == 'Monthly') {
        return 'primary'
      }
      if (period == 'Active') {
        return 'success'
      }
      if (period == 'Terminated') {
        return 'danger'
      }
      if (period == 'Under-Signing') {
        return 'warning'
      }
      if (period == 'Expired') {
        return 'secondary'
      }

      return 'light'
    },

    deleteEmployee(client_code) {
      this.$swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-danger',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if (result.value) {
          axios.get('/deleteClient', {
            params: {
              client_code,
            },
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getEmployees()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Client Deleted',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Client deleted')
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
          }).catch(error => {
            console.error(error)
          })
        }
      })
    },

    isPdf(url) {
      if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
        return true
      }

      return false
    },

    getEmployees() {
      this.isBusy = true

      axios.get('/getClients', {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,
          advanceSearch: this.formValues,
        },
      })

        .then(response => {
          console.log('response', response.data.data)
          this.items = response.data.data
          this.count = response.data.totalRows
          this.isBusy = false

          // TODO
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

    handlePageChange(value) {
      this.page = value
      this.isBusy = true
      this.getEmployees()
    },

    searchTimeOut() {
      let timeout = null
      clearTimeout(timeout)
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1
        this.getEmployees()
      }, 800)
    },

    getVendors() {
      axios.get('/vendorsDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.vendors = response.data.data

            console.log('Vendors Fetched')
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
      }).catch(error => {
        console.error(error)
      })
    },
    getClients() {
      axios.get('/clientsDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.clients = response.data.data

            console.log('Clients Fetched')
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
      }).catch(error => {
        console.error(error)
      })
    },
    getAccommodations() {
      axios.get('/accommodationsDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.accommodations = response.data.data

            console.log('accommodations Fetched')
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
      }).catch(error => {
        console.error(error)
      })
    },

    getProfessions() {
      axios.get('/professionsDropdown').then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
            this.professions = response.data.data

            console.log('professions Fetched')
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
      }).catch(error => {
        console.error(error)
      })
    },

    /* getEmployeeDetail(item){
        console.log(item.item);
          axios.get('/getEmployeeDetail', {
            params: {
              emp_id: item.emp_id,
            },
          }).then((response) => {

            if(response.data.hasOwnProperty('success'))
            {
              if(response.data.success === true)
              {

              }
              else
              {
                this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `Error`,
                      icon: 'AlertCircleIcon',
                      variant: 'danger',
                      text: `Something went wrong, try again later`,
                    },
                  })
              }

            }
            else
            {
              this.$toast({
                    component: ToastificationContent,
                    position: 'top-right',
                    props: {
                      title: `Error`,
                      icon: 'AlertCircleIcon',
                      variant: 'danger',
                      text: `Something went wrong, try again later`,
                    },
                  })
            }

          }).catch((error) => {
            console.error(error);
          });
      } */

  },
}
</script>

<style lang="css" >

.enlargeable-image .full  {
  z-index:9999 !important;

  background-color: rgba(0,0,0,0.5) !important;
}

.customHead{

  background-color: #06608f !important;
  color:#fff;
}

</style>
