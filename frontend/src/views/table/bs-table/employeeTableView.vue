//empluee table view

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
                @click="(page = 1), getEmployees()"
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

      <b-form-group v-if="$ability.can('add/copy', 'employees')">
        <div class="d-flex align-items-center">
          <b-input-group>
            <b-input-group-prepend>
              <b-button
                ref="button"
                v-b-modal.modal-export
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                title="Export"
                variant="outline-primary"
              >
                <feather-icon icon="DownloadIcon" />
              </b-button>
            </b-input-group-prepend>

            <b-input-group-prepend>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                :to="{ name: 'add-employee' }"
                title="Add Employee"
              >
                ADD+
              </b-button>
            </b-input-group-prepend>
          </b-input-group>
        </div>
      </b-form-group>
    </div>

    <!-- Export Model -->

    <b-modal
      id="modal-export"
      ref="exportModel"
      hide-footer
      title="Export Table"
      size="lg"
      :no-close-on-backdrop="true"
    >
      <b-form @submit.prevent>
        <b-row>
          <b-col md="6">
            <b-form-group label="Search Filter">
              <b-form-input v-model="searchTerm" placeholder="" />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Iqama Expiry Date">
              <b-form-datepicker v-model="iqama_expiry_date" />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Vendor">
              <v-select
                v-model="vendor"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="vendors"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Client">
              <v-select
                v-model="client"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clients"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Status">
              <v-select
                v-model="exportStatus"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="statuses"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Records Limit">
              <b-form-input v-model="exportLimit" placeholder="" />
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              :href="
                '/api/employeeCsv?searchTerm=' +
                searchTerm +
                ' &amp;limit=' +
                exportLimit +
                ' &amp;iqama_expiry_date=' +
                iqama_expiry_date +
                ' &amp;vendor=' +
                vendor +
                ' &amp;client=' +
                client +
                ' &amp;status=' +
                exportStatus
              "
              target="_blank"
              @click="closeExpot"
            >
              <span>Export</span>
            </b-button>

            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="outline-primary"
              class="mr-1"
              @click="resetExport"
            >
              <span>Reset</span>
            </b-button>

            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="outline-secondary"
              class="mr-1"
              @click="closeExpot"
            >
              <span>Cancel</span>
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>

    <!-- Advance Search Model -->
    <b-modal
      id="modal-advancesearch"
      ref="searchModel"
      hide-footer
      title="Advance Search"
      size="lg"
      cancel-variant="outline-secondary"
    >
      <b-form @submit.prevent>
        <b-row>
          <b-col md="6">
            <b-form-group>
              <label for="name">
                Enter Name
                <small class="text-muted"> Contains. </small>
              </label>

              <b-form-input
                id="name"
                v-model="formValues.name"
                placeholder="Enter name"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="GL ID" label-for="emp_id">
              <b-form-input
                id="emp_id"
                v-model="formValues.emp_id"
                placeholder=""
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Nationality" label-for="v-nationality">
              <v-select
                id="v-nationality"
                v-model="formValues.nationality"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="nationalities"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Religion" label-for="v-religion">
              <v-select
                id="v-religion"
                v-model="formValues.religion"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="religions"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Date of Birth" label-for="v-dob">
              <b-form-datepicker
                id="v-dob"
                v-model="formValues.dob"
                class="mb-1"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Age" label-for="v-age">
              <b-form-input
                id="v-age"
                v-model="formValues.age"
                placeholder="Enter age"
                type="number"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Joining date" label-for="v-joining_date">
              <b-form-datepicker
                id="v-joining_date"
                v-model="formValues.joining_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Mobile Number" label-for="v-contact_number">
              <b-form-input
                id="v-contact_number"
                v-model="formValues.contact_number"
                placeholder="Enter Mobile number"
                type="number"
              />
            </b-form-group>
          </b-col>

          <!-- <b-col md="6">
              <b-form-group
                label="Profile Photo"
                label-for="v-emp_photo"
              >

                <b-form-file
                  v-model="formValues.emp_photo"
                  id="v-emp_photo"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->

          <b-col md="12">
            <b-form-group label="Benefits Details" label-for="v-benefits">
              <b-form-textarea
                id="v-benefits"
                v-model="formValues.benefits"
                placeholder="Benefits Detail"
                rows="3"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="IBAN (NCB)" label-for="v-iban">
              <b-form-input
                id="v-iban"
                v-model="formValues.iban"
                type="text"
                placeholder="IBAN number"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Vacation Due Date" label-for="v-vacation_date">
              <b-form-datepicker
                id="v-vacation_date"
                v-model="formValues.vacation_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>
          <b-col md="12">
            <b-form-group label-for="v-notes" label="Notes">
              <b-form-textarea
                id="v-notes"
                v-model="formValues.notes"
                placeholder="Notes"
                rows="3"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Iqama Number" label-for="v-iqama_no">
              <b-form-input
                id="v-iqama_no"
                v-model="formValues.iqama_no"
                placeholder="Iqama Number"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Iqama Expiry Date"
              label-for="v-iqama_expiry_date"
            >
              <b-form-datepicker
                id="v-iqama_expiry_date"
                v-model="formValues.iqama_expiry_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Iqama Profession"
              label-for="v-iqama_profession"
            >
              <v-select
                id="v-iqama_profession"
                v-model="formValues.iqama_profession"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="professions"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>
          <!-- <b-col md="6">
              <b-form-group
                label="Iqama Upload"
                label-for="v-iqama"
              >
              <b-form-file
                  v-model="formValues.iqama"
                  id="v-iqama"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->
          <b-col md="6">
            <b-form-group label="Passport Number" label-for="v-passport_number">
              <b-form-input
                id="v-passport_number"
                v-model="formValues.passport_number"
                placeholder="Passport Number"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Passport Expiry Date"
              label-for="v-passport_expiry_date"
            >
              <b-form-datepicker
                id="v-passport_expiry_date"
                v-model="formValues.passport_expiry_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>
          <!-- <b-col md="6">
              <b-form-group
                label="Passport File"
                label-for="v-passport"
              >
              <b-form-file
                  v-model="formValues.passport"
                  id="v-passport"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->
          <!-- <b-col md="6">
              <b-form-group
                label="Visa File"
                label-for="v-passport_2"
              >
              <b-form-file
                  v-model="formValues.passport_2"
                  id="v-passport_2"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->
          <!-- <b-col md="6">
              <b-form-group
                label="AJEER File"
                label-for="v-ajeer"
              >
              <b-form-file
                  v-model="formValues.ajeer"
                  id="v-ajeer"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->
          <b-col md="6">
            <b-form-group
              label="AJEER Expiry Date"
              label-for="v-ajeer_expiration_date"
            >
              <b-form-datepicker
                id="v-ajeer_expiration_date"
                v-model="formValues.ajeer_expiration_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>
          <!-- <b-col md="6">
              <b-form-group
                label="Medical Insurance File"
                label-for="v-insurance_card"
              >
              <b-form-file
                  v-model="formValues.insurance_card"
                  id="v-insurance_card"
                  accept="image/* , .pdf"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col> -->
          <b-col md="6">
            <b-form-group
              label="Medical Insurance Expiry Date"
              label-for="v-insurance_card_expirationDate"
            >
              <b-form-datepicker
                id="v-insurance_card_expirationDate"
                v-model="formValues.insurance_card_expirationDate"
                class="mb-1"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Vendor" label-for="v-vendor">
              <v-select
                id="v-vendor"
                v-model="formValues.vendor"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="vendors"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Basic Salary" label-for="v-salary_rate">
              <b-form-input
                id="v-salary_rate"
                v-model="formValues.salary_rate"
                placeholder="Basic Salary"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Client" label-for="v-client">
              <v-select
                id="v-client"
                v-model="formValues.client"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clients"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Status" label-for="v-status">
              <v-select
                id="v-status"
                v-model="formValues.status"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="statuses"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
                :reduce="(text) => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="accommodation" label-for="v-accommodation">
              <v-select
                id="v-accommodation"
                v-model="formValues.accommodation"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="accommodations"
                :selectable="(option) => !option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Start Date" label-for="v-contract_start">
              <b-form-datepicker
                id="v-contract_start"
                v-model="formValues.contract_start"
                class="mb-1"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Stop Date" label-for="v-project_stop_date">
              <b-form-datepicker
                id="v-project_stop_date"
                v-model="formValues.project_stop_date"
                class="mb-1"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="English Language" label-for="v-lang_eng">
              <!-- <v-select
                    id="v-lang_eng"
                    v-model="formValues.lang_eng"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="lang_engs"
                    :selectable="option => ! option.value.includes('select_value')"
                    label="text"
                  /> -->
              <b-form-rating
                v-model="formValues.lang_eng"
                no-border
                variant="warning"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Arabic Language" label-for="v-lang_ar">
              <!-- <v-select
                    id="v-lang_ar"
                    v-model="formValues.lang_ar"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="lang_ars"
                    :selectable="option => ! option.value.includes('select_value')"
                    label="text"
                  /> -->

              <b-form-rating
                v-model="formValues.lang_ar"
                no-border
                variant="warning"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Hindi Language" label-for="v-lang_hind">
              <!-- <v-select
                    id="v-lang_hind"
                    v-model="formValues.lang_hind"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="lang_hinds"
                    :selectable="option => ! option.value.includes('select_value')"
                    label="text"
                  /> -->

              <b-form-rating
                v-model="formValues.lang_hind"
                no-border
                variant="warning"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Presentable Rating"
              label-for="v-appearance_presentable"
            >
              <!-- <v-select
                    id="v-appearance_presentable"
                    v-model="formValues.appearance_presentable"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="appearance_presentables"
                    :selectable="option => ! option.value.includes('select_value')"
                    label="text"
                  /> -->
              <b-form-rating
                v-model="formValues.appearance_presentable"
                no-border
                variant="warning"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Beard?">
              <div class="demo-inline-spacing">
                <b-form-radio
                  v-model="formValues.apprearance_beard"
                  name="v-apprearance_beard"
                  value="yes"
                >
                  Yes
                </b-form-radio>
                <b-form-radio
                  v-model="formValues.apprearance_beard"
                  name="v-apprearance_beard"
                  value="no"
                >
                  No
                </b-form-radio>
              </div>
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-form-group label="Skills" label-for="v-skills">
              <b-form-checkbox-group
                id="checkbox-group-2"
                v-model="formValues.skills"
                name="Skills"
                class="demo-inline-spacing"
              >
                <b-form-checkbox value="Forklift"> Forklift </b-form-checkbox>
                <b-form-checkbox value="Waiter"> Waiter </b-form-checkbox>
                <b-form-checkbox value="Kitchen Helper">
                  Kitchen Helper
                </b-form-checkbox>
                <b-form-checkbox value="Electrician">
                  Electrician
                </b-form-checkbox>
                <b-form-checkbox value="Plumber"> Plumber </b-form-checkbox>
                <b-form-checkbox value="AC technician">
                  AC technician
                </b-form-checkbox>
              </b-form-checkbox-group>
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-form-group label="Misconduct Report" label-for="v-misconduct">
              <b-form-textarea
                id="v-misconduct"
                v-model="formValues.misconduct"
                placeholder="Misconduct Report"
                rows="3"
              />
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="
                (page = 1), (searchTerm = ''), closeSearch(), getEmployees()
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
              v-if="$ability.can('edit', 'employees')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'Employee-edit', params: { empId: row.item.emp_id }}"
              variant="flat-success"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="EditIcon" />
            </b-button>

            <b-button
              v-if="$ability.can('delete', 'employees')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-danger"
              class="btn-icon rounded-circle"
              @click="deleteEmployee(row.item.emp_id)"
            >
              <feather-icon icon="Trash2Icon" />
            </b-button>
            <b-button
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-primary"
              class="btn-icon rounded-circle"
            >
              <a :href="`tel:+${ row.item.contact_number }`"><feather-icon icon="PhoneIcon" /></a>

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

              <b-input-group-append v-if="$ability.can('edit', 'employees')">
                <b-button
                  size="sm"
                  :to="{
                    name: 'Employee-edit',
                    params: { empId: row.item.emp_id },
                  }"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-success"
                >
                  <feather-icon icon="EditIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append v-if="$ability.can('delete', 'employees')">
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-danger"
                  @click="deleteDocument(row.item.id)"
                >
                  <feather-icon icon="Trash2Icon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append>
                <b-button
                  size="sm"
                  v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                  variant="outline-primary"
                >
                  <a :href="`tel:+${row.item.contact_number}`"
                    ><feather-icon icon="PhoneIcon"
                  /></a>
                </b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-form-group>
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card no-body>
          <b-row>
            <b-col cols="12">
              <b-tabs pills card vertical>
                <b-tab title="Employee Details" active>
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Name</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.name }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Emp ID</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.emp_id }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Nationality</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.nationality }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Religion</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.religion }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Date of Birth</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.dob | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Age</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.age }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Joining Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.joining_date | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Mobile Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contact_number }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Profile Photo</strong>
                      </b-col>
                      <b-col v-if="isPdf(row.item.emp_photo)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.emp_photo"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>
                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.emp_photo"
                          :src_large="row.item.emp_photo"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.emp_photo"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Benefits">
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Benefits Details</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.benefits }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>IBAN (NCB)</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.iban }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Vacation Due Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.vacation_date | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Notes</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.notes }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Legal Details">
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Iqama Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.iqama_no }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Iqama Expiry Date</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-badge
                          pill
                          :variant="row.item.iqamaExpireVarient['dateVariant']"
                        >
                          {{
                            row.item.iqamaExpireVarient["iqama_expiry_date"]
                              | moment("DD/MM/YYYY")
                          }}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Iqama Profession</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.iqama_profession }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Iqama Upload</strong>
                      </b-col>

                      <b-col v-if="isPdf(row.item.iqama)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.iqama"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>
                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.iqama"
                          :src_large="row.item.iqama"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.iqama"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Passport Number</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.passport_number }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Passport Expiry Date</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-badge
                          pill
                          :variant="
                            row.item.passportExpireVarient['dateVariant']
                          "
                        >
                          {{
                            row.item.passportExpireVarient[
                              "passport_expiry_date"
                            ] | moment("DD/MM/YYYY")
                          }}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Passport File</strong>
                      </b-col>
                      <b-col v-if="isPdf(row.item.passport)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.passport"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>
                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.passport"
                          :src_large="row.item.passport"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.passport"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Visa File</strong>
                      </b-col>
                      <b-col v-if="isPdf(row.item.passport_2)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.passport_2"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>
                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.passport_2"
                          :src_large="row.item.passport_2"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.passport_2"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>AJEER File</strong>
                      </b-col>
                      <b-col v-if="isPdf(row.item.ajeer)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.ajeer"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>
                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.ajeer"
                          :src_large="row.item.ajeer"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.ajeer"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>AJEER Expiration Date</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-badge
                          pill
                          :variant="row.item.ajeerExpireVarient['dateVariant']"
                        >
                          {{
                            row.item.ajeerExpireVarient["ajeer_expiration_date"]
                              | moment("DD/MM/YYYY")
                          }}
                        </b-badge>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Medical Insurance File</strong>
                      </b-col>
                      <b-col v-if="isPdf(row.item.insurance_card)" cols="10">
                        <a
                          class="btn btn-primary"
                          :href="row.item.insurance_card"
                          target="_blank"
                          >View file</a
                        >
                      </b-col>

                      <b-col v-else cols="10">
                        <enlargeable-image
                          :src="row.item.insurance_card"
                          :src_large="row.item.insurance_card"
                          animation_duration="600"
                        >
                          <b-img
                            style="max-height: 80px"
                            thumbnail
                            :src="row.item.insurance_card"
                          />
                        </enlargeable-image>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Medical Insurance Expiry Date</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-badge
                          pill
                          :variant="
                            row.item.insuranceExpireVarient['dateVariant']
                          "
                        >
                          {{
                            row.item.insuranceExpireVarient[
                              "insurance_card_expirationDate"
                            ] | moment("DD/MM/YYYY")
                          }}
                        </b-badge>
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Vendor Details">
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Vendor</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.vendor }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Basic Salary</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ Number(row.item.salary_rate).toLocaleString() }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Client Details">
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Client</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.client }}
                      </b-col>
                    </b-row>
                     <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Location</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.client_location }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Status</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.status }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Accommodation</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.accommodation }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Start Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.contract_start | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Stop Date</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.project_stop_date | moment("DD/MM/YYYY") }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>

                <b-tab title="Employee Review">
                  <b-card-text>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>English Language</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-form-rating
                          no-border
                          :value="row.item.lang_eng"
                          variant="warning"
                          readonly
                          inline
                        />
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Arabic Language</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-form-rating
                          no-border
                          :value="row.item.lang_ar"
                          variant="warning"
                          readonly
                          inline
                        />
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Hindi Language</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-form-rating
                          no-border
                          :value="row.item.lang_hind"
                          variant="warning"
                          readonly
                          inline
                        />
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Presentable rating</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-form-rating
                          no-border
                          :value="row.item.appearance_presentable"
                          variant="warning"
                          readonly
                          inline
                        />
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Beard?</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.apprearance_beard == 1 ? "Yes" : "No" }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Skills</strong>
                      </b-col>
                      <b-col cols="10">
                        <b-list-group horizontal>
                          <b-list-group-item
                            v-for="oneSkill in row.item.skills"
                            :key="oneSkill"
                          >
                            {{ oneSkill }}
                          </b-list-group-item>
                        </b-list-group>
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Misconduct Report</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.misconduct }}
                      </b-col>
                    </b-row>
                    <b-row class="border-bottom border-left">
                      <b-col cols="2">
                        <strong>Overall Rating</strong>
                      </b-col>
                      <b-col cols="10">
                        {{ row.item.rating }}
                      </b-col>
                    </b-row>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12" class="text-right px-3">
              <strong>Created By : </strong>
              {{ row.item.created_by }}
            </b-col>
          </b-row>
          <b-row v-if="row.item.updated_by">
            <b-col cols="12" class="text-right px-3">
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

      <!-- <template #cell(iqama)="data">
          <b-avatar :src="data.value" />
        </template> -->

      <template #cell(iqama)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(passport)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(passport_2)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(ajeer)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(insurance_card)="data">
        <enlargeable-image
          :src="data.value"
          :src_large="data.value"
          animation_duration="600"
        >
          <b-img thumbnail :src="data.value" />
        </enlargeable-image>
      </template>

      <template #cell(iqamaExpireVarient)="data">
        <b-badge pill :variant="data.value['dateVariant']">
          {{ data.value["iqama_expiry_date"] | moment("DD/MM/YYYY") }}
        </b-badge>
      </template>

      <template #cell(status)="data">
        <b-badge pill :variant="statusVariant(data.value)">
          {{ data.value }}
        </b-badge>
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

  <!-- <template #code>
      {{ codeRowDetailsSupport }}
    </template> -->
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
  BSpinner,
} from "bootstrap-vue";

import Ripple from "vue-ripple-directive";
import EnlargeableImage from "@diracleo/vue-enlargeable-image";
import { initialAbility } from "@/libs/acl/config";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { codeRowDetailsSupport } from "./code";

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
      exportLimit: "",
      iqama_expiry_date: "",
      vendor: "",
      client: "",
      exportStatus: "",

      isBusy: true,
      fields: [
        { key: "Options", thClass: "customHead" },
        {
          key: "emp_id",
          label: "GL ID",
          sortable: true,
          thClass: "customHead",
        },
        { key: "name", sortable: true, thClass: "customHead" },
        { key: "iqama_no", sortable: true, thClass: "customHead" },
        {
          key: "iqamaExpireVarient",
          label: "Iqama Expiry",
          sortable: true,
          thClass: "customHead",
        },
        { key: "vendor", sortable: true, thClass: "customHead" },
        { key: "client", sortable: true, thClass: "customHead" },
        { key: "status", sortable: true, thClass: "customHead" },
      ],
      items: [],
      emp_detail: [
        { age: 40, first_name: "Dickerson", last_name: "Macdonald" },
      ],
      /* eslint-disable global-require */
      status: [
        {
          1: "Current",
          2: "Professional",
          3: "Rejected",
          4: "Resigned",
          5: "Applied",
        },
        {
          1: "light-primary",
          2: "light-success",
          3: "light-danger",
          4: "light-warning",
          5: "light-info",
        },
      ],
      codeRowDetailsSupport,

      empName: "",
      empId: "",
      empNationality: "",
      empReligion: "",
      empDob: "",
      empAge: "",
      empJoiningDate: "",
      empMobileNumber: "",
      empProfilePhoto: "",
      searchTerm: "",
      pageSize: 10,
      page: 1,
      count: 0,

      formValues: {
        name: "",
        emp_id: "",
        nationality: "",
        religion: "",
        dob: "",
        age: "",
        contact_number: "",
        joining_date: "",
        /* emp_photo:null, */

        benefits: "",
        iban: "",
        vacation_date: "",
        notes: "",

        iqama_no: "",
        iqama_expiry_date: "",
        iqama_profession: "",
        /* iqama:null, */
        passport_number: "",
        passport_expiry_date: "",
        /* passport:null,
        passport_2:null,
        ajeer:null, */
        ajeer_expiration_date: "",
        /* insurance_card:null, */
        insurance_card_expirationDate: "",

        vendor: "",
        salary_rate: "",

        client: "",
        status: "",
        accommodation: "",
        contract_start: "",
        project_stop_date: "",

        lang_eng: null,
        lang_ar: null,
        lang_hind: null,
        appearance_presentable: null,
        apprearance_beard: "",
        skills: [],
        misconduct: "",
      },

      nationalities: [
        { value: "select_value", text: "Select Value" },
        { value: "Bangladesh", text: "Bangladesh" },
        { value: "India", text: "India" },
        { value: "Pakistan", text: "Pakistan" },
        { value: "Nepal", text: "Nepal" },
        { value: "Sirilanka", text: "Sirilanka" },
        { value: "Philippine", text: "Philippine" },
        { value: "Sudan", text: "Sudan" },
      ],
      religions: [
        { value: "select_value", text: "Select Value" },
        { value: "Islam", text: "Islam" },
        { value: "Hindu", text: "Hindu" },
        { value: "Buddhist", text: "Buddhist" },
        { value: "Christian", text: "Christian" },
        { value: "Other", text: "Other" },
      ],
      professions: [],
      vendors: [],
      clients: [],
      statuses: [
        { value: "select_value", text: "Select Value" },
        { value: "Available", text: "Available" },
        { value: "Deployed", text: "Deployed" },
        { value: "Runaway", text: "Runaway" },
        { value: "Exit", text: "Exit" },
        { value: "Terminated", text: "Terminated" },
        { value: "Vacation", text: "Vacation" },
        { value: "Return to Vendor", text: "Return to Vendor" },
      ],
      accommodations: [],

      lang_engs: [
        { value: "1", text: "1" },
        { value: "2", text: "2" },
        { value: "3", text: "3" },
        { value: "4", text: "4" },
        { value: "5", text: "5" },
      ],
      lang_ars: [
        { value: "1", text: "1" },
        { value: "2", text: "2" },
        { value: "3", text: "3" },
        { value: "4", text: "4" },
        { value: "5", text: "5" },
      ],
      lang_hinds: [
        { value: "1", text: "1" },
        { value: "2", text: "2" },
        { value: "3", text: "3" },
        { value: "4", text: "4" },
        { value: "5", text: "5" },
      ],
      appearance_presentables: [
        { value: "1", text: "1" },
        { value: "2", text: "2" },
        { value: "3", text: "3" },
        { value: "4", text: "4" },
        { value: "5", text: "5" },
      ],
    };
  },

  mounted() {
    this.getEmployees();
    this.getVendors();
    this.getClients();
    this.getProfessions();
    this.getAccommodations();
  },
  methods: {
    closeSearch() {
      this.$refs.searchModel.hide();
    },
    closeExpot() {
      this.$refs.exportModel.hide();
    },

    resetExport() {
      this.exportLimit = "";
      this.iqama_expiry_date = "";
      this.vendor = "";
      this.client = "";
      this.status = "";
      this.searchTerm = "";
    },

    resetSearch() {
      Object.entries(this.formValues).forEach(([key, value]) => {
        if (key == "skills") {
          this.formValues[key] = [];
        } else if (
          key == "lang_eng" ||
          key == "lang_ar" ||
          key == "lang_hind" ||
          key == "appearance_presentable"
        ) {
          this.formValues[key] = null;
        } else {
          this.formValues[key] = "";
        }
      });

      console.log(this.formValues);
    },

    onClose() {
      this.popoverShow = false;
    },

    statusVariant(status) {
      if (status == "Available") {
        return "warning";
      }
      if (status == "Deployed") {
        return "success";
      }
      if (status == "Runaway" || status == "Exit" || status == "Terminated") {
        return "danger";
      }
      if (status == "Vacation") {
        return "primary";
      }

      return "light";
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

    deleteEmployee(emp_id) {
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          axios
            .get("/deleteEmployee", {
              params: {
                emp_id,
              },
            })
            .then((response) => {
              if (response.data.hasOwnProperty("success")) {
                if (response.data.success === true) {
                  this.getEmployees();
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Employee Deleted",
                      icon: "EditIcon",
                      variant: "success",
                    },
                  });
                  console.log("Employee deleted");
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
            });
        }
      });
    },

    isPdf(url) {
      if (url.substr(url.lastIndexOf(".") + 1) == "pdf") {
        return true;
      }

      return false;
    },

    getEmployees() {
      // this.$ability.update(initialAbility)
      // console.log('abilities',this.$ability.can('add/copy', 'employees'))
      this.isBusy = true;
      axios
        .get("/getemployee", {
          params: {
            searchTerm: this.searchTerm,
            page_no: this.page,
            advanceSearch: this.formValues,
          },
        })

        .then((response) => {
          console.log("response", response.data.data);
          this.items = response.data.data;
          this.count = response.data.totalRows;
          this.isBusy = false;

          // TODO
        })
        .catch((error) => {
        console.log(error.response.status)
         this.httpHelper(error)
        });
    },

    handlePageChange(value) {
      this.page = value;
      this.isBusy = true;
      this.getEmployees();
    },

    searchTimeOut() {
      let timeout = null;
      clearTimeout(timeout);
      // Make a new timeout set to go off in 800ms
      timeout = setTimeout(() => {
        this.page = 1;
        this.getEmployees();
      }, 800);
    },

    getVendors() {
      axios
        .get("/vendorsDropdown")
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.vendors = response.data.data;

              console.log("Vendors Fetched");
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
        });
    },
    getClients() {
      axios
        .get("/clientsDropdown")
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.clients = response.data.data;

              console.log("Clients Fetched");
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
        });
    },
    getAccommodations() {
      axios
        .get("/accommodationsDropdown")
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.accommodations = response.data.data;

              console.log("accommodations Fetched");
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
        });
    },

    getProfessions() {
      axios
        .get("/professionsDropdown")
        .then((response) => {
          if (response.data.hasOwnProperty("success")) {
            if (response.data.success === true) {
              console.log(response.data.data);
              this.professions = response.data.data;

              console.log("professions Fetched");
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
        });
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
};
</script>

<style lang="css" >
.enlargeable-image .full {
  z-index: 9999 !important;

  background-color: rgba(0, 0, 0, 0.5) !important;
}

.customHead {
  background-color: #06608f !important;
  color: #fff;
}
</style>
