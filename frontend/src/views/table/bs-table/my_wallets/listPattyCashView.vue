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
                                @click="
                                    page = 1;
                                    Day = '';
                                    getDocuments();
                                "
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
                                @click="Day = ''"
                            >
                                Advance search
                            </b-button>
                        </b-input-group-append>
                        <b-input-group-append>
                            <b-button
                                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                variant="outline-primary"
                                v-bind:class="{ active: Day == 'yesterday' }"
                                @click="
                                    Day = 'yesterday';
                                    getDocuments();
                                "
                            >
                                Yesterday
                            </b-button>
                        </b-input-group-append>
                        <b-input-group-append>
                            <b-button
                                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                variant="outline-primary"
                                v-bind:class="{ active: Day == 'today' }"
                                @click="
                                    Day = 'today';
                                    getDocuments();
                                "
                            >
                                Today
                            </b-button>
                        </b-input-group-append>
                        <b-input-group-append>
                            <b-button
                                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                variant="outline-primary"
                                v-bind:class="{ active: Day == '' }"
                                @click="
                                    Day = '';
                                    getDocuments();
                                "
                            >
                                All
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </div>
            </b-form-group>
            <!-- v-if="$ability.can('add/copy', 'petty_cash')" -->
            <b-form-group>
                <div class="d-flex align-items-center">
                    <b-input-group>
                        <b-input-group-prepend>
                            <div id="my-container">
                                <b-button
                                    id="popover-reactive-1"
                                    ref="button"
                                    title="Export"
                                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
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
                                                    v-ripple.400="
                                                        'rgba(255, 255, 255, 0.15)'
                                                    "
                                                    size="sm"
                                                    variant="primary"
                                                    :href="
                                                        '/api/walletTransactionsExport?wallet_id=' +
                                                            walletId +
                                                            '&amp;searchTerm=' +
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
                                        </b-row>
                                    </div>
                                </b-popover>
                            </div>
                        </b-input-group-prepend>
                        <b-input-group-prepend>
                            <b-button
                                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                variant="primary"
                                :to="{
                                    name: 'create-my-new-patty-cash',
                                    params: { id: walletId }
                                }"
                                title="Petty Cash"
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
                            label="Transaction Type"
                            label-for="trans_type"
                        >
                            <v-select
                                id="trans_type"
                                v-model="formValues.transaction_type"
                                :dir="
                                    $store.state.appConfig.isRTL ? 'rtl' : 'ltr'
                                "
                                :options="RequestType"
                                label="text"
                                :reduce="text => text.value"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                            label="Assign To Category"
                            label-for="Category"
                        >
                            <v-select
                                id="Category"
                                v-model="formValues.assign_to_category"
                                :dir="
                                    $store.state.appConfig.isRTL ? 'rtl' : 'ltr'
                                "
                                :options="AssignToCategory"
                                label="text"
                                :reduce="text => text.value"
                                @input="Type_Change(formValues)"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col md="6">
                        <b-form-group
                            label="Assign To Entity"
                            label-for="autosuggest__input"
                        >
                            <vue-autosuggest
                                v-model="formValues.assign_to_entity"
                                :suggestions="filteredOptions"
                                :limit="10"
                                :input-props="{
                                    id: 'autosuggest__input',
                                    class: 'form-control',
                                    placeholder: 'Search with name or ID'
                                }"
                                @input="onInputChange(formValues)"
                                @selected="assignOwnerField(formValues, $event)"
                            >
                                <template slot-scope="{ suggestion }">
                                    <span class="my-suggestion-item"
                                        >{{ suggestion.item.name }},
                                        {{ suggestion.item.id }}
                                    </span>
                                </template>
                            </vue-autosuggest>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Price" label-for="total_price">
                            <b-form-input
                                @scroll.prevent
                                id="total_price"
                                v-model.number="formValues.total"
                                type="number"
                                placeholder="0.00"
                                min="0"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <label for="v-from_date">From Date</label>
                        <b-input-group>
                            <cleave
                                id="from_date"
                                v-model="formValues.fromDate"
                                class="form-control"
                                :raw="false"
                                :options="options.date"
                                placeholder="YYYY-MM-DD"
                            />

                            <b-input-group-append>
                                <b-form-datepicker
                                    v-model="formValues.fromDate"
                                    show-decade-nav
                                    button-only
                                    right
                                    locale="en-US"
                                    aria-controls="v-from_date"
                                />
                            </b-input-group-append>
                        </b-input-group>
                    </b-col>
                    <b-col md="6">
                        <label for="v-to_date">To Date</label>
                        <b-input-group>
                            <cleave
                                id="to_date"
                                v-model="formValues.toDate"
                                class="form-control"
                                :raw="false"
                                :options="options.date"
                                placeholder="YYYY-MM-DD"
                            />

                            <b-input-group-append>
                                <b-form-datepicker
                                    v-model="formValues.toDate"
                                    show-decade-nav
                                    button-only
                                    right
                                    locale="en-US"
                                    aria-controls="v-to_date"
                                />
                            </b-input-group-append>
                        </b-input-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Description">
                            <b-form-textarea
                                id="Description"
                                v-model="formValues.description"
                                placeholder="Search Description's Here.."
                                rows="2"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Notes">
                            <b-form-textarea
                                id="notes"
                                v-model="formValues.notes"
                                placeholder="Search Note's Here.."
                                rows="2"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col cols="6">
                        <div class="d-flex pt-1 my-2">
                            <b-form-checkbox
                                class="d-flex"
                                value="1"
                                v-model="formValues.approve"
                                name="approve"
                            >
                                Approve
                            </b-form-checkbox>
                            <b-form-checkbox
                                class="d-flex ml-2 pl-2"
                                value="0"
                                v-model="formValues.approve"
                                name="approve"
                            >
                                Not Approve
                            </b-form-checkbox>
                        </div>
                    </b-col>
                    <!-- <b-col cols="6" class="mb-2">
            <div class="d-flex pt-1 mt-1">
            <b-form-checkbox class="d-flex" value="Credit" v-model="formValues.credit_debit" name="credit_debit">
              Credit
            </b-form-checkbox>
              <b-form-checkbox class="d-flex ml-2 pl-2" value="Debit" v-model="formValues.credit_debit" name="credit_debit">
              Debit
            </b-form-checkbox>
            </div>
      </b-col> -->

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

        <div class="container">
            <b-row class="">
                <!-- <b-col md="3" class="border border-primary p-1 m-1  rounded " >
                        <strong>Wallet Id : </strong>
                        {{ wallet__data_values.id }}
                      </b-col> -->
                <b-col
                    md="3"
                    class="border border-primary text-white p-1 m-1 rounded"
                    style="
            background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));
          "
                >
                    <strong>Wallet User Name : </strong>
                    {{ wallet__data_values.user_name }}
                </b-col>

                <b-col
                    md="3"
                    class="border border-primary text-white p-1 m-1 rounded"
                    style="
            background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));
          "
                >
                    <strong>Wallet Account Name : </strong>
                    {{ wallet__data_values.account_name }}
                </b-col>
                <b-col
                    md="3"
                    class="border border-primary text-white p-1 m-1 rounded"
                    style="
            background: linear-gradient(118deg, #06608f, rgba(6, 96, 143, 0.7));
          "
                >
                    <strong>Balance : </strong>
                    {{ wallet__data_values.balance }}
                </b-col>
                <!-- <template #cell(wallet_id)="">
          {{ wallet__data_values.id }}
      </template>
      <template #cell(wallet_user_name)="">
          {{ wallet__data_values.user_name }}
      </template>
            <template #cell(wallet_account_name)="">
          {{ wallet__data_values.account_name }}
      </template> -->
            </b-row>
        </div>

        <!-- <b-card
    class="card-company-table mb-1"
  > -->
        <!-- tableData -->
        <!-- <b-table
      :items="tableData"
      responsive
      :fields="fields_top"
      class="mb-0"
    >
      <template #cell(wallet_id)="">
          {{ wallet__data_values.id }}
      </template>
      <template #cell(wallet_user_name)="">
          {{ wallet__data_values.user_name }}
      </template>
            <template #cell(wallet_account_name)="">
          {{ wallet__data_values.account_name }}
      </template>

    </b-table> -->
        <!-- </b-card> -->
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
                    <div class="d-flex justify-content-start">
                        <!-- v-if="$ability.can('edit', 'petty_cash')" -->
                        <!-- <b-button

              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              :to="{ name: 'edit-my-patty-cash', params: { id: row.item.id } }"
              variant="flat-success"
              class="btn-icon rounded-circle"
            >
              <feather-icon icon="EditIcon" />
            </b-button> -->
                        <!-- v-if="$ability.can('delete', 'petty_cash')" -->
                        <!-- <b-button

              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="flat-danger"
              class="btn-icon rounded-circle"
              @click="deleteDocument(row.item.id)"
            >
              <feather-icon icon="Trash2Icon" />
            </b-button> -->
                    </div>
                </div>
            </template>

            <template #cell(transaction_type)="data">
                <b-badge pill :variant="statusVariant(data.value)">
                    {{ data.value }}
                </b-badge>
            </template>
            <template #cell(total)="data">
                <span
                    :class="
                        data.item.credit_debit == 'Debit' ? 'text-danger' : ''
                    "
                    >{{ data.value }}</span
                >
            </template>

            <template #cell(accept)="data">
                <b-form-checkbox
          :disabled="isSubmitting || data.item.accept"
          type="checkbox"
          class="m-0"
          :value="true"
          v-model="data.item.accept"
          @change="accept_status($event, data.item)"
        >
          <b-badge pill :variant="accept_statusVariant(data.value)">
            <span class="text-white" v-if="data.value == true">Accept</span>
            <span class="text-white" v-if="data.value == false"
              >Not Accept</span
            >
          </b-badge>
        </b-form-checkbox>

                <!-- v-if="!$ability.can('edit', 'petty_cash_approve')" -->

                <!-- <b-badge pill :variant="accept_statusVariant(data.value)" >
                    <span class="text-white" v-if="data.value == true">Accept</span>
                      <span class="text-white" v-if="data.value == false">Not Accept</span>
                </b-badge>  -->
            </template>

            <template #cell(approve)="data">
                <!-- <b-form-checkbox :disabled="isSubmitting" type="checkbox" class="m-0" :value='true' v-model="data.item.approve" @change="approve_status($event, data.item)" v-if="$ability.can('edit', 'petty_cash_approve')">
                <b-badge pill :variant="approve_statusVariant(data.value)">
                    <span class="text-white" v-if="data.value == true">Approve</span>
                      <span class="text-white" v-if="data.value == false">Not Approve</span>
                </b-badge>
        </b-form-checkbox> -->

                <!-- v-if="!$ability.can('edit', 'petty_cash_approve')" -->

                <b-badge pill :variant="approve_statusVariant(data.value)">
                    <span class="text-white" v-if="data.value == true"
                        >Approve</span
                    >
                    <span class="text-white" v-if="data.value == false"
                        >Not Approve</span
                    >
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
                                        <strong>Wallet Id</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.wallet_id }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Transaction Type</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.transaction_type }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Assign To Category</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.assign_to_category }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Assign To Entity</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.assign_to_entity }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Assign To Entity Id</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.assign_to_entity_id }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Approve</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        <!-- {{ row.item.approve }} -->
                                        <b-badge
                                            pill
                                            :variant="
                                                approve_statusVariant(
                                                    row.item.approve
                                                )
                                            "
                                        >
                                            <span
                                                class="text-white"
                                                v-if="row.item.approve == true"
                                                >Approve</span
                                            >
                                            <span
                                                class="text-white"
                                                v-if="row.item.approve == false"
                                                >Not Approve</span
                                            >
                                        </b-badge>
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Date</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.date }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Unit Price</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.unit_price }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Quantity</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.quantity }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Total</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.total }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Type(Credit/Debit)</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.credit_debit }}
                                    </b-col>
                                </b-row>
                                <b-row class="border-bottom">
                                    <b-col cols="2">
                                        <strong>Description</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.description }}
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

                                <b-row
                                    class="border-bottom"
                                    v-if="
                                        row.item.assign_to_category == 'Client'
                                    "
                                >
                                    <b-col cols="2">
                                        <strong>Client Location</strong>
                                    </b-col>
                                    <b-col cols="10">
                                        {{ row.item.client_location }}
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
                                <b-row
                                    class="border-bottom"
                                    v-if="row.item.approved_by"
                                >
                                    <b-col cols="12" class="text-right">
                                        <strong>Approved By : </strong>
                                        {{ row.item.approved_by }}
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
import { VueAutosuggest } from "vue-autosuggest";

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
        VueAutosuggest,
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
            Day: "",
            walletId: null,
            popoverShow: false,
            base_url: "",
            isSubmitting: false,

            recipientType: [
                {
                    value: "Accommodation",
                    text: "Accommodation"
                },
                {
                    value: "Client",
                    text: "Client"
                },
                {
                    value: "Employee",
                    text: "Employee"
                },
                {
                    value: "Supplier",
                    text: "Supplier"
                },
                {
                    value: "Vendor",
                    text: "Vendor"
                }
            ],
            recipients: [
                {
                    value: "New Contract",
                    text: "New Contract"
                },
                {
                    value: "Renewal Contract",
                    text: "Renewal Contract"
                },
                {
                    value: "Contract Termination",
                    text: "Contract Termination"
                },
                {
                    value: "Rent Payment",
                    text: "Rent Payment"
                },
                {
                    value: "Electricity Payment",
                    text: "Electricity Payment"
                },
                {
                    value: "Others",
                    text: "Others"
                }
            ],
            recipients_accommodation: [
                {
                    value: "New Contract",
                    text: "New Contract"
                },
                {
                    value: "Renewal Contract",
                    text: "Renewal Contract"
                },
                {
                    value: "Contract Termination",
                    text: "Contract Termination"
                },
                {
                    value: "Rent Payment",
                    text: "Rent Payment"
                },
                {
                    value: "Electricity Payment",
                    text: "Electricity Payment"
                },
                {
                    value: "Others",
                    text: "Others"
                }
            ],
            recipients_client: [
                {
                    value: "Addendum",
                    text: "Addendum"
                },
                {
                    value: "Statement Of Account",
                    text: "Statement Of Account"
                },
                {
                    value: "Balance Confirmation",
                    text: "Balance Confirmation"
                },
                {
                    value: "Delayed Payment",
                    text: "Delayed Payment"
                },
                {
                    value: "Dispatch Notice",
                    text: "Dispatch Notice"
                },
                {
                    value: "Non-Renewal",
                    text: "Non-Renewal"
                },
                {
                    value: "Others",
                    text: "Others"
                },
                {
                    value: "Renewal",
                    text: "Renewal"
                },
                {
                    value: "Termination",
                    text: "Termination"
                }
            ],
            recipients_vendor: [
                {
                    value: "AJEER Request",
                    text: "AJEER Request"
                },
                {
                    value: "Balance Confirmation",
                    text: "Balance Confirmation"
                },
                {
                    value: "Employee Runaway",
                    text: "Employee Runaway"
                },
                {
                    value: "Employee Termination",
                    text: "Employee Termination"
                },
                {
                    value: "Employee Vacation Request",
                    text: "Employee Vacation Request"
                },
                {
                    value: "Iqama Expiry",
                    text: "Iqama Expiry"
                }
            ],
            recipients_employee: [
                {
                    value: "Salary Slip",
                    text: "Salary Slip"
                },
                {
                    value: "Employment Certificate",
                    text: "Employment Certificate"
                },
                {
                    value: "Entry & Exit Certificate",
                    text: "Entry & Exit Certificate"
                },
                {
                    value: "Salary Certificate",
                    text: "Salary Certificate"
                },
                {
                    value: "Vacation Benefits",
                    text: "Vacation Benefits"
                },
                {
                    value: "EOSB",
                    text: "EOSB"
                }
            ],
            recipients_supplier: [
                {
                    value: "Balance Confirmation",
                    text: "Balance Confirmation"
                },
                {
                    value: "Statement of Account",
                    text: "Statement of Account"
                },
                {
                    value: "Others",
                    text: "Others"
                }
            ],
            RequestType: [
                {
                    value: "Equipment Purchase",
                    text: "Equipment Purchase"
                },
                {
                    value: "Material Purchase",
                    text: "Material Purchase"
                },
                {
                    value: "Accommodation Purchase",
                    text: "Accommodation Purchase"
                },
                {
                    value: "Employee Advance",
                    text: "Employee Advance"
                },
                {
                    value: "Employee Payment",
                    text: "Employee Payment"
                },
                {
                    value: "Utility Purchase",
                    text: "Utility Purchase"
                },
                {
                    value: "Fuel Credit",
                    text: "Fuel Credit"
                },
                { value: "Fuel Expense", text: "Fuel Expense" },
                {
                    value: "Adjustment",
                    text: "Adjustment"
                },
                {
                    value: "Credit",
                    text: "Credit"
                },
                {
                    value: "Service Purchase",
                    text: "Service Purchase"
                },
                {
                    value: "Transportation Expense",
                    text: "Transportation Expense"
                },
                {
                    value: "Transfer to Wallet",
                    text: "Transfer to Wallet"
                },
                {
                    value: "Others",
                    text: "Others"
                }
            ],
            AssignToCategory: [
                {
                    value: "Accommodation",
                    text: "Accommodation"
                },
                {
                    value: "Client",
                    text: "Client"
                },
                {
                    value: "Employee",
                    text: "Employee"
                },
                {
                    value: "Supplier",
                    text: "Supplier"
                },
                {
                    value: "Vendor",
                    text: "Vendor"
                },
                {
                    value: "Wallet",
                    text: "Wallet"
                }
            ],
            filteredOptions: [],

            formValues: {
                total: null,
                transaction_type: "",
                assign_to_category: "",
                assign_to_entity: "",
                assign_to_entity_id: "",
                credit_debit: "",
                approve: null,
                fromDate: "",
                toDate: "",
                notes: null,
                description: null
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
                {
                    key: "Options",
                    thClass: "customHead"
                },
                {
                    key: "id",
                    label: "ID",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "transaction_type",
                    label: "Transaction Type",
                    sortable: true,
                    thClass: "customHead"
                },

                {
                    key: "total",
                    label: "Total",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "date",
                    label: "Date",
                    sortable: true,
                    thClass: "customHead"
                },
                {
                    key: "accept",
                    label: "Accept",
                    thClass: "customHead"
                },

                {
                    key: "approve",
                    label: "Approve",
                    thClass: "customHead"
                },

                {
                    key: "total",
                    label: "Total",
                    sortable: true,
                    thClass: "customHead"
                }
            ],
            items: [],
            /* eslint-disable global-require */

            searchTerm: "",
            pageSize: 10,
            page: 1,
            count: 0,

            tableData: [0],
            wallet__data_values: {},

            fields_top: [
                {
                    key: "wallet_id",
                    label: "Wallet Id"
                },
                {
                    key: "wallet_user_name",
                    label: "Wallet User Name"
                },
                {
                    key: "wallet_account_name",
                    label: "Wallet Account Name"
                }
            ]
        };
    },

    mounted() {
        this.walletId = this.$route.params.id;
        this.getWallet();

        this.getDocuments();
    },
    methods: {
        onClose() {
            this.popoverShow = false;
        },

        accept_status(data, item) {
            item.aprrove = data;
            console.log("item approve", item);
            console.log("data approve", data);
            var id = item.id;

            console.log(id);
            this.isSubmitting = true;

            this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                    title: "Transaction is in processing",
                    icon: "InfoIcon",
                    variant: "primary"
                }
            });

            axios
                .post("/updateTransactionApproveStatus", {
                    id: id,
                    accept: data
                })
                .then(response => {
                    if (response.data.hasOwnProperty("success")) {
                        if (response.data.success === true) {
                            // this.getDocuments();
                            this.getWallet();

                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Transaction updated Successfully",
                                    icon: "EditIcon",
                                    variant: "success"
                                }
                            });
                            console.log("Transaction Updated Successfully");
                            this.isSubmitting = false;
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
                            this.isSubmitting = false;
                        }
                    } else {
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
                });
        },
        Type_Change(item) {
            console.log("clear item ", item);
            item.assign_to_entity = "";
            item.assign_to_entity_id = "";

            this.filteredOptions = [];
            console.log("clear item new", item);
        },
        assignOwnerField(item, event) {
            console.log("item", item);
            console.log("name", event.item);
            item.assign_to_entity = event.item.name;
            item.assign_to_entity_id = event.item.id;
            console.log("item new", item);

            return;
        },

        onInputChange(item) {
            let text = item.assign_to_entity;
            let type = item.assign_to_category;
            if (text === "" || text === undefined) {
                return;
            }
            if (type === "" || type === undefined) {
                return;
            }
            console.log("text", text);

            axios
                .get("/documentResourceOwner", {
                    params: {
                        id: text,
                        type: item.assign_to_category
                    }
                })
                .then(response => {
                    if (response.data.hasOwnProperty("success")) {
                        if (response.data.success === true) {
                            console.log(response.data.data);
                            this.filteredOptions = [
                                {
                                    data: response.data.data
                                }
                            ];
                            console.log("item", item);

                            console.log("Assign to entity Fetched");
                        } else {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Error",
                                    icon: "AlertCircleIcon",
                                    variant: "danger",
                                    text: response.data.message
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
                                text: "Something went wrong, try again later"
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },

        getWallet() {
            console.log(this.$route.params.id);

            axios
                .get("/getMyWallet", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(response => {
                    if (response.data.hasOwnProperty("success")) {
                        if (response.data.success === true) {
                            console.log(response.data.data);

                            this.wallet__data_values = response.data.data;
                            this.wallet__data_values["user_name"] =
                                response.data.data.user.name;

                            console.log("tableData", this.wallet__data_values);

                            // this.initTrHeight();

                            console.log("Wallet Fetched");
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
                                text: "Something went wrong, try again later"
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
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
                        .post("/deleteMyTransaction", {
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
                                                "Transaction Deleted Successfully",
                                            icon: "EditIcon",
                                            variant: "success"
                                        }
                                    });
                                    console.log(
                                        "Transaction Deleted Successfully"
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
                .get("/getAllMyTransaction", {
                    params: {
                        id: this.$route.params.id,
                        day: this.Day,
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
                .catch((error) => {
          console.error(error);
          this.httpHelper(error)
        });
        this.getWallet()
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
        statusVariant(ownerType) {
            if (ownerType == "Adjustment") {
                return "warning";
            } else if (ownerType == "Credit") {
                return "danger";
            } else {
                return "primary";
            }
        },

        approve_statusVariant(ownerType) {
            if (ownerType == true) {
                return "success";
            } else if (ownerType == false) {
                return "danger";
            }
        },
        accept_statusVariant(ownerType) {
            if (ownerType == true) {
                return "success";
            } else if (ownerType == false) {
                return "danger";
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
}</style
><style>
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
}</style
><style lang="scss">
@import "@core/scss/vue/libs/vue-autosuggest.scss";
</style>
