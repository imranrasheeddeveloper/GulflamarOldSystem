<template>
  <div>
    <form-wizard
      ref="wizard"
      color="#7367F0"
      :title="null"
      :subtitle="null"
      layout="vertical"
      finish-button-text="Submit"
      back-button-text="Previous"
      class="wizard-vertical mb-3"
      @on-complete="formSubmitted"
    >

      <!-- account datails tab -->
      <tab-content title="Account Details">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Employee Details
            </h5>
            <small class="text-muted">
              Enter Employee Account Details.
            </small>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Name"
              label-for="v-name"
            >
              <b-form-input
                id="v-name"
                v-model="formValues.name"
                placeholder="Enter name"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Nationality"
              label-for="v-nationality"
            >
              <v-select
                id="v-nationality"
                v-model="formValues.nationality"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="nationalities"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
                :reduce="text => text.value"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Religion"
              label-for="v-religion"
            >
              <v-select
                id="v-religion"
                v-model="formValues.religion"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="religions"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
                :reduce="text => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <label for="v-dob">Date of Birth</label>
            <b-input-group>

              <cleave
                id="date"
                v-model="formValues.dob"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.dob"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-dob"
                />
              </b-input-group-append>

            </b-input-group>
          </b-col>
          <!-- <b-col md="6">
            <b-form-group
              label="Age"
              label-for="v-age"
            >

              <b-form-input
                v-model="formValues.age"
                id="v-age"
                placeholder="Enter age"
                type="number"
                readonly
              />
            </b-form-group>
          </b-col> -->

          <b-col md="6">

            <label for="v-joining_date">Joining date</label>
            <b-input-group>

              <cleave
                id="v-joining_date"
                v-model="formValues.joining_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.joining_date"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-dob"
                />
              </b-input-group-append>

            </b-input-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Mobile Number"
              label-for="v-contact_number"
            >
              <cleave
                id="date"
                v-model="formValues.contact_number"
                class="form-control"
                :raw="false"
                :options="options.phone"
                placeholder="Enter Mobile number"
              />

            </b-form-group>
          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Profile Photo"
                  label-for="v-emp_photo"
                >

                  <b-form-file
                    id="v-emp_photo"
                    v-model="formValues.emp_photo"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <enlargeable-image
                  v-if="formValues.ui_emp_photo"
                  :src="formValues.ui_emp_photo"
                  :src_large="formValues.ui_emp_photo"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_emp_photo"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
      </tab-content>

      <!-- personal info tab -->
      <tab-content title="Benefits">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Benefits
            </h5>
            <small class="text-muted">Enter Employee Benefits.</small>
          </b-col>
          <b-col md="12">
            <b-form-group
              label="Benefits Details"
              label-for="v-benefits"
            >

              <b-form-textarea
                id="v-benefits"
                v-model="formValues.benefits"
                placeholder="Benefits Detail"
                rows="3"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="IBAN (NCB)"
              label-for="v-iban"
            >

              <cleave
                id="v-iban"
                v-model="formValues.iban"
                class="form-control"
                :raw="false"
                :options="options.iban"
                placeholder="Enter IBAN"
              />

            </b-form-group>
          </b-col>
          <b-col md="6">
            <label for="v-vacation_date">Vacation Due Date</label>
            <b-input-group>

              <cleave
                id="v-vacation_date"
                v-model="formValues.vacation_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.vacation_date"
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
            <b-form-group
              label-for="v-notes"
              label="Notes"
            >
              <b-form-textarea
                id="v-notes"
                v-model="formValues.notes"
                placeholder="Notes"
                rows="3"
              />
            </b-form-group>
          </b-col>
        </b-row>
      </tab-content>

      <!-- address -->
      <tab-content title="Legal Details">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Legal Details
            </h5>
            <small class="text-muted" />
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Iqama Number"
              label-for="v-iqama_no"
            >
              <cleave
                id="v-iqama_no"
                v-model="formValues.iqama_no"
                class="form-control"
                :raw="false"
                :options="options.iqama_number"
                placeholder="Iqama Number"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">

            <label for="v-iqama_expiry_date">Iqama Expiry Date</label>
            <b-input-group>

              <cleave
                id="v-iqama_expiry_date"
                v-model="formValues.iqama_expiry_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.iqama_expiry_date"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-iqama_expiry_date"
                />
              </b-input-group-append>

            </b-input-group>
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
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Iqama Upload"
                  label-for="v-iqama"
                >
                  <b-form-file

                    id="v-iqama"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                    @change="imageUpload($event,'iqama')"
                  />
                </b-form-group>
              </b-col>
              <b-col
                v-if="isPdf(formValues.ui_iqama)"
                md="6"
              >

                <a
                  class="btn btn-primary"
                  :href="formValues.ui_iqama"
                  target="_blank"
                >View file</a>
              </b-col>
              <b-col
                v-else
                md="6"
              >
                <enlargeable-image
                  v-if="formValues.ui_iqama"
                  :src="formValues.ui_iqama"
                  :src_large="formValues.ui_iqama"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_iqama"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Passport Number"
              label-for="v-passport_number"
            >
              <b-form-input
                id="v-passport_number"
                v-model="formValues.passport_number"
                placeholder="Passport Number"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <label for="v-passport_expiry_date">Passport Expiry Date</label>
            <b-input-group>

              <cleave
                id="v-passport_expiry_date"
                v-model="formValues.passport_expiry_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.passport_expiry_date"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-passport_expiry_date"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Passport File"
                  label-for="v-passport"
                >
                  <b-form-file
                    id="v-passport"
                    v-model="formValues.passport"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                </b-form-group>
              </b-col>
              <b-col
                v-if="isPdf(formValues.ui_passport)"
                md="6"
              >

                <a
                  class="btn btn-primary"
                  :href="formValues.ui_passport"
                  target="_blank"
                >View file</a>
              </b-col>
              <b-col
                v-else
                md="6"
              >
                <enlargeable-image
                  v-if="formValues.ui_passport"
                  :src="formValues.ui_passport"
                  :src_large="formValues.ui_passport"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_passport"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Visa File"
                  label-for="v-passport_2"
                >
                  <b-form-file
                    id="v-passport_2"
                    v-model="formValues.passport_2"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                </b-form-group>
              </b-col>
              <b-col
                v-if="isPdf(formValues.ui_passport_2)"
                md="6"
              >

                <a
                  class="btn btn-primary"
                  :href="formValues.ui_passport_2"
                  target="_blank"
                >View file</a>
              </b-col>
              <b-col
                v-else
                md="6"
              >
                <enlargeable-image
                  v-if="formValues.ui_passport_2"
                  :src="formValues.ui_passport_2"
                  :src_large="formValues.ui_passport_2"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_passport_2"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="AJEER File"
                  label-for="v-ajeer"
                >
                  <b-form-file
                    id="v-ajeer"
                    v-model="formValues.ajeer"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                </b-form-group>
              </b-col>
              <b-col
                v-if="isPdf(formValues.ui_ajeer)"
                md="6"
              >

                <a
                  class="btn btn-primary"
                  :href="formValues.ui_ajeer"
                  target="_blank"
                >View file</a>
              </b-col>
              <b-col
                v-else
                md="6"
              >
                <enlargeable-image
                  v-if="formValues.ui_ajeer"
                  :src="formValues.ui_ajeer"
                  :src_large="formValues.ui_ajeer"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_ajeer"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
          <b-col md="6">

            <label for="v-ajeer_expiration_date">AJEER Expiry Date</label>
            <b-input-group>

              <cleave
                id="v-ajeer_expiration_date"
                v-model="formValues.ajeer_expiration_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.ajeer_expiration_date"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-ajeer_expiration_date"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>
          <b-col md="12">
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Medical Insurance File"
                  label-for="v-insurance_card"
                >
                  <b-form-file
                    id="v-insurance_card"
                    v-model="formValues.insurance_card"
                    accept="image/* , .pdf"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                </b-form-group>
              </b-col>
              <b-col
                v-if="isPdf(formValues.ui_insurance_card)"
                md="6"
              >

                <a
                  class="btn btn-primary"
                  :href="formValues.ui_insurance_card"
                  target="_blank"
                >View file</a>
              </b-col>
              <b-col
                v-else
                md="6"
              >
                <enlargeable-image
                  v-if="formValues.ui_insurance_card"
                  :src="formValues.ui_insurance_card"
                  :src_large="formValues.ui_insurance_card"
                  animation_duration="600"
                >
                  <b-img
                    style="max-height: 80px;"
                    thumbnail
                    :src="formValues.ui_insurance_card"
                  />
                </enlargeable-image>
              </b-col>
            </b-row>
          </b-col>
          <b-col md="6">

            <label for="v-insurance_card_expirationDate">Medical Insurance Expiry Date</label>
            <b-input-group>

              <cleave
                id="v-insurance_card_expirationDate"
                v-model="formValues.insurance_card_expirationDate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.insurance_card_expirationDate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-insurance_card_expirationDate"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>
        </b-row>
      </tab-content>

      <!-- Vendor Details -->
      <tab-content title="Vendor Details">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Vendor Details
            </h5>
            <small class="text-muted" />
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Vendor"
              label-for="v-vendor"
            >
              <v-select
                id="v-vendor"
                v-model="formValues.vendor"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="vendors"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Basic Salary"
              label-for="v-salary_rate"
            >

              <b-input-group>
                <b-input-group-prepend is-text>
                  SR
                </b-input-group-prepend>
                <b-form-input
                  id="v-salary_rate"
                  v-model="formValues.salary_rate"
                  placeholder="Basic Salary"
                />
              </b-input-group>

            </b-form-group>
          </b-col>

        </b-row>
      </tab-content>

      <!-- Client Details -->
      <tab-content title="Client Details">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Client Details
            </h5>
            <small class="text-muted" />
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Client"
              label-for="v-client"
            >
              <v-select
                id="v-client"
                v-model="formValues.client"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clients"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
                @input="selected_client" >
              </v-select>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Location"
              label-for="v_location"
            >
              <v-select
                id="v_location"
                v-model="formValues.client_location"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clientLocations"
                label="text"
                :reduce="text => text.text"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Status"
              label-for="v-status"
            >
              <v-select
                id="v-status"
                v-model="formValues.status"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="statuses"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
                :reduce="text => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Accommodation"
              label-for="v-accommodation"
            >
              <v-select
                id="v-accommodation"
                v-model="formValues.accommodation"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="accommodations"
                :selectable="option => ! option.value.includes('select_value')"
                label="text"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">

            <label for="v-contract_start">Start Date</label>
            <b-input-group>

              <cleave
                id="v-contract_start"
                v-model="formValues.contract_start"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.contract_start"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-contract_start"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>

          <b-col md="6">

            <label for="v-project_stop_date">Stop Date</label>
            <b-input-group>

              <cleave
                id="v-project_stop_date"
                v-model="formValues.project_stop_date"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.project_stop_date"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="v-project_stop_date"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>

        </b-row>
      </tab-content>

      <!-- Employee Review -->
      <tab-content title="Employee Review">
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
          >
            <h5 class="mb-0">
              Employee Review
            </h5>
            <small class="text-muted" />
          </b-col>
          <b-col md="6">
            <b-form-group
              label="English Language"
              label-for="v-lang_eng"
            >
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
            <b-form-group
              label="Arabic Language"
              label-for="v-lang_ar"
            >
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
            <b-form-group
              label="Hindi Language"
              label-for="v-lang_hind"
            >
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
            <b-form-group
              label="Beard?"
              label-for="v-apprearance_beard"
            >
              <div class="demo-inline-spacing">
                <b-form-radio

                  v-model="formValues.apprearance_beard"
                  name="v-apprearance_beard"
                  value="1"
                >
                  Yes
                </b-form-radio>
                <b-form-radio

                  v-model="formValues.apprearance_beard"
                  name="v-apprearance_beard"
                  value="2"
                >
                  No
                </b-form-radio>

              </div>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Skills"
              label-for="v-skills"
            >
              <b-form-checkbox-group
                id="checkbox-group-2"
                v-model="formValues.skills"
                name="Skills"
                class="demo-inline-spacing"
              >
                <b-form-checkbox value="Forklift">
                  Forklift
                </b-form-checkbox>
                <b-form-checkbox value="Waiter">
                  Waiter
                </b-form-checkbox>
                <b-form-checkbox value="Kitchen Helper">
                  Kitchen Helper
                </b-form-checkbox>
                <b-form-checkbox value="Electrician">
                  Electrician
                </b-form-checkbox>
                <b-form-checkbox value="Plumber">
                  Plumber
                </b-form-checkbox>
                <b-form-checkbox value="AC technician">
                  AC technician
                </b-form-checkbox>

              </b-form-checkbox-group>
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-form-group
              label="Misconduct Report"
              label-for="v-misconduct"
            >

              <b-form-textarea
                id="v-misconduct"
                v-model="formValues.misconduct"
                placeholder="Misconduct Report"
                rows="3"
              />
            </b-form-group>
          </b-col>

        </b-row>
      </tab-content>

    </form-wizard>

  </div>
</template>

<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'

import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'

import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormDatepicker,
  BFormFile,
  BFormTextarea,
  BFormCheckbox,
  BFormRadio,
  BFormCheckboxGroup,
  BImg,
  BFormRating,
  BInputGroup,
  BInputGroupPrepend,
  BInputGroupAppend,
} from 'bootstrap-vue'
import axios from '@axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    FormWizard,
    TabContent,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormDatepicker,
    BFormFile,
    BFormTextarea,
    BFormCheckbox,
    BFormRadio,
    vSelect,
    BFormCheckboxGroup,
    BImg,
    EnlargeableImage,
    BFormRating,
    BInputGroup,
    BInputGroupPrepend,
    BInputGroupAppend,
    // eslint-disable-next-line vue/no-unused-components
    ToastificationContent,
    Cleave,
  },
  data() {
    return {
      options: {

        phone: {
          prefix: '9665',
          blocks: [12],
          uppercase: true,
          noImmediatePrefix: true,
        },
        date: {
          date: true,
          delimiter: '-',
          datePattern: ['Y', 'm', 'd'],
        },
        account_number: {
          blocks: [15],
          uppercase: true,
        },
        iqama_number: {
          blocks: [10],
          uppercase: true,
        },
        cr_number: {
          blocks: [10],
          uppercase: true,
        },
        iban: {
          prefix: 'SA',
          blocks: [24],
          uppercase: true,
          noImmediatePrefix: true,
        },

      },
      dobDate: '',
      isSubmitting: false,
      selectedContry: 'select_value',
      selectedLanguage: 'nothing_selected',
      countryName: [
        { value: 'select_value', text: 'Select Value' },
        { value: 'Russia', text: 'Russia' },
        { value: 'Canada', text: 'Canada' },
        { value: 'China', text: 'China' },
        { value: 'United States', text: 'United States' },
        { value: 'Brazil', text: 'Brazil' },
        { value: 'Australia', text: 'Australia' },
        { value: 'India', text: 'India' },
      ],
      languageName: [
        { value: 'nothing_selected', text: 'Nothing Selected' },
        { value: 'English', text: 'English' },
        { value: 'Chinese', text: 'Mandarin Chinese' },
        { value: 'Hindi', text: 'Hindi' },
        { value: 'Spanish', text: 'Spanish' },
        { value: 'Arabic', text: 'Arabic' },
        { value: 'Malay', text: 'Malay' },
        { value: 'Russian', text: 'Russian' },
      ],
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
        { value: 'Available', text: 'Available' },
        { value: 'Deployed', text: 'Deployed' },
        { value: 'Runaway', text: 'Runaway' },
        { value: 'Exit', text: 'Exit' },
        { value: 'Terminated', text: 'Terminated' },
        { value: 'Vacation', text: 'Vacation' },
        { value: 'Return to Vendor', text: 'Return to Vendor' },
      ],
      clientLocations: [],
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
      formValues: {

        name: '',
        nationality: '',
        religion: '',
        dob: '',

        contact_number: '',
        joining_date: '',
        emp_photo: null,
        ui_emp_photo: '',

        benefits: '',
        iban: '',
        vacation_date: '',
        notes: '',

        iqama_no: '',
        iqama_expiry_date: '',
        iqama_profession: '',
        iqama: null,
        passport_number: '',
        passport_expiry_date: '',
        passport: null,
        passport_2: null,
        ajeer: null,
        ajeer_expiration_date: '',
        insurance_card: null,
        insurance_card_expirationDate: '',

        vendor: '',
        salary_rate: '',

        client: '',
        client_location: '',
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

        ui_iqama: '',
        ui_passport_2: '',
        ui_passport: '',
        ui_insurance_card: '',
        ui_ajeer: '',
        

      },
    }
  },

  created() {
    this.getVendors()
    this.getClients()
    this.getProfessions()
    this.getAccommodations()
  },

  mounted() {
    const { wizard } = this.$refs
    wizard.activateAll()
  },
  methods: {

    dobContext(ctx) {
      this.formValues.dob = ctx.selectedYMD
    },

    addLoading() {
      const accordionItems = [...document.getElementsByClassName('wizard-btn')]

      accordionItems.forEach(accordionItem => {
        accordionItem.classList.add('disabled')
        accordionItem.setAttribute('disabled', 'disabled')
        accordionItem.innerHTML = '<span>Please Wait</span>'
      })
    },
    removeLoading() {
      const accordionItems = [...document.getElementsByClassName('wizard-btn')]

      accordionItems.forEach(accordionItem => {
        accordionItem.classList.remove('disabled')
        accordionItem.removeAttribute('disabled')
        accordionItem.innerHTML = '<span>Submit</span>'
      })
    },
    formSubmitted() {
      this.addLoading()

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
      axios.post('/createEmployee',
        formData).then(response => {
        console.log(response)
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            this.$router.replace('/employee')
              .then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Employee Created',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
              })
          } else {
            this.removeLoading()
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
          this.removeLoading()
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
    isPdf(url) {
      if (url.substr(url.lastIndexOf('.') + 1) == 'pdf') {
        return true
      }

      return false
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

    imageUpload(e, eName) {
      console.log(`formValues.${eName}`)
      this.formValues[eName] = e.target.files[0]
      console.log(this.formValues.iqama)
    },

    selected_client(item) {
            axios.get('/getClientLocations', {
        params: {
          client_code: item.value,
          
        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data.location_name)
            this.clientLocations = response.data.data
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
}
</script>

<style lang="css" >

.enlargeable-image .full  {
  z-index:9999 !important;

  background-color: rgba(0,0,0,0.5) !important;
}

.wizard-icon-container{

  background-color: #06608f !important;
}

.stepTitle.active{

  color: #06608f !important;
}

.wizard-btn{

  background-color: #06608f !important;
}

</style>
