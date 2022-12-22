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

            <b-input-group-append>
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                v-b-modal.modal-advancesearch
                variant="outline-primary"
              >
                Advance search
              </b-button>
            </b-input-group-append>

            <!-- <b-input-group-append>
                <b-button
                :to="{ name: 'create-accommodation'}"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary">
                  ADD +
                </b-button>
              </b-input-group-append> -->
          </b-input-group>

        </div>
      </b-form-group>

      <b-form-group v-if="$ability.can('add/copy', 'accommodation')">
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
                          :href="'/api/accommodationCsv?searchTerm='+ searchTerm+' &amp;limit='+exportLimit"
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

                :to="{ name: 'create-accommodation'}"

                title="Add Accommodation"
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
            <b-form-group
              label="Name"
              label-for="name"
            >
              <b-form-input
                id="name"
                v-model="formValues.name"
                type="text"
                placeholder="Accommodation Name"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Location"
              label-for="location"
            >
              <b-form-input
                id="location"
                v-model="formValues.location"
                type="text"
                placeholder="Accommodation Location"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Address"
              label-for="address"
            >
              <b-form-input
                id="address"
                v-model="formValues.address"
                type="text"
                placeholder="Accommodation Address"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Rooms"
              label-for="v-rooms"
            >
              <v-select
                id="v-rooms"
                v-model="formValues.rooms"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="roomsDropdown"

                label="text"
                :reduce="text => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Beds"
              label-for="beds"
            >
              <b-form-input
                id="beds"
                v-model="formValues.beds"
                type="number"
                placeholder="No of Beds"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">

            <label for="contract_startdate">Contract Start Date</label>
            <b-input-group>

              <cleave
                id="contract_startdate"
                v-model="formValues.contract_startdate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.contract_startdate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="contract_startdate"
                />
              </b-input-group-append>

            </b-input-group>
          </b-col>

          <b-col md="6">

            <label for="contract_enddate">Contract End Date</label>
            <b-input-group>

              <cleave
                id="contract_enddate"
                v-model="formValues.contract_enddate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.contract_enddate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="contract_enddate"
                />
              </b-input-group-append>

            </b-input-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Unit No."
              label-for="apartment"
            >
              <b-form-input
                id="apartment"
                v-model="formValues.apartment"
                type="text"
                placeholder="Appartments"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Rent"
              label-for="rent"
            >
              <b-input-group>
                <b-input-group-prepend is-text>
                  SR
                </b-input-group-prepend>
                <b-form-input
                  id="rent"
                  v-model="formValues.rent"
                  type="text"
                  placeholder="Accommodation rent"
                />
              </b-input-group>

            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Period"
              label-for="period"
            >
              <v-select
                id="period"
                v-model="formValues.period"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="periodDropdown"

                label="text"
                :reduce="text => text.value"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">

            <label for="due-date">Rent Due Date</label>
            <b-input-group>

              <cleave
                id="due-date"
                v-model="formValues.dueDate"
                class="form-control"
                :raw="false"
                :options="options.date"
                placeholder="YYYY-MM-DD"
              />

              <b-input-group-append>
                <b-form-datepicker
                  v-model="formValues.dueDate"
                  show-decade-nav
                  button-only
                  right
                  locale="en-US"
                  aria-controls="due-date"
                />
              </b-input-group-append>

            </b-input-group>

          </b-col>

          <b-col md="6">
            <b-form-group
              label="Services"
              label-for="services"
            >
              <b-form-checkbox-group
                id="checkbox-group-2"
                v-model="formValues.services"
                name="services"
                class="demo-inline-spacing"
              >
                <b-form-checkbox value="Room">
                  Room
                </b-form-checkbox>
                <b-form-checkbox value="Beds">
                  Beds
                </b-form-checkbox>
                <b-form-checkbox value="AC">
                  AC
                </b-form-checkbox>
                <b-form-checkbox value="Water">
                  Water
                </b-form-checkbox>
                <b-form-checkbox value="Electricity">
                  Electricity
                </b-form-checkbox>
                <b-form-checkbox value="Kitchen Equipment">
                  Kitchen Equipment
                </b-form-checkbox>

              </b-form-checkbox-group>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Electricity Account#"
              label-for="electricityAccountNo"
            >
              <cleave
                id="electricityAccountNo"
                v-model="formValues.electricityAccountNo"
                class="form-control"
                :raw="false"
                :options="options.account_number"
                placeholder="Electricity Account Number"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Water Bill Account#"
              label-for="waterBillAccountNo"
            >
              <cleave
                id="waterBillAccountNo"
                v-model="formValues.waterBillAccountNo"
                class="form-control"
                :raw="false"
                :options="options.account_number"
                placeholder="Water Bill Account Number"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Fixed Electricity Amount"
              label-for="fixedElectricityAmount"
            >
              <b-input-group>
                <b-input-group-prepend is-text>
                  SR
                </b-input-group-prepend>
                <b-form-input
                  id="fixedElectricityAmount"
                  v-model="formValues.fixedElectricityAmount"
                  type="text"
                  placeholder="Fixed Electricity Amount"
                />
              </b-input-group>

            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Fixed Water Amount"
              label-for="fixedWaterAmount"
            >
              <b-input-group>
                <b-input-group-prepend is-text>
                  SR
                </b-input-group-prepend>
                <b-form-input
                  id="fixedWaterAmount"
                  v-model="formValues.fixedWaterAmount"
                  type="text"
                  placeholder="Fixed Water Amount"
                />
              </b-input-group>

            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Contact Name"
              label-for="contactName"
            >
              <b-form-input
                id="contactName"
                v-model="formValues.contactName"
                type="text"
                placeholder="Contact Number"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Contact Number"
              label-for="contactNo"
            >
              <cleave
                id="contactNo"
                v-model="formValues.contactNo"
                class="form-control"
                :raw="false"
                :options="options.phone"
                placeholder="Contact Number"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Account Name"
              label-for="accountName"
            >
              <b-form-input
                id="accountName"
                v-model="formValues.accountName"
                type="text"
                placeholder="Account Name"
              />
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="Bank Name"
              label-for="bankName"
            >
              <!-- <b-form-input
                 id="bankName"
                v-model="formValues.bankName"
                type="text"
                placeholder="Bank Name"
              /> -->

              <v-select
                id="bankName"
                v-model="formValues.bankName"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="bankDropdown"

                label="text"
                :reduce="text => text.value"
              />

            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="IBAN"
              label-for="iban"
            >

              <cleave
                id="iban"
                v-model="formValues.iban"
                class="form-control"
                :raw="false"
                :options="options.iban"
                placeholder="Enter IBAN"
              />
            </b-form-group>
          </b-col>

          <b-col md="12">
            <b-form-group
              label="Notes"
              label-for="notes"
            >
              <b-form-textarea
                id="notes"
                v-model="formValues.notes"
                placeholder="Notes"
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
              @click="page=1,searchTerm='',closeSearch(), getEmployees()"
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
              v-if="$ability.can('edit', 'accommodation')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'edit-accommodation', params: { id: row.item.id }}"
              variant="flat-success"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="EditIcon" />
            </b-button>

            <b-button
              v-if="$ability.can('delete', 'accommodation')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-danger"
              class="btn-icon rounded-circle"
              @click="deletePurchase(row.item.id)"
            >
              <feather-icon icon="Trash2Icon" />
            </b-button>

            <b-button
              v-if="$ability.can('view', 'rent_payment')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-info"
              class="btn-icon rounded-circle"
              :to="{ name: 'view-accommodation-payments', params: { id: row.item.id }}"

              title="Rent Payments"
            >
              <feather-icon icon="DollarSignIcon" />
            </b-button>

            <b-button
              v-if="$ability.can('view', 'bill_payment')"
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-info"
              class="btn-icon rounded-circle"
              :to="{ name: 'view-bill-payments', params: { id: row.item.id }}"

              title="Bill Payments"
            >
              <feather-icon icon="ClipboardIcon" />
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

              <b-input-group-append v-if="$ability.can('edit', 'accommodation')">
                <b-button
                  size="sm"
                  :to="{ name: 'edit-accommodation', params: { id: row.item.id }}"
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
              <b-input-group-append v-if="$ability.can('view', 'rent_payment')">
                <b-button
                  size="sm"
                  :to="{ name: 'view-accommodation-payments', params: { id: row.item.id }}"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-info"
                >
                  <feather-icon icon="DollarSignIcon" />
                </b-button>
              </b-input-group-append>
              <b-input-group-append v-if="$ability.can('view', 'bill_payment')">
                <b-button
                  size="sm"
                  :to="{ name: 'view-bill-payments', params: { id: row.item.id }}"
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-primary text-info"
                >
                  <feather-icon icon="ClipboardIcon" />
                </b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-form-group>
      </template>

      <!-- full detail on click -->
      <template #row-details="row">
        <b-card
          no-body 
          class="px-1 py-1">
            <b-row>
              <b-col cols="12">
                <b-card-text>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Name</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.name }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Location</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.location }}
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
                      <strong>Rooms</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.rooms }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Beds</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.beds }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Contract Startdate</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.contract_startdate | moment("DD/MM/YYYY") }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Rent</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ Number(row.item.rent).toLocaleString() }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Services</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.services.join(', ') }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Due Date</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.dueDate | moment("DD/MM/YYYY") }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Period</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.period }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Unit No.</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.apartment }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Electricity Account No</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.electricityAccountNo }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Water Bill Account No</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.waterBillAccountNo }}
                    </b-col>
                  </b-row>

                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Fixed Electricity Amount</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ Number(row.item.fixedElectricityAmount).toLocaleString() }}
                    </b-col>
                  </b-row>

                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Fixed Electricity Amount</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ Number(row.item.fixedWaterAmount).toLocaleString() }}
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
                      {{ row.item.accountName }}
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Bank Name</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.bankName }}
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
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Notes</strong>
                    </b-col>
                    <b-col cols="10">
                      {{ row.item.notes }} 
                    </b-col>
                  </b-row>
                  <b-row class="border-bottom ">
                    <b-col cols="2">
                      <strong>Google Maps</strong>
                    </b-col>
                    <b-col cols="10">
                     <a href="https://www.google.com/maps/search/51.5088233,-0.1296787/@51.5088233,-0.1296787,13z" target="_blank">https://www.google.com/maps/search/{{row.item.latitude}},{{row.item.longitude}}/@{{row.item.latitude}},{{row.item.longitude}}z</a>
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
                </b-card-text>
              </b-col>
            </b-row>
        </b-card>
      </template>

      <!-- <template #cell(iqama)="data">
          <b-avatar :src="data.value" />
        </template> -->

      <template #cell(period)="data">
        <b-badge
          pill
          :variant="statusVariant(data.value)"
        >
          {{ data.value }}
        </b-badge>
      </template>

      <template #cell(dateWithVariant)="data">
        <b-badge
          pill
          :variant="data.value['dueDateVariant']"
        >
          {{ data.value['dueDate'] | moment("DD/MM/YYYY") }}
        </b-badge>
      </template>

      <template #cell(services)="data">

        <span>{{ data.value.join(', ') }}</span>

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
  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge, BImg, BTabs, BTab, BCardText, BCardImg ,  BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BInputGroup, BInputGroupAppend, BInputGroupPrepend, BFormDatepicker, BFormFile, BFormTextarea, BFormRadio, BForm, BFormCheckboxGroup, BFormRating, BListGroupItem, BListGroup, BModal, BPopover, BSpinner,
} from 'bootstrap-vue'

import Ripple from 'vue-ripple-directive'
import EnlargeableImage from '@diracleo/vue-enlargeable-image'
import pdf from 'vue-pdf'
import Cleave from 'vue-cleave-component'
import 'cleave.js/dist/addons/cleave-phone.us'

import VuePdfApp from 'vue-pdf-app'

import 'vue-pdf-app/dist/icons/main.css'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import { latLng, icon, Icon } from 'leaflet'
import { LMap, LTileLayer, LCircle } from 'vue2-leaflet'
import 'leaflet/dist/leaflet.css'

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
    BModal,
    pdf,
    VuePdfApp,
    BPopover,
    BSpinner,
    Cleave,
     LMap,
    LTileLayer,
    LCircle,
  },
  directives: {
    Ripple,
  },

  errorCaptured() {
    return false
  },

  data() {
    return {
      options: {

        phone: {
          blocks: [12],
          uppercase: true,
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
          blocks: [24],
          uppercase: true,
        },

      },
      popoverShow: false,
      exportLimit: '',
      isBusy: true,
      fields: [{ key: 'Options', thClass: 'customHead' }, {
        key: 'id', label: 'ID', sortable: true, thClass: 'customHead',
      }, { key: 'name', sortable: true, thClass: 'customHead' }, { key: 'Address', sortable: true, thClass: 'customHead' }, { key: 'rent', sortable: true, thClass: 'customHead' }, { key: 'period', sortable: true, thClass: 'customHead' }, {
        key: 'dateWithVariant', label: 'dueDate', sortable: true, thClass: 'customHead',
      }],
      items: [
      ],
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

      searchTerm: '',
      pageSize: 10,
      page: 1,
      count: 0,

      formValues: {
        name: '',
        location: '',
        address: '',
        rooms: '',
        beds: '',
        contract_startdate: '',
        contract_enddate: '',
        rent: '',
        services: [],
        dueDate: '',
        period: '',
        apartment: '',
        electricityAccountNo: '',
        waterBillAccountNo: '',

        contactName: '',
        contactNo: '',
        accountName: '',
        bankName: '',
        iban: '',
        fixedElectricityAmount: '',
        fixedWaterAmount: '',
        notes: '',
        center: [],
        
      },

      roomsDropdown: [
        { value: '1 Room', text: '1 Room' },
        { value: '2 Rooms', text: '2 Rooms' },
        { value: '3 Rooms', text: '3 Rooms' },
        { value: '4 Rooms', text: '4 Rooms' },
        { value: '5 Rooms', text: '5 Rooms' },
        { value: '6 Rooms', text: '6 Rooms' },
        { value: '7 Rooms', text: '7 Rooms' },
        { value: '8 Rooms', text: '8 Rooms' },
        { value: '9 Rooms', text: '9 Rooms' },
      ],

      periodDropdown: [
        { value: 'Monthly', text: 'Monthly' },
        { value: 'Bi-Monthly', text: 'Bi-Monthly' },
        { value: 'Quarterly', text: 'Quarterly' },
        { value: '4 Months', text: '4 Months' },
        { value: '6 Months', text: '6 Months' },
        { value: 'Annual', text: 'Annual' },
      ],

      bankDropdown: [
        { value: 'Al Rajhi', text: 'Al Rajhi' },
        { value: 'Al Ahli', text: 'Al Ahli' },
        { value: 'Alinma', text: 'Alinma' },
        { value: 'Riyadh Bank', text: 'Riyadh Bank' },
        { value: 'Banque Saudi Fransi', text: 'Banque Saudi Fransi' },
        { value: 'The Saudi Investment Bank', text: 'The Saudi Investment Bank' },
        { value: 'The National Commercial Bank', text: 'The National Commercial Bank' },
        { value: 'The Saudi British Bank (SABB)', text: 'The Saudi British Bank (SABB)' },
        { value: 'Samba Financial Group (Samba)', text: 'Samba Financial Group (Samba)' },
        { value: 'Alawwal bank', text: 'Alawwal bank' },
        { value: 'Arab National Bank', text: 'Arab National Bank' },
        { value: 'Bank AlBilad', text: 'Bank AlBilad' },
        { value: 'Bank AlJazira', text: 'Bank AlJazira' },
        { value: 'Saudi National Bank', text: 'Saudi National Bank' },
        { value: 'Gulf International Bank Saudi Arabia (GIB-SA)', text: 'Gulf International Bank Saudi Arabia (GIB-SA)' },
      ],

    
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
      zoom: 16,
      circle: {
        radius: 4500,
        color: '#EA5455',
      },
    }
  },

  mounted() {
    this.getEmployees()
  },
  methods: {

    closeSearch() {
      this.$refs.searchModel.hide()
    },

    resetSearch() {
      Object.entries(this.formValues).forEach(([key, value]) => {
        if (key == 'services') {
          this.formValues[key] = []
        } else if (key == 'lang_eng' || key == 'lang_ar' || key == 'lang_hind' || key == 'appearance_presentable') {
          this.formValues[key] = null
        } else {
          this.formValues[key] = ''
        }
      })

      console.log(this.formValues)
    },

    onClose() {
      this.popoverShow = false
    },

    statusVariant(period) {
      if (period == 'Monthly') {
        return 'primary'
      }
      if (period == 'Bi-Monthly') {
        return 'success'
      }
      if (period == 'Quarterly') {
        return 'danger'
      }
      if (period == '4 Months') {
        return 'warning'
      }
      if (period == '6 Months') {
        return 'info'
      }
      if (period == 'Annual') {
        return 'secondary'
      }

      return 'light'
    },
    httpHelper(error){
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
    deletePurchase(id) {
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
          axios.get('/deleteAccommodation', {
            params: {
              id,
            },
          }).then(response => {
            if (response.data.hasOwnProperty('success')) {
              if (response.data.success === true) {
                this.getEmployees()
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Purchase Deleted',
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })
                console.log('Accommodation deleted')
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

    getEmployees() {
      this.isBusy = true

      axios.get('/getAccommodation', {
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
        }).catch(error => {
          console.error(error)
          this.httpHelper(error)
        })
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
<style lang="scss">
.vue2leaflet-map{
  &.leaflet-container{
    height: 250px;
    width:  250px;
  }
}
</style>