(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[84],{

/***/ "./frontend/src/views/table/bs-table/code.js":
/*!***************************************************!*\
  !*** ./frontend/src/views/table/bs-table/code.js ***!
  \***************************************************/
/*! exports provided: codeBasic, codeStyleOption, codeRowColStyle, codeResponsive, codeFormatterCallback, codeDataRendering, codeSticky, codeRowDetailsSupport, codeRowSelectSupport, codeKitchenSink, codeLight, codeSimple */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeBasic", function() { return codeBasic; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeStyleOption", function() { return codeStyleOption; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeRowColStyle", function() { return codeRowColStyle; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeResponsive", function() { return codeResponsive; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeFormatterCallback", function() { return codeFormatterCallback; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeDataRendering", function() { return codeDataRendering; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeSticky", function() { return codeSticky; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeRowDetailsSupport", function() { return codeRowDetailsSupport; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeRowSelectSupport", function() { return codeRowSelectSupport; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeKitchenSink", function() { return codeKitchenSink; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeLight", function() { return codeLight; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeSimple", function() { return codeSimple; });
var codeBasic = "\n<template>\n  <b-table responsive=\"sm\" :items=\"items\"/>\n</template>\n\n<script>\nimport { BTable } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n  },\n  data() {\n    return {\n      items: [\n        {\n          age: 40, first_name: 'Dickerson', last_name: 'Macdonald', Occupation: 'Job',\n        },\n        {\n          age: 21, first_name: 'Larsen', last_name: 'Shaw', Occupation: 'Job',\n        },\n        {\n          age: 89, first_name: 'Geneva', last_name: 'Wilson', Occupation: 'Bussiness',\n        },\n        {\n          age: 38, first_name: 'Jami', last_name: 'Carney', Occupation: 'Bussiness',\n        },\n        {\n          age: 40, first_name: 'James', last_name: 'Thomson', Occupation: 'Job',\n        },\n      ],\n    }\n  },\n}\n</script>\n";
var codeStyleOption = "\n<template>\n  <div>\n    <!-- checkbox -->\n    <b-form-group>\n      <label class=\"mb-0\">Table Options</label>\n      <div class=\"demo-inline-spacing\">\n        <b-form-checkbox\n          v-model=\"striped\"\n        >\n          Striped\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"bordered\"\n        >\n          Bordered\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"borderless\"\n        >\n          Borderless\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"outlined\"\n        >\n          Outlined\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"small\"\n        >\n          Small\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"hover\"\n        >\n          Hover\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"dark\"\n        >\n          Dark\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"fixed\"\n        >\n          Fixed\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"footClone\"\n        >\n          Foot Clone\n        </b-form-checkbox>\n        <b-form-checkbox\n          v-model=\"noCollapse\"\n        >\n          No border collapse\n        </b-form-checkbox>\n      </div>\n    </b-form-group>\n\n    <!-- radio -->\n    <b-form-group\n      label=\"Head Variant\"\n      label-cols-lg=\"2\"\n    >\n      <b-form-radio-group\n        v-model=\"headVariant\"\n      >\n        <b-form-radio\n          :value=\"null\"\n        >\n          None\n        </b-form-radio>\n        <b-form-radio\n          value=\"light\"\n        >\n          Light\n        </b-form-radio>\n        <b-form-radio\n          value=\"dark\"\n        >\n          Dark\n        </b-form-radio>\n      </b-form-radio-group>\n    </b-form-group>\n\n    <!-- variant select -->\n    <b-form-group\n      label=\"Table Variant\"\n      label-for=\"table-style-variant\"\n      label-cols-lg=\"2\"\n      class=\"mb-0\"\n    >\n      <v-select\n        v-model=\"tableVariant\"\n        :dir=\"$store.state.appConfig.isRTL ? 'rtl' : 'ltr'\"\n        label=\"tableVariants\"\n        :options=\"tableVariants\"\n      />\n    </b-form-group>\n\n    <!-- table -->\n    <b-table\n      :striped=\"striped\"\n      :bordered=\"bordered\"\n      :borderless=\"borderless\"\n      :outlined=\"outlined\"\n      :small=\"small\"\n      :hover=\"hover\"\n      :dark=\"dark\"\n      :fixed=\"fixed\"\n      :foot-clone=\"footClone\"\n      :no-border-collapse=\"noCollapse\"\n      :items=\"items\"\n      :fields=\"fields\"\n      :head-variant=\"headVariant\"\n      :table-variant=\"tableVariant\"\n    />\n  </div>\n</template>\n\n<script>\nimport {\n  BTable, BFormGroup, BFormRadio, BFormCheckbox, BFormRadioGroup, BCardBody,\n} from 'bootstrap-vue'\nimport vSelect from 'vue-select'\n\nexport default {\n  components: {\n    BTable,\n    BFormGroup,\n    BCardBody,\n    vSelect,\n    BFormRadio,\n    BFormCheckbox,\n    BFormRadioGroup,\n  },\n  data() {\n    return {\n      fields: ['first_name', 'last_name', 'age'],\n      items: [\n        { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },\n        { age: 21, first_name: 'Larsen', last_name: 'Shaw' },\n        { age: 89, first_name: 'Geneva', last_name: 'Wilson' },\n        { age: 89, first_name: 'Olenka', last_name: 'Brawley' },\n        { age: 89, first_name: 'Vernon', last_name: 'Perford' },\n      ],\n      tableVariants: [\n        'none',\n        'primary',\n        'secondary',\n        'info',\n        'danger',\n        'warning',\n        'success',\n        'light',\n        'dark',\n      ],\n      striped: false,\n      bordered: false,\n      borderless: false,\n      outlined: false,\n      small: false,\n      hover: false,\n      dark: false,\n      fixed: false,\n      footClone: false,\n      headVariant: null,\n      tableVariant: 'none',\n      noCollapse: false,\n    }\n  },\n}\n</script>\n\n<style lang=\"scss\">\n@import '@core/scss/vue/libs/vue-select.scss';\n</style>\n";
var codeRowColStyle = "\n<template>\n  <b-table\n    :items=\"items\"\n    :fields=\"fields\"\n    :tbody-tr-class=\"rowClass\"\n    class=\"rounded-bottom\"\n  />\n</template>\n\n<script>\nimport { BTable } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n  },\n  data() {\n    return {\n      fields: ['first_name', 'last_name', { key: 'age', variant: 'success' }],\n      items: [\n        {\n          age: 40, first_name: 'Dickerson', last_name: 'Macdonald', status: 'awesome',\n        },\n        { age: 21, first_name: 'Larsen', last_name: 'Shaw' },\n        { age: 89, first_name: 'Geneva', last_name: 'Wilson' },\n        { age: 89, first_name: 'Olenka', last_name: 'Brawley' },\n        { age: 89, first_name: 'Vernon', last_name: 'Perford' },\n      ],\n    }\n  },\n  methods: {\n    rowClass(item, type) {\n      const colorClass = 'table-success'\n      if (!item || type !== 'row') { return }\n\n      // eslint-disable-next-line consistent-return\n      if (item.status === 'awesome') { return colorClass }\n    },\n  },\n}\n</script>\n";
var codeResponsive = "\n<template>\n   <b-table\n    responsive\n    :items=\"items\"\n    :fields=\"fields\"\n    class=\"mb-0\"\n  >\n    <template #cell(Phone)=\"data\">\n      <span class=\"text-nowrap\">\n        {{ data.value }}\n      </span>\n    </template>\n\n    <!-- Optional default data cell scoped slot -->\n    <template #cell()=\"data\">\n      {{ data.value }}\n    </template>\n  </b-table>\n</template>\n\n<script>\nimport { BTable } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n  },\n  data() {\n    return {\n      fields: [\n        'id',\n        'first_name',\n        'email',\n        'gender',\n        'ip_address',\n        {\n          key: 'Phone', label: 'Phone',\n        },\n        'Country',\n      ],\n      items: [\n        {\n          id: '1',\n          first_name: 'Marybelle',\n          last_name: 'Della Scala',\n          email: 'mdellascala0@opensource.org',\n          gender: 'Female',\n          ip_address: '85.254.7.181',\n          Phone: '+86 350 673 7985',\n          Country: 'China',\n\n        },\n        {\n          id: '2',\n          first_name: 'Octavia',\n          last_name: 'Tohill',\n          email: 'otohill1@google.co.jp',\n          gender: 'Female',\n          ip_address: '93.70.144.212',\n          Phone: '+63 938 283 0018',\n          Country: 'Philippines',\n        },\n        {\n          id: '3',\n          first_name: 'Jennie',\n          last_name: 'Geroldi',\n          email: 'jgeroldi2@slideshare.net',\n          gender: 'Female',\n          ip_address: '76.145.147.212',\n          Phone: '+81 235 674 0110',\n          Country: 'Japan',\n        },\n        {\n          id: '4',\n          first_name: 'Vanya',\n          last_name: 'Wharby',\n          email: 'vwharby3@mozilla.org',\n          gender: 'Male',\n          ip_address: '139.234.247.155',\n          Phone: '+30 349 556 7375',\n          Country: 'Greece',\n        },\n        {\n          id: '5',\n          first_name: 'Olenka',\n          last_name: 'Brawley',\n          email: 'obrawleyc@adobe.com',\n          gender: 'Female',\n          ip_address: '166.183.163.155',\n          Phone: '+62 841 824 0990',\n          Country: 'Indonesia',\n        },\n      ],\n    }\n  },\n}\n</script>\n";
var codeFormatterCallback = "\n<template>\n   <b-table\n    :fields=\"fields\"\n    :items=\"items\"\n  >\n    <template #cell(name)=\"data\">\n      <a :href=\"`#${data.value.replace(/[^a-z]+/i,'-').toLowerCase()}`\">{{ data.value }}</a>\n    </template>\n  </b-table>\n</template>\n\n<script>\nimport { BTable } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n  },\n  data() {\n    return {\n      fields: [\n        {\n          key: 'name',\n          label: 'Full Name',\n          formatter: value => `${value.first} ${value.last}`,\n        },\n        // A regular column\n        'age',\n        {\n          // A regular column with custom formatter\n          key: 'sex',\n          formatter: value => value.charAt(0).toUpperCase(),\n        },\n        {\n          // A virtual column with custom formatter\n          key: 'birthYear',\n          label: 'Birth Year',\n          formatter: (value, key, item) => new Date().getFullYear() - item.age,\n        },\n      ],\n      items: [\n        { name: { first: 'John', last: 'Doe' }, sex: 'Male', age: 42 },\n        { name: { first: 'Clemens', last: 'Doe' }, sex: 'Female', age: 36 },\n        { name: { first: 'Rubin', last: 'Kincade' }, sex: 'male', age: 73 },\n        { name: { first: 'Shirley', last: 'Partridge' }, sex: 'female', age: 62 },\n        { name: { first: 'Olenka', last: 'Brawley' }, sex: 'female', age: 26 },\n      ],\n    }\n  },\n}\n</script>\n";
var codeDataRendering = "\n<template>\n  <div>\n    <b-table\n      small\n      :fields=\"fields\"\n      :items=\"items\"\n      responsive=\"sm\"\n    >\n      <!-- A virtual column -->\n      <template #cell(index)=\"data\">\n        {{ data.index + 1 }}\n      </template>\n\n      <!-- A custom formatted column -->\n      <template #cell(name)=\"data\">\n        {{ data.value.first+' - '+ data.value.last }}\n      </template>\n\n      <!-- A custom formatted column -->\n      <template #cell(Popularity)=\"data\">\n        <b-progress\n          :value=\"data.value.value\"\n          max=\"100\"\n          :variant=\"data.value.variant\"\n          striped\n        />\n      </template>\n\n      <template #cell(order_status)=\"data\">\n        <b-badge\n          pill\n          :variant=\"data.value.variant\"\n        >\n          {{ data.value.status }}\n        </b-badge>\n      </template>\n\n      <!-- A virtual composite column -->\n      <template #cell(price)=\"data\">\n        {{ '$'+data.value }}\n      </template>\n\n      <!-- Optional default data cell scoped slot -->\n      <template #cell()=\"data\">\n        {{ data.value }}\n      </template>\n    </b-table>\n  </div>\n</template>\n\n<script>\nimport { BTable, BProgress, BBadge } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n    BProgress,\n    BBadge,\n  },\n  data() {\n    return {\n      fields: [\n        // A virtual column that doesn't exist in items\n        'index',\n        // A column that needs custom formatting\n        { key: 'name', label: 'Name' },\n        'Category',\n        // A regular column\n        { key: 'Popularity', label: 'Popularity' },\n        // A regular column\n        { key: 'order_status', label: 'Order Status' },\n        // A virtual column made up from two fields\n        { key: 'price', label: 'Price' },\n      ],\n      items: [\n        {\n          name: { first: 'Fitbit', last: 'Activity Tracker' },\n          Category: 'Fitness',\n          order_status: { status: 'Delivered', variant: 'success' },\n          Popularity: { value: 50, variant: 'success' },\n          price: 99.99,\n        },\n        {\n          name: { first: 'Fitbit ', last: 'Flex Wireless Activity' },\n          Category: 'Fitness',\n          order_status: { status: 'Pending', variant: 'primary' },\n          Popularity: { value: 36, variant: 'primary' },\n          price: 89.85,\n        },\n        {\n          name: { first: 'Fitbit', last: 'Sleep Tracker Wristband' },\n          Category: 'Fitness',\n          order_status: { status: 'Delivered', variant: 'success' },\n          Popularity: { value: 76, variant: 'success' },\n          price: 65.25,\n        },\n        {\n          name: { first: 'Fitbit', last: 'Sleep Wristband' },\n          Category: 'Fitness',\n          order_status: { status: 'On Hold', variant: 'warning' },\n          Popularity: { value: 55, variant: 'warning' },\n          price: 75.55,\n        },\n        {\n          name: { first: 'Fitbit', last: 'Clip for Zip Wireless Activity Trackers' },\n          Category: 'Fitness',\n          order_status: { status: 'Canceled', variant: 'danger' },\n          Popularity: { value: 75, variant: 'danger' },\n          price: 105.55,\n        },\n      ],\n    }\n  },\n}\n</script>\n";
var codeSticky = "\n<template>\n  <div>\n    <div class=\"d-flex mb-2\">\n      <b-form-checkbox\n        v-model=\"stickyHeader\"\n        class=\"vs-checkbox-con mr-1\"\n        plain\n      >\n        <span class=\"vs-checkbox\">\n          <span class=\"vs-checkbox--check\">\n            <i class=\"vs-icon feather icon-check\" />\n          </span>\n        </span>\n        <span class=\"vs-label\">Sticky header</span>\n      </b-form-checkbox>\n      <b-form-checkbox\n        v-model=\"noCollapse\"\n        class=\"vs-checkbox-con\"\n        plain\n      >\n        <span class=\"vs-checkbox\">\n          <span class=\"vs-checkbox--check\">\n            <i class=\"vs-icon feather icon-check\" />\n          </span>\n        </span>\n        <span class=\"vs-label\">No border collapse</span>\n      </b-form-checkbox>\n    </div>\n    <b-table\n      :sticky-header=\"stickyHeader\"\n      :no-border-collapse=\"noCollapse\"\n\n      responsive\n      :items=\"items\"\n      :fields=\"fields\"\n    >\n      <template #head(id)>\n        <div class=\"text-nowrap\">\n          Row ID\n        </div>\n      </template>\n\n      <template #head()=\"scope\">\n        <div class=\"text-nowrap\">\n          {{ scope.label }}\n        </div>\n      </template>\n\n      <template #cell(avatar)=\"data\">\n        <b-avatar\n          class=\"mr-1\"\n          :src=\"data.value\"\n        />\n      </template>\n\n      <template #cell(status)=\"data\">\n        <b-badge :variant=\"status[1][data.value]\">\n          {{ status[0][data.value] }}\n        </b-badge>\n      </template>\n\n      <template #cell()=\"data\">\n        <span class=\"text-nowrap\">{{ data.value }}</span>\n      </template>\n    </b-table>\n  </div>\n</template>\n\n<script>\nimport {\n  BTable, BFormCheckbox, BAvatar, BBadge,\n} from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n    BFormCheckbox,\n    BAvatar,\n    BBadge,\n  },\n  data() {\n    return {\n      stickyHeader: true,\n      noCollapse: false,\n      fields: [\n        {\n          key: 'id', stickyColumn: true, isRowHeader: true, variant: 'primary',\n        },\n        {\n          key: 'avatar', label: 'Avatar',\n        },\n        'full_name',\n        { key: 'post', stickyColumn: true, variant: 'info' },\n        'email',\n        'city',\n        'start_date',\n        'salary',\n        'age',\n        'experience',\n        { key: 'status', label: 'Status' },\n      ],\n      items: [\n        {\n          id: 1,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/10-small.png'),\n          full_name: \"Korrie O'Crevy\",\n          post: 'Nuclear Power Engineer',\n          email: 'kocrevy0@thetimes.co.uk',\n          city: 'Krasnosilka',\n          start_date: '09/23/2016',\n          salary: '$23896.35',\n          age: '61',\n          experience: '1 Year',\n          status: 2,\n        },\n        {\n          id: 2,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/1-small.png'),\n          full_name: 'Bailie Coulman',\n          post: 'VP Quality Control',\n          email: 'bcoulman1@yolasite.com',\n          city: 'Hinigaran',\n          start_date: '05/20/2018',\n          salary: '$13633.69',\n          age: '63',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 3,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Stella Ganderton',\n          post: 'Operator',\n          email: 'sganderton2@tuttocitta.it',\n          city: 'Golcowa',\n          start_date: '03/24/2018',\n          salary: '$13076.28',\n          age: '66',\n          experience: '6 Years',\n          status: 5,\n        },\n        {\n          id: 4,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/3-small.png'),\n          full_name: 'Dorolice Crossman',\n          post: 'Cost Accountant',\n          email: 'dcrossman3@google.co.jp',\n          city: 'Paquera',\n          start_date: '12/03/2017',\n          salary: '$12336.17',\n          age: '22',\n          experience: '2 Years',\n          status: 2,\n        },\n        {\n          id: 5,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/4-small.png'),\n          full_name: 'Harmonia Nisius',\n          post: 'Senior Cost Accountant',\n          email: 'hnisius4@gnu.org',\n          city: 'Lucan',\n          start_date: '08/25/2017',\n          salary: '$10909.52',\n          age: '33',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 6,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/5-small.png'),\n          full_name: 'Genevra Honeywood',\n          post: 'Geologist',\n          email: 'ghoneywood5@narod.ru',\n          city: 'Maofan',\n          start_date: '06/01/2017',\n          salary: '$17803.80',\n          age: '61',\n          experience: '1 Year',\n          status: 1,\n        },\n        {\n          id: 7,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/7-small.png'),\n          full_name: 'Eileen Diehn',\n          post: 'Environmental Specialist',\n          email: 'ediehn6@163.com',\n          city: 'Lampuyang',\n          start_date: '10/15/2017',\n          salary: '$18991.67',\n          age: '59',\n          experience: '9 Years',\n          status: 3,\n        },\n        {\n          id: 8,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Richardo Aldren',\n          post: 'Senior Sales Associate',\n          email: 'raldren7@mtv.com',\n          city: 'Skoghall',\n          start_date: '11/05/2016',\n          salary: '$19230.13',\n          age: '55',\n          experience: '5 Years',\n          status: 3,\n        },\n        {\n          id: 9,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/2-small.png'),\n          full_name: 'Allyson Moakler',\n          post: 'Safety Technician',\n          email: 'amoakler8@shareasale.com',\n          city: 'Mogilany',\n          start_date: '12/29/2018',\n          salary: '$11677.32',\n          age: '39',\n          experience: '9 Years',\n          status: 5,\n        },\n        {\n          id: 10,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/6-small.png'),\n          full_name: 'Merline Penhalewick',\n          post: 'Junior Executive',\n          email: 'mpenhalewick9@php.net',\n          city: 'Kanuma',\n          start_date: '04/19/2019',\n          salary: '$15939.52',\n          age: '23',\n          experience: '3 Years',\n          status: 2,\n        },\n      ],\n      status: [{\n        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',\n      },\n      {\n        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',\n      }],\n    }\n  },\n}\n</script>\n";
var codeRowDetailsSupport = "\n<template>\n  <div>\n    <b-table\n      :items=\"items\"\n      :fields=\"fields\"\n      striped\n      responsive\n    >\n      <template #cell(show_details)=\"row\">\n        <b-form-checkbox\n          v-model=\"row.detailsShowing\"\n          plain\n          class=\"vs-checkbox-con\"\n          @change=\"row.toggleDetails\"\n        >\n          <span class=\"vs-checkbox\">\n            <span class=\"vs-checkbox--check\">\n              <i class=\"vs-icon feather icon-check\" />\n            </span>\n          </span>\n          <span class=\"vs-label\">{{ row.detailsShowing ? 'Hide' : 'Show' }}</span>\n        </b-form-checkbox>\n      </template>\n\n      <template #row-details=\"row\">\n        <b-card>\n          <b-row class=\"mb-2\">\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>Full Name : </strong>{{ row.item.full_name }}\n            </b-col>\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>Post : </strong>{{ row.item.post }}\n            </b-col>\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>Email : </strong>{{ row.item.email }}\n            </b-col>\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>City : </strong>{{ row.item.city }}\n            </b-col>\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>Salary : </strong>{{ row.item.salary }}\n            </b-col>\n            <b-col\n              md=\"4\"\n              class=\"mb-1\"\n            >\n              <strong>Age : </strong>{{ row.item.age }}\n            </b-col>\n          </b-row>\n\n          <b-button\n            size=\"sm\"\n            variant=\"outline-secondary\"\n            @click=\"row.toggleDetails\"\n          >\n            Hide Details\n          </b-button>\n        </b-card>\n      </template>\n\n      <template #cell(avatar)=\"data\">\n        <b-avatar :src=\"data.value\" />\n      </template>\n\n       <template #cell(status)=\"data\">\n        <b-badge :variant=\"status[1][data.value]\">\n          {{ status[0][data.value] }}\n        </b-badge>\n      </template>\n    </b-table>\n  </div>\n</template>\n\n<script>\nimport {\n  BTable, BFormCheckbox, BButton, BCard, BRow, BCol, BAvatar, BBadge,\n} from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n    BButton,\n    BFormCheckbox,\n    BCard,\n    BRow,\n    BCol,\n    BBadge,\n    BAvatar,\n  },\n  data() {\n    return {\n      fields: ['show_details', 'id', { key: 'avatar', label: 'Avatar' }, 'full_name', 'post', 'email', 'city', 'start_date', 'salary', 'age', 'experience', { key: 'status', label: 'Status' }],\n      items: [\n        {\n          id: 1,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/10-small.png'),\n          full_name: \"Korrie O'Crevy\",\n          post: 'Nuclear Power Engineer',\n          email: 'kocrevy0@thetimes.co.uk',\n          city: 'Krasnosilka',\n          start_date: '09/23/2016',\n          salary: '$23896.35',\n          age: '61',\n          experience: '1 Year',\n          status: 2,\n        },\n        {\n          id: 2,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/1-small.png'),\n          full_name: 'Bailie Coulman',\n          post: 'VP Quality Control',\n          email: 'bcoulman1@yolasite.com',\n          city: 'Hinigaran',\n          start_date: '05/20/2018',\n          salary: '$13633.69',\n          age: '63',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 3,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Stella Ganderton',\n          post: 'Operator',\n          email: 'sganderton2@tuttocitta.it',\n          city: 'Golcowa',\n          start_date: '03/24/2018',\n          salary: '$13076.28',\n          age: '66',\n          experience: '6 Years',\n          status: 5,\n        },\n        {\n          id: 4,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/3-small.png'),\n          full_name: 'Dorolice Crossman',\n          post: 'Cost Accountant',\n          email: 'dcrossman3@google.co.jp',\n          city: 'Paquera',\n          start_date: '12/03/2017',\n          salary: '$12336.17',\n          age: '22',\n          experience: '2 Years',\n          status: 2,\n        },\n        {\n          id: 5,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/4-small.png'),\n          full_name: 'Harmonia Nisius',\n          post: 'Senior Cost Accountant',\n          email: 'hnisius4@gnu.org',\n          city: 'Lucan',\n          start_date: '08/25/2017',\n          salary: '$10909.52',\n          age: '33',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 6,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/5-small.png'),\n          full_name: 'Genevra Honeywood',\n          post: 'Geologist',\n          email: 'ghoneywood5@narod.ru',\n          city: 'Maofan',\n          start_date: '06/01/2017',\n          salary: '$17803.80',\n          age: '61',\n          experience: '1 Year',\n          status: 1,\n        },\n        {\n          id: 7,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/7-small.png'),\n          full_name: 'Eileen Diehn',\n          post: 'Environmental Specialist',\n          email: 'ediehn6@163.com',\n          city: 'Lampuyang',\n          start_date: '10/15/2017',\n          salary: '$18991.67',\n          age: '59',\n          experience: '9 Years',\n          status: 3,\n        },\n        {\n          id: 8,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Richardo Aldren',\n          post: 'Senior Sales Associate',\n          email: 'raldren7@mtv.com',\n          city: 'Skoghall',\n          start_date: '11/05/2016',\n          salary: '$19230.13',\n          age: '55',\n          experience: '5 Years',\n          status: 3,\n        },\n        {\n          id: 9,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/2-small.png'),\n          full_name: 'Allyson Moakler',\n          post: 'Safety Technician',\n          email: 'amoakler8@shareasale.com',\n          city: 'Mogilany',\n          start_date: '12/29/2018',\n          salary: '$11677.32',\n          age: '39',\n          experience: '9 Years',\n          status: 5,\n        },\n        {\n          id: 10,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/6-small.png'),\n          full_name: 'Merline Penhalewick',\n          post: 'Junior Executive',\n          email: 'mpenhalewick9@php.net',\n          city: 'Kanuma',\n          start_date: '04/19/2019',\n          salary: '$15939.52',\n          age: '23',\n          experience: '3 Years',\n          status: 2,\n        },\n      ],\n      status: [{\n        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',\n      },\n      {\n        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',\n      }],\n    }\n  },\n}\n</script>\n";
var codeRowSelectSupport = "\n<template>\n  <div>\n    <b-form-group\n      label=\"Selection mode:\"\n      label-cols-md=\"4\"\n    >\n      <!-- <b-form-select\n        v-model=\"selectMode\"\n        :options=\"modes\"\n      /> -->\n      <v-select\n        v-model=\"selectMode\"\n        label=\"title\"\n        :options=\"modes\"\n      />\n    </b-form-group>\n\n    <b-table\n      ref=\"selectableTable\"\n      selectable\n      :select-mode=\"selectMode\"\n      :items=\"items\"\n      :fields=\"fields\"\n      responsive=\"sm\"\n      @row-selected=\"onRowSelected\"\n    >\n      <!-- Example scoped slot for select state illustrative purposes -->\n      <template #cell(selected)=\"{ rowSelected }\">\n        <template v-if=\"rowSelected\">\n          <i class=\"feather icon-disc primary\" />\n        </template>\n\n        <template v-else>\n          <i class=\"feather icon-circle\" />\n        </template>\n      </template>\n\n      <template #cell(avatar)=\"data\">\n        <b-avatar :src=\"data.value\" />\n      </template>\n\n       <template #cell(status)=\"data\">\n        <b-badge :variant=\"status[1][data.value]\">\n          {{ status[0][data.value] }}\n        </b-badge>\n      </template>\n    </b-table>\n\n    <b-button\n      size=\"sm\"\n      class=\"mr-1\"\n      variant=\"primary\"\n      @click=\"selectAllRows\"\n    >\n      Select all\n    </b-button>\n    <b-button\n      size=\"sm\"\n      class=\"mr-1\"\n      variant=\"primary\"\n      @click=\"clearSelected\"\n    >\n      Clear selected\n    </b-button>\n    <b-button\n      size=\"sm\"\n      variant=\"primary\"\n      class=\"mr-1\"\n      @click=\"selectThirdRow\"\n    >\n      Select 3rd row\n    </b-button>\n    <b-button\n      variant=\"primary\"\n      size=\"sm\"\n      class=\"mr-1\"\n      @click=\"unselectThirdRow\"\n    >\n      Unselect 3rd row\n    </b-button>\n  </div>\n</template>\n\n<script>\nimport {\n  BTable, BButton, BFormGroup, BAvatar, BBadge,\n} from 'bootstrap-vue'\nimport vSelect from 'vue-select'\n\nexport default {\n  components: {\n    BTable,\n    BButton,\n    BFormGroup,\n    BAvatar,\n    BBadge,\n    vSelect,\n  },\n  data() {\n    return {\n      // selectedCheck: false,\n      modes: ['multi', 'single', 'range'],\n      fields: ['selected', 'id', { key: 'avatar', label: 'Avatar' }, 'full_name', 'post', 'email', 'city', 'start_date', 'salary', 'age', 'experience', { key: 'status', label: 'Status' }],\n      items: [\n        {\n          id: 1,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/10-small.png'),\n          full_name: \"Korrie O'Crevy\",\n          post: 'Nuclear Power Engineer',\n          email: 'kocrevy0@thetimes.co.uk',\n          city: 'Krasnosilka',\n          start_date: '09/23/2016',\n          salary: '$23896.35',\n          age: '61',\n          experience: '1 Year',\n          status: 2,\n        },\n        {\n          id: 2,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/1-small.png'),\n          full_name: 'Bailie Coulman',\n          post: 'VP Quality Control',\n          email: 'bcoulman1@yolasite.com',\n          city: 'Hinigaran',\n          start_date: '05/20/2018',\n          salary: '$13633.69',\n          age: '63',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 3,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Stella Ganderton',\n          post: 'Operator',\n          email: 'sganderton2@tuttocitta.it',\n          city: 'Golcowa',\n          start_date: '03/24/2018',\n          salary: '$13076.28',\n          age: '66',\n          experience: '6 Years',\n          status: 5,\n        },\n        {\n          id: 4,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/3-small.png'),\n          full_name: 'Dorolice Crossman',\n          post: 'Cost Accountant',\n          email: 'dcrossman3@google.co.jp',\n          city: 'Paquera',\n          start_date: '12/03/2017',\n          salary: '$12336.17',\n          age: '22',\n          experience: '2 Years',\n          status: 2,\n        },\n        {\n          id: 5,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/4-small.png'),\n          full_name: 'Harmonia Nisius',\n          post: 'Senior Cost Accountant',\n          email: 'hnisius4@gnu.org',\n          city: 'Lucan',\n          start_date: '08/25/2017',\n          salary: '$10909.52',\n          age: '33',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 6,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/5-small.png'),\n          full_name: 'Genevra Honeywood',\n          post: 'Geologist',\n          email: 'ghoneywood5@narod.ru',\n          city: 'Maofan',\n          start_date: '06/01/2017',\n          salary: '$17803.80',\n          status: [{\n        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',\n      },\n      {\n        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',\n      }],age: '61',\n          experience: '1 Year',\n          status: 1,\n        },\n        {\n          id: 7,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/7-small.png'),\n          full_name: 'Eileen Diehn',\n          post: 'Environmental Specialist',\n          email: 'ediehn6@163.com',\n          city: 'Lampuyang',\n          start_date: '10/15/2017',\n          salary: '$18991.67',\n          age: '59',\n          experience: '9 Years',\n          status: 3,\n        },\n        {\n          id: 8,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Richardo Aldren',\n          post: 'Senior Sales Associate',\n          email: 'raldren7@mtv.com',\n          city: 'Skoghall',\n          start_date: '11/05/2016',\n          salary: '$19230.13',\n          age: '55',\n          experience: '5 Years',\n          status: 3,\n        },\n        {\n          id: 9,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/2-small.png'),\n          full_name: 'Allyson Moakler',\n          post: 'Safety Technician',\n          email: 'amoakler8@shareasale.com',\n          city: 'Mogilany',\n          start_date: '12/29/2018',\n          salary: '$11677.32',\n          age: '39',\n          experience: '9 Years',\n          status: 5,\n        },\n        {\n          id: 10,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/6-small.png'),\n          full_name: 'Merline Penhalewick',\n          post: 'Junior Executive',\n          email: 'mpenhalewick9@php.net',\n          city: 'Kanuma',\n          start_date: '04/19/2019',\n          salary: '$15939.52',\n          age: '23',\n          experience: '3 Years',\n          status: 2,\n        },\n      ],\n      status: [{\n        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',\n      },\n      {\n        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',\n      }],\n      selectMode: 'multi',\n      selected: [],\n    }\n  },\n  methods: {\n    onRowSelected(items) {\n      this.selected = items\n    },\n    selectAllRows() {\n      this.$refs.selectableTable.selectAllRows()\n    },\n    clearSelected() {\n      this.$refs.selectableTable.clearSelected()\n    },\n    selectThirdRow() {\n      // Rows are indexed from 0, so the third row is index 2\n      this.$refs.selectableTable.selectRow(2)\n    },\n    unselectThirdRow() {\n      // Rows are indexed from 0, so the third row is index 2\n      this.$refs.selectableTable.unselectRow(2)\n    },\n  },\n}\n</script>\n\n<style lang=\"scss\">\n.b-table-selectable{\n  .feather{\n    font-size: 1.3rem;\n  }\n}\n\n</style>\n";
var codeKitchenSink = "\n<template>\n  <b-row>\n    <b-col\n      md=\"2\"\n      sm=\"4\"\n      class=\"my-1\"\n    >\n      <b-form-group\n        class=\"mb-0\"\n      >\n        <label class=\"d-inline-block text-sm-left mr-50\">Per page</label>\n        <b-form-select\n          id=\"perPageSelect\"\n          v-model=\"perPage\"\n          size=\"sm\"\n          :options=\"pageOptions\"\n          class=\"w-50\"\n        />\n      </b-form-group>\n    </b-col>\n    <b-col\n      md=\"4\"\n      sm=\"8\"\n      class=\"my-1\"\n    >\n      <b-form-group\n        label=\"Sort\"\n        label-cols-sm=\"3\"\n        label-align-sm=\"right\"\n        label-size=\"sm\"\n        label-for=\"sortBySelect\"\n        class=\"mb-0\"\n      >\n        <b-input-group size=\"sm\">\n          <b-form-select\n            id=\"sortBySelect\"\n            v-model=\"sortBy\"\n            :options=\"sortOptions\"\n            class=\"w-75\"\n          >\n            <template v-slot:first>\n              <option value=\"\">\n                -- none --\n              </option>\n            </template>\n          </b-form-select>\n          <b-form-select\n            v-model=\"sortDesc\"\n            size=\"sm\"\n            :disabled=\"!sortBy\"\n            class=\"w-25\"\n          >\n            <option :value=\"false\">\n              Asc\n            </option>\n            <option :value=\"true\">\n              Desc\n            </option>\n          </b-form-select>\n        </b-input-group>\n      </b-form-group>\n    </b-col>\n    <b-col\n      md=\"6\"\n      class=\"my-1\"\n    >\n      <b-form-group\n        label=\"Filter\"\n        label-cols-sm=\"3\"\n        label-align-sm=\"right\"\n        label-size=\"sm\"\n        label-for=\"filterInput\"\n        class=\"mb-0\"\n      >\n        <b-input-group size=\"sm\">\n          <b-form-input\n            id=\"filterInput\"\n            v-model=\"filter\"\n            type=\"search\"\n            placeholder=\"Type to Search\"\n          />\n          <b-input-group-append>\n            <b-button\n              :disabled=\"!filter\"\n              @click=\"filter = ''\"\n            >\n              Clear\n            </b-button>\n          </b-input-group-append>\n        </b-input-group>\n      </b-form-group>\n    </b-col>\n\n    <b-col cols=\"12\">\n      <b-table\n        striped\n        hover\n        responsive\n        :per-page=\"perPage\"\n        :current-page=\"currentPage\"\n        :items=\"items\"\n        :fields=\"fields\"\n        :sort-by.sync=\"sortBy\"\n        :sort-desc.sync=\"sortDesc\"\n        :sort-direction=\"sortDirection\"\n        :filter=\"filter\"\n        :filter-included-fields=\"filterOn\"\n        @filtered=\"onFiltered\"\n      >\n        <template #cell(avatar)=\"data\">\n          <b-avatar :src=\"data.value\" />\n        </template>\n\n        <template #cell(status)=\"data\">\n          <b-badge :variant=\"status[1][data.value]\">\n            {{ status[0][data.value] }}\n          </b-badge>\n        </template>\n      </b-table>\n    </b-col>\n\n    <b-col\n      cols=\"12\"\n    >\n      <b-pagination\n        v-model=\"currentPage\"\n        :total-rows=\"totalRows\"\n        :per-page=\"perPage\"\n        align=\"center\"\n        size=\"sm\"\n        class=\"my-0\"\n      />\n    </b-col>\n  </b-row>\n</template>\n\n<script>\nimport {\n  BTable, BAvatar, BBadge, BRow, BCol, BFormGroup, BFormSelect, BPagination, BInputGroup, BFormInput, BInputGroupAppend, BButton,\n} from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BTable,\n    BAvatar,\n    BBadge,\n    BRow,\n    BCol,\n    BFormGroup,\n    BFormSelect,\n    BPagination,\n    BInputGroup,\n    BFormInput,\n    BInputGroupAppend,\n    BButton,\n  },\n  data() {\n    return {\n      perPage: 5,\n      pageOptions: [3, 5, 10],\n      totalRows: 1,\n      currentPage: 1,\n      sortBy: '',\n      sortDesc: false,\n      sortDirection: 'asc',\n      filter: null,\n      filterOn: [],\n      infoModal: {\n        id: 'info-modal',\n        title: '',\n        content: '',\n      },\n      fields: [\n        {\n          key: 'id', label: 'Id',\n        },\n        {\n          key: 'avatar', label: 'Avatar',\n        },\n        { key: 'full_name', label: 'Full Name', sortable: true },\n        { key: 'post', label: 'Post', sortable: true },\n        'email',\n        'city',\n        'start_date',\n        'salary',\n        'age',\n        'experience',\n        { key: 'status', label: 'Status', sortable: true },\n      ],\n      items: [\n        {\n          id: 1,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/10-small.png'),\n          full_name: \"Korrie O'Crevy\",\n          post: 'Nuclear Power Engineer',\n          email: 'kocrevy0@thetimes.co.uk',\n          city: 'Krasnosilka',\n          start_date: '09/23/2016',\n          salary: '$23896.35',\n          age: '61',\n          experience: '1 Year',\n          status: 2,\n        },\n        {\n          id: 2,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/1-small.png'),\n          full_name: 'Bailie Coulman',\n          post: 'VP Quality Control',\n          email: 'bcoulman1@yolasite.com',\n          city: 'Hinigaran',\n          start_date: '05/20/2018',\n          salary: '$13633.69',\n          age: '63',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 3,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Stella Ganderton',\n          post: 'Operator',\n          email: 'sganderton2@tuttocitta.it',\n          city: 'Golcowa',\n          start_date: '03/24/2018',\n          salary: '$13076.28',\n          age: '66',\n          experience: '6 Years',\n          status: 5,\n        },\n        {\n          id: 4,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/3-small.png'),\n          full_name: 'Dorolice Crossman',\n          post: 'Cost Accountant',\n          email: 'dcrossman3@google.co.jp',\n          city: 'Paquera',\n          start_date: '12/03/2017',\n          salary: '$12336.17',\n          age: '22',\n          experience: '2 Years',\n          status: 2,\n        },\n        {\n          id: 5,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/4-small.png'),\n          full_name: 'Harmonia Nisius',\n          post: 'Senior Cost Accountant',\n          email: 'hnisius4@gnu.org',\n          city: 'Lucan',\n          start_date: '08/25/2017',\n          salary: '$10909.52',\n          age: '33',\n          experience: '3 Years',\n          status: 2,\n        },\n        {\n          id: 6,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/5-small.png'),\n          full_name: 'Genevra Honeywood',\n          post: 'Geologist',\n          email: 'ghoneywood5@narod.ru',\n          city: 'Maofan',\n          start_date: '06/01/2017',\n          salary: '$17803.80',\n          age: '61',\n          experience: '1 Year',\n          status: 1,\n        },\n        {\n          id: 7,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/7-small.png'),\n          full_name: 'Eileen Diehn',\n          post: 'Environmental Specialist',\n          email: 'ediehn6@163.com',\n          city: 'Lampuyang',\n          start_date: '10/15/2017',\n          salary: '$18991.67',\n          age: '59',\n          experience: '9 Years',\n          status: 3,\n        },\n        {\n          id: 8,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/9-small.png'),\n          full_name: 'Richardo Aldren',\n          post: 'Senior Sales Associate',\n          email: 'raldren7@mtv.com',\n          city: 'Skoghall',\n          start_date: '11/05/2016',\n          salary: '$19230.13',\n          age: '55',\n          experience: '5 Years',\n          status: 3,\n        },\n        {\n          id: 9,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/2-small.png'),\n          full_name: 'Allyson Moakler',\n          post: 'Safety Technician',\n          email: 'amoakler8@shareasale.com',\n          city: 'Mogilany',\n          start_date: '12/29/2018',\n          salary: '$11677.32',\n          age: '39',\n          experience: '9 Years',\n          status: 5,\n        },\n        {\n          id: 10,\n          // eslint-disable-next-line global-require\n          avatar: require('@/assets/images/avatars/6-small.png'),\n          full_name: 'Merline Penhalewick',\n          post: 'Junior Executive',\n          email: 'mpenhalewick9@php.net',\n          city: 'Kanuma',\n          start_date: '04/19/2019',\n          salary: '$15939.52',\n          age: '23',\n          experience: '3 Years',\n          status: 2,\n        },\n      ],\n      status: [{\n        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',\n      },\n      {\n        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',\n      }],\n    }\n  },\n  computed: {\n    sortOptions() {\n      // Create an options list from our fields\n      return this.fields\n        .filter(f => f.sortable)\n        .map(f => ({ text: f.label, value: f.key }))\n    },\n  },\n  mounted() {\n    // Set the initial number of items\n    this.totalRows = this.items.length\n  },\n  methods: {\n    info(item, index, button) {\n      this.infoModal.title = `Row index: ${index}`\n      this.infoModal.content = JSON.stringify(item, null, 2)\n      this.$root.$emit('bv::show::modal', this.infoModal.id, button)\n    },\n    resetInfoModal() {\n      this.infoModal.title = ''\n      this.infoModal.content = ''\n    },\n    onFiltered(filteredItems) {\n      // Trigger pagination to update the number of buttons/pages due to filtering\n      this.totalRows = filteredItems.length\n      this.currentPage = 1\n    },\n  },\n}\n</script>\n";
var codeLight = "\n<template>\n  <b-table-lite\n    hover\n    :items=\"items\"\n  />\n</template>\n\n<script>\nimport BCardCode from '@core/components/b-card-code/BCardCode.vue'\nimport { BTableLite } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BCardCode,\n    BTableLite,\n  },\n  data() {\n    return {\n      items: [\n        {\n          age: 40, first_name: 'Dickerson', last_name: 'Macdonald', Occupation: 'Job',\n        },\n        {\n          age: 21, first_name: 'Larsen', last_name: 'Shaw', Occupation: 'Job',\n        },\n        {\n          age: 89, first_name: 'Geneva', last_name: 'Wilson', Occupation: 'Bussiness',\n        },\n        {\n          age: 38, first_name: 'Jami', last_name: 'Carney', Occupation: 'Bussiness',\n        },\n        {\n          age: 40, first_name: 'James', last_name: 'Thomson', Occupation: 'Job',\n        },\n      ],\n    }\n  },\n}\n</script>\n";
var codeSimple = "\n<template>\n  <div>\n    <b-table-simple\n      hover\n      small\n      caption-top\n      responsive\n    >\n      <caption>Items sold in August, grouped by Country and City:</caption>\n      <colgroup><col><col></colgroup>\n      <colgroup><col><col><col></colgroup>\n      <colgroup><col><col></colgroup>\n      <b-thead head-variant=\"light\">\n        <b-tr>\n          <b-th colspan=\"2\">\n            Region\n          </b-th>\n          <b-th colspan=\"3\">\n            Clothes\n          </b-th>\n          <b-th colspan=\"2\">\n            Accessories\n          </b-th>\n        </b-tr>\n        <b-tr>\n          <b-th>Country</b-th>\n          <b-th>City</b-th>\n          <b-th>Trousers</b-th>\n          <b-th>Skirts</b-th>\n          <b-th>Dresses</b-th>\n          <b-th>Bracelets</b-th>\n          <b-th>Rings</b-th>\n        </b-tr>\n      </b-thead>\n      <b-tbody>\n        <b-tr>\n          <b-th rowspan=\"3\">\n            Belgium\n          </b-th>\n          <b-th class=\"text-right\">\n            Antwerp\n          </b-th>\n          <b-td>56</b-td>\n          <b-td>22</b-td>\n          <b-td>43</b-td>\n          <b-td variant=\"success\">\n            72\n          </b-td>\n          <b-td>23</b-td>\n        </b-tr>\n        <b-tr>\n          <b-th class=\"text-right\">\n            Gent\n          </b-th>\n          <b-td>46</b-td>\n          <b-td variant=\"warning\">\n            18\n          </b-td>\n          <b-td>50</b-td>\n          <b-td>61</b-td>\n          <b-td variant=\"danger\">\n            15\n          </b-td>\n        </b-tr>\n        <b-tr>\n          <b-th class=\"text-right\">\n            Brussels\n          </b-th>\n          <b-td>51</b-td>\n          <b-td>27</b-td>\n          <b-td>38</b-td>\n          <b-td>69</b-td>\n          <b-td>28</b-td>\n        </b-tr>\n        <b-tr>\n          <b-th rowspan=\"2\">\n            The Netherlands\n          </b-th>\n          <b-th class=\"text-right\">\n            Amsterdam\n          </b-th>\n          <b-td variant=\"success\">\n            89\n          </b-td>\n          <b-td>34</b-td>\n          <b-td>69</b-td>\n          <b-td>85</b-td>\n          <b-td>38</b-td>\n        </b-tr>\n        <b-tr>\n          <b-th class=\"text-right\">\n            Utrecht\n          </b-th>\n          <b-td>80</b-td>\n          <b-td variant=\"danger\">\n            12\n          </b-td>\n          <b-td>43</b-td>\n          <b-td>36</b-td>\n          <b-td variant=\"warning\">\n            19\n          </b-td>\n        </b-tr>\n      </b-tbody>\n      <b-tfoot>\n        <b-tr>\n          <b-td\n            colspan=\"7\"\n            variant=\"secondary\"\n            class=\"text-right\"\n          >\n            Total Rows: <b>5</b>\n          </b-td>\n        </b-tr>\n      </b-tfoot>\n    </b-table-simple>\n  </div>\n</template>\n";

/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTable.vue":
/*!*************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTable.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./employeeTable.vue?vue&type=template&id=5625242f& */ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f&");
/* harmony import */ var _employeeTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./employeeTable.vue?vue&type=script&lang=js& */ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./employeeTable.vue?vue&type=style&index=0&lang=scss& */ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _employeeTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "frontend/src/views/table/bs-table/employeeTable.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTable.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTable.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f&":
/*!********************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTable.vue?vue&type=template&id=5625242f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTable_vue_vue_type_template_id_5625242f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTableView.vue":
/*!*****************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTableView.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./employeeTableView.vue?vue&type=template&id=2ace44f4& */ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4&");
/* harmony import */ var _employeeTableView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./employeeTableView.vue?vue&type=script&lang=js& */ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./employeeTableView.vue?vue&type=style&index=0&lang=css& */ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _employeeTableView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "frontend/src/views/table/bs-table/employeeTableView.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTableView.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&":
/*!**************************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css& ***!
  \**************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTableView.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4&":
/*!************************************************************************************************!*\
  !*** ./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTableView.vue?vue&type=template&id=2ace44f4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_employeeTableView_vue_vue_type_template_id_2ace44f4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap-vue */ "./frontend/node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _employeeTableView_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./employeeTableView.vue */ "./frontend/src/views/table/bs-table/employeeTableView.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* import TableBasic from './TableBasic.vue'
import TableStyleOptions from './TableStyleOptions.vue'
import TableRowColStyle from './TableRowColStyle.vue'
import TableResponsive from './TableResponsive.vue'
import TableFormatterCallback from './TableFormatterCallback.vue'
import TableCustomRender from './TableCustomRender.vue'
import TableStickyColumnsHeaders from './TableStickyColumnsHeaders.vue' */


/*
import TableRowSelectSupport from './TableRowSelectSupport.vue'
import TableLightWeight from './TableLightWeight.vue'
import TableSimple from './TableSimple.vue'
import TableKitchenSink from './TableKitchenSink.vue' */

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCol"],

    /* TableBasic,
    TableStyleOptions,
    TableRowColStyle,
    TableResponsive,
    TableFormatterCallback,
    TableCustomRender,
    TableStickyColumnsHeaders, */
    TableRowdetailsSupport: _employeeTableView_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
    /* TableRowSelectSupport,
    TableLightWeight,
    TableSimple,
    TableKitchenSink, */

  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _core_components_b_card_code_BCardCode_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @core/components/b-card-code/BCardCode.vue */ "./frontend/src/@core/components/b-card-code/BCardCode.vue");
/* harmony import */ var _axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @axios */ "./frontend/src/libs/axios.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-select */ "./frontend/node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! bootstrap-vue */ "./frontend/node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var vue_ripple_directive__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-ripple-directive */ "./frontend/node_modules/vue-ripple-directive/src/ripple.js");
/* harmony import */ var _diracleo_vue_enlargeable_image__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @diracleo/vue-enlargeable-image */ "./frontend/node_modules/@diracleo/vue-enlargeable-image/dist/vue-enlargeable-image.esm.js");
/* harmony import */ var _libs_acl_config__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @/libs/acl/config */ "./frontend/src/libs/acl/config.js");
/* harmony import */ var _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @core/components/toastification/ToastificationContent.vue */ "./frontend/src/@core/components/toastification/ToastificationContent.vue");
/* harmony import */ var _code__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./code */ "./frontend/src/views/table/bs-table/code.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//









/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BCardCode: _core_components_b_card_code_BCardCode_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    BTable: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTable"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BButton"],
    BFormCheckbox: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormCheckbox"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCard"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCol"],
    BBadge: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BBadge"],
    BAvatar: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BAvatar"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BImg"],
    EnlargeableImage: _diracleo_vue_enlargeable_image__WEBPACK_IMPORTED_MODULE_5__["default"],
    BTabs: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTabs"],
    BTab: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTab"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCardText"],
    BPagination: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BPagination"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormGroup"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormInput"],
    BFormSelect: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormSelect"],
    BDropdown: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BDropdown"],
    BDropdownItem: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BDropdownItem"],
    BInputGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BInputGroup"],
    BInputGroupAppend: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BInputGroupAppend"],
    BInputGroupPrepend: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BInputGroupPrepend"],
    BFormDatepicker: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormDatepicker"],
    BFormFile: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormFile"],
    BFormTextarea: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormTextarea"],
    BFormRadio: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormRadio"],
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BForm"],
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_2___default.a,
    BFormCheckboxGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormCheckboxGroup"],
    BFormRating: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormRating"],
    BListGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BListGroup"],
    BListGroupItem: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BListGroupItem"],
    BPopover: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BPopover"],
    BSpinner: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BSpinner"]
  },
  directives: {
    Ripple: vue_ripple_directive__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  data: function data() {
    return {
      popoverShow: false,
      exportLimit: "",
      iqama_expiry_date: "",
      vendor: "",
      client: "",
      exportStatus: "",
      isBusy: true,
      fields: [{
        key: "Options",
        thClass: "customHead"
      }, {
        key: "emp_id",
        label: "GL ID",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "name",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "iqama_no",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "iqamaExpireVarient",
        label: "Iqama Expiry",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "vendor",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "client",
        sortable: true,
        thClass: "customHead"
      }, {
        key: "status",
        sortable: true,
        thClass: "customHead"
      }],
      items: [],
      emp_detail: [{
        age: 40,
        first_name: "Dickerson",
        last_name: "Macdonald"
      }],

      /* eslint-disable global-require */
      status: [{
        1: "Current",
        2: "Professional",
        3: "Rejected",
        4: "Resigned",
        5: "Applied"
      }, {
        1: "light-primary",
        2: "light-success",
        3: "light-danger",
        4: "light-warning",
        5: "light-info"
      }],
      codeRowDetailsSupport: _code__WEBPACK_IMPORTED_MODULE_8__["codeRowDetailsSupport"],
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
        misconduct: ""
      },
      nationalities: [{
        value: "select_value",
        text: "Select Value"
      }, {
        value: "Bangladesh",
        text: "Bangladesh"
      }, {
        value: "India",
        text: "India"
      }, {
        value: "Pakistan",
        text: "Pakistan"
      }, {
        value: "Nepal",
        text: "Nepal"
      }, {
        value: "Sirilanka",
        text: "Sirilanka"
      }, {
        value: "Philippine",
        text: "Philippine"
      }, {
        value: "Sudan",
        text: "Sudan"
      }],
      religions: [{
        value: "select_value",
        text: "Select Value"
      }, {
        value: "Islam",
        text: "Islam"
      }, {
        value: "Hindu",
        text: "Hindu"
      }, {
        value: "Buddhist",
        text: "Buddhist"
      }, {
        value: "Christian",
        text: "Christian"
      }, {
        value: "Other",
        text: "Other"
      }],
      professions: [],
      vendors: [],
      clients: [],
      statuses: [{
        value: "select_value",
        text: "Select Value"
      }, {
        value: "Available",
        text: "Available"
      }, {
        value: "Deployed",
        text: "Deployed"
      }, {
        value: "Runaway",
        text: "Runaway"
      }, {
        value: "Exit",
        text: "Exit"
      }, {
        value: "Terminated",
        text: "Terminated"
      }, {
        value: "Vacation",
        text: "Vacation"
      }, {
        value: "Return to Vendor",
        text: "Return to Vendor"
      }],
      accommodations: [],
      lang_engs: [{
        value: "1",
        text: "1"
      }, {
        value: "2",
        text: "2"
      }, {
        value: "3",
        text: "3"
      }, {
        value: "4",
        text: "4"
      }, {
        value: "5",
        text: "5"
      }],
      lang_ars: [{
        value: "1",
        text: "1"
      }, {
        value: "2",
        text: "2"
      }, {
        value: "3",
        text: "3"
      }, {
        value: "4",
        text: "4"
      }, {
        value: "5",
        text: "5"
      }],
      lang_hinds: [{
        value: "1",
        text: "1"
      }, {
        value: "2",
        text: "2"
      }, {
        value: "3",
        text: "3"
      }, {
        value: "4",
        text: "4"
      }, {
        value: "5",
        text: "5"
      }],
      appearance_presentables: [{
        value: "1",
        text: "1"
      }, {
        value: "2",
        text: "2"
      }, {
        value: "3",
        text: "3"
      }, {
        value: "4",
        text: "4"
      }, {
        value: "5",
        text: "5"
      }]
    };
  },
  mounted: function mounted() {
    this.getEmployees();
    this.getVendors();
    this.getClients();
    this.getProfessions();
    this.getAccommodations();
  },
  methods: {
    closeSearch: function closeSearch() {
      this.$refs.searchModel.hide();
    },
    closeExpot: function closeExpot() {
      this.$refs.exportModel.hide();
    },
    resetExport: function resetExport() {
      this.exportLimit = "";
      this.iqama_expiry_date = "";
      this.vendor = "";
      this.client = "";
      this.status = "";
      this.searchTerm = "";
    },
    resetSearch: function resetSearch() {
      var _this = this;

      Object.entries(this.formValues).forEach(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            key = _ref2[0],
            value = _ref2[1];

        if (key == "skills") {
          _this.formValues[key] = [];
        } else if (key == "lang_eng" || key == "lang_ar" || key == "lang_hind" || key == "appearance_presentable") {
          _this.formValues[key] = null;
        } else {
          _this.formValues[key] = "";
        }
      });
      console.log(this.formValues);
    },
    onClose: function onClose() {
      this.popoverShow = false;
    },
    statusVariant: function statusVariant(status) {
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
    deleteEmployee: function deleteEmployee(emp_id) {
      var _this2 = this;

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
      }).then(function (result) {
        if (result.value) {
          _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/deleteEmployee", {
            params: {
              emp_id: emp_id
            }
          }).then(function (response) {
            if (response.data.hasOwnProperty("success")) {
              if (response.data.success === true) {
                _this2.getEmployees();

                _this2.$toast({
                  component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
                  position: "top-right",
                  props: {
                    title: "Employee Deleted",
                    icon: "EditIcon",
                    variant: "success"
                  }
                });

                console.log("Employee deleted");
              } else {
                _this2.$toast({
                  component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
                  position: "top-right",
                  props: {
                    title: "Error",
                    icon: "AlertCircleIcon",
                    variant: "danger",
                    text: "Something went wrong, try again later"
                  }
                });
              }
            } else {
              _this2.$toast({
                component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
                position: "top-right",
                props: {
                  title: "Error",
                  icon: "AlertCircleIcon",
                  variant: "danger",
                  text: "Something went wrong, try again later"
                }
              });
            }
          })["catch"](function (error) {
            console.error(error);
          });
        }
      });
    },
    isPdf: function isPdf(url) {
      if (url.substr(url.lastIndexOf(".") + 1) == "pdf") {
        return true;
      }

      return false;
    },
    getEmployees: function getEmployees() {
      var _this3 = this;

      // this.$ability.update(initialAbility)
      // console.log('abilities',this.$ability.can('add/copy', 'employees'))
      this.isBusy = true;
      _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/getemployee", {
        params: {
          searchTerm: this.searchTerm,
          page_no: this.page,
          advanceSearch: this.formValues
        }
      }).then(function (response) {
        console.log("response", response.data.data);
        _this3.items = response.data.data;
        _this3.count = response.data.totalRows;
        _this3.isBusy = false; // TODO
      })["catch"](function (error) {
        console.error(error);
      });
    },
    handlePageChange: function handlePageChange(value) {
      this.page = value;
      this.isBusy = true;
      this.getEmployees();
    },
    searchTimeOut: function searchTimeOut() {
      var _this4 = this;

      var timeout = null;
      clearTimeout(timeout); // Make a new timeout set to go off in 800ms

      timeout = setTimeout(function () {
        _this4.page = 1;

        _this4.getEmployees();
      }, 800);
    },
    getVendors: function getVendors() {
      var _this5 = this;

      _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/vendorsDropdown").then(function (response) {
        if (response.data.hasOwnProperty("success")) {
          if (response.data.success === true) {
            console.log(response.data.data);
            _this5.vendors = response.data.data;
            console.log("Vendors Fetched");
          } else {
            _this5.$toast({
              component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later"
              }
            });
          }
        } else {
          _this5.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
            position: "top-right",
            props: {
              title: "Error",
              icon: "AlertCircleIcon",
              variant: "danger",
              text: "Something went wrong, try again later"
            }
          });
        }
      })["catch"](function (error) {
        console.error(error);
      });
    },
    getClients: function getClients() {
      var _this6 = this;

      _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/clientsDropdown").then(function (response) {
        if (response.data.hasOwnProperty("success")) {
          if (response.data.success === true) {
            console.log(response.data.data);
            _this6.clients = response.data.data;
            console.log("Clients Fetched");
          } else {
            _this6.$toast({
              component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later"
              }
            });
          }
        } else {
          _this6.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
            position: "top-right",
            props: {
              title: "Error",
              icon: "AlertCircleIcon",
              variant: "danger",
              text: "Something went wrong, try again later"
            }
          });
        }
      })["catch"](function (error) {
        console.error(error);
      });
    },
    getAccommodations: function getAccommodations() {
      var _this7 = this;

      _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/accommodationsDropdown").then(function (response) {
        if (response.data.hasOwnProperty("success")) {
          if (response.data.success === true) {
            console.log(response.data.data);
            _this7.accommodations = response.data.data;
            console.log("accommodations Fetched");
          } else {
            _this7.$toast({
              component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later"
              }
            });
          }
        } else {
          _this7.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
            position: "top-right",
            props: {
              title: "Error",
              icon: "AlertCircleIcon",
              variant: "danger",
              text: "Something went wrong, try again later"
            }
          });
        }
      })["catch"](function (error) {
        console.error(error);
      });
    },
    getProfessions: function getProfessions() {
      var _this8 = this;

      _axios__WEBPACK_IMPORTED_MODULE_1__["default"].get("/professionsDropdown").then(function (response) {
        if (response.data.hasOwnProperty("success")) {
          if (response.data.success === true) {
            console.log(response.data.data);
            _this8.professions = response.data.data;
            console.log("professions Fetched");
          } else {
            _this8.$toast({
              component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
              position: "top-right",
              props: {
                title: "Error",
                icon: "AlertCircleIcon",
                variant: "danger",
                text: "Something went wrong, try again later"
              }
            });
          }
        } else {
          _this8.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
            position: "top-right",
            props: {
              title: "Error",
              icon: "AlertCircleIcon",
              variant: "danger",
              text: "Something went wrong, try again later"
            }
          });
        }
      })["catch"](function (error) {
        console.error(error);
      });
    }
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

  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".v-select {\n  position: relative;\n  font-family: inherit;\n}\n.v-select,\n.v-select * {\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n}\n\n/* KeyFrames */\n@-webkit-keyframes vSelectSpinner-ltr {\n0% {\n    -webkit-transform: rotate(0deg);\n            transform: rotate(0deg);\n}\n100% {\n    -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n}\n}\n@-webkit-keyframes vSelectSpinner-rtl {\n0% {\n    -webkit-transform: rotate(0deg);\n            transform: rotate(0deg);\n}\n100% {\n    -webkit-transform: rotate(-360deg);\n            transform: rotate(-360deg);\n}\n}\n@keyframes vSelectSpinner-ltr {\n0% {\n    -webkit-transform: rotate(0deg);\n            transform: rotate(0deg);\n}\n100% {\n    -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n}\n}\n@keyframes vSelectSpinner-rtl {\n0% {\n    -webkit-transform: rotate(0deg);\n            transform: rotate(0deg);\n}\n100% {\n    -webkit-transform: rotate(-360deg);\n            transform: rotate(-360deg);\n}\n}\n/* Dropdown Default Transition */\n.vs__fade-enter-active,\n.vs__fade-leave-active {\n  pointer-events: none;\n  -webkit-transition: opacity 0.15s cubic-bezier(1, 0.5, 0.8, 1);\n  transition: opacity 0.15s cubic-bezier(1, 0.5, 0.8, 1);\n}\n[dir] .vs__fade-enter-active, [dir] .vs__fade-leave-active {\n  -webkit-transition: opacity 0.15s cubic-bezier(1, 0.5, 0.8, 1);\n}\n.vs__fade-enter,\n.vs__fade-leave-to {\n  opacity: 0;\n}\n\n/** Component States */\n/*\n * Disabled\n *\n * When the component is disabled, all interaction\n * should be prevented. Here we modify the bg color,\n * and change the cursor displayed on the interactive\n * components.\n */\n[dir] .vs--disabled .vs__dropdown-toggle, [dir] .vs--disabled .vs__clear, [dir] .vs--disabled .vs__search, [dir] .vs--disabled .vs__selected, [dir] .vs--disabled .vs__open-indicator {\n  cursor: not-allowed;\n  background-color: #f8f8f8;\n}\n\n/*\n *  RTL - Right to Left Support\n *\n *  Because we're using a flexbox layout, the `dir=\"rtl\"`\n *  HTML attribute does most of the work for us by\n *  rearranging the child elements visually.\n */\n.v-select[dir=rtl] .vs__actions {\n  padding: 0 3px 0 6px;\n}\n.v-select[dir=rtl] .vs__clear {\n  margin-left: 6px;\n  margin-right: 0;\n}\n.v-select[dir=rtl] .vs__deselect {\n  margin-left: 0;\n  margin-right: 2px;\n}\n.v-select[dir=rtl] .vs__dropdown-menu {\n  text-align: right;\n}\n\n/**\n    Dropdown Toggle\n\n    The dropdown toggle is the primary wrapper of the component. It\n    has two direct descendants: .vs__selected-options, and .vs__actions.\n\n    .vs__selected-options holds the .vs__selected's as well as the\n    main search input.\n\n    .vs__actions holds the clear button and dropdown toggle.\n */\n.vs__dropdown-toggle {\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  white-space: normal;\n}\n[dir] .vs__dropdown-toggle {\n  padding: 0 0 4px 0;\n  background: none;\n  border: 1px solid #d8d6de;\n  border-radius: 0.357rem;\n}\n.vs__selected-options {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -ms-flex-preferred-size: 100%;\n      flex-basis: 100%;\n  -webkit-box-flex: 1;\n      -ms-flex-positive: 1;\n          flex-grow: 1;\n  -ms-flex-wrap: wrap;\n      flex-wrap: wrap;\n  position: relative;\n}\n[dir] .vs__selected-options {\n  padding: 0 2px;\n}\n.vs__actions {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n}\n[dir=ltr] .vs__actions {\n  padding: 4px 6px 0 3px;\n}\n[dir=rtl] .vs__actions {\n  padding: 4px 3px 0 6px;\n}\n\n/* Dropdown Toggle States */\n[dir] .vs--searchable .vs__dropdown-toggle {\n  cursor: text;\n}\n[dir] .vs--unsearchable .vs__dropdown-toggle {\n  cursor: pointer;\n}\n[dir] .vs--open .vs__dropdown-toggle {\n  border-bottom-color: transparent;\n}\n[dir=ltr] .vs--open .vs__dropdown-toggle {\n  border-bottom-left-radius: 0;\n  border-bottom-right-radius: 0;\n}\n[dir=rtl] .vs--open .vs__dropdown-toggle {\n  border-bottom-right-radius: 0;\n  border-bottom-left-radius: 0;\n}\n.vs__open-indicator {\n  fill: rgba(60, 60, 60, 0.5);\n  -webkit-transform: scale(1);\n  transition: -webkit-transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n  -webkit-transition: -webkit-transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n  transition: transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n  transition: transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855), -webkit-transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n  -webkit-transition-timing-function: cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir] .vs__open-indicator {\n          -webkit-transform: scale(1);\n                  transform: scale(1);\n  -webkit-transition: -webkit-transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n          -webkit-transition-timing-function: cubic-bezier(1, -0.115, 0.975, 0.855);\n                  transition-timing-function: cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir=ltr] .vs--open .vs__open-indicator {\n  -webkit-transform: rotate(180deg) scale(1);\n  transform: rotate(180deg) scale(1);\n}\n[dir=rtl] .vs--open .vs__open-indicator {\n  -webkit-transform: rotate(-180deg) scale(1);\n          transform: rotate(-180deg) scale(1);\n}\n.vs--loading .vs__open-indicator {\n  opacity: 0;\n}\n\n/* Clear Button */\n.vs__clear {\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__clear {\n  padding: 0;\n  border: 0;\n  background-color: transparent;\n  cursor: pointer;\n}\n[dir=ltr] .vs__clear {\n  margin-right: 8px;\n}\n[dir=rtl] .vs__clear {\n  margin-left: 8px;\n}\n\n/* Dropdown Menu */\n.vs__dropdown-menu {\n  display: block;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  position: absolute;\n  top: calc(100% - 1px);\n  z-index: 1000;\n  width: 100%;\n  max-height: 350px;\n  min-width: 160px;\n  overflow-y: auto;\n  -webkit-box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1);\n  list-style: none;\n}\n[dir] .vs__dropdown-menu {\n  padding: 5px 0;\n  margin: 0;\n          -webkit-box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1);\n                  box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1);\n  border: 1px solid #d8d6de;\n  border-top-style: none;\n  border-radius: 0 0 0.357rem 0.357rem;\n  background: #fff;\n}\n[dir=ltr] .vs__dropdown-menu {\n  left: 0;\n  text-align: left;\n}\n[dir=rtl] .vs__dropdown-menu {\n  right: 0;\n  text-align: right;\n}\n[dir] .vs__no-options {\n  text-align: center;\n}\n\n/* List Items */\n.vs__dropdown-option {\n  line-height: 1.42857143;\n  /* Normalize line height */\n  display: block;\n  color: #333;\n  /* Overrides most CSS frameworks */\n  white-space: nowrap;\n}\n[dir] .vs__dropdown-option {\n  padding: 3px 20px;\n  clear: both;\n}\n[dir] .vs__dropdown-option:hover {\n  cursor: pointer;\n}\n.vs__dropdown-option--highlight {\n  color: #06608f !important;\n}\n[dir] .vs__dropdown-option--highlight {\n  background: rgba(6, 96, 143, 0.12);\n}\n.vs__dropdown-option--disabled {\n  color: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__dropdown-option--disabled {\n  background: inherit;\n}\n[dir] .vs__dropdown-option--disabled:hover {\n  cursor: inherit;\n}\n\n/* Selected Tags */\n.vs__selected {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  color: #333;\n  line-height: 1.8;\n  z-index: 0;\n}\n[dir] .vs__selected {\n  background-color: #06608f;\n  border: 0 solid rgba(60, 60, 60, 0.26);\n  border-radius: 0.357rem;\n  margin: 4px 2px 0px 2px;\n  padding: 0 0.25em;\n}\n.vs__deselect {\n  display: -webkit-inline-box;\n  display: -ms-inline-flexbox;\n  display: inline-flex;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__deselect {\n  padding: 0;\n  border: 0;\n  cursor: pointer;\n  background: none;\n  text-shadow: 0 1px 0 #fff;\n}\n[dir=ltr] .vs__deselect {\n  margin-left: 4px;\n}\n[dir=rtl] .vs__deselect {\n  margin-right: 4px;\n}\n\n/* States */\n[dir] .vs--single .vs__selected {\n  background-color: transparent;\n  border-color: transparent;\n}\n.vs--single.vs--open .vs__selected {\n  position: absolute;\n  opacity: 0.4;\n}\n.vs--single.vs--searching .vs__selected {\n  display: none;\n}\n\n/* Search Input */\n/**\n * Super weird bug... If this declaration is grouped\n * below, the cancel button will still appear in chrome.\n * If it's up here on it's own, it'll hide it.\n */\n.vs__search::-webkit-search-cancel-button {\n  display: none;\n}\n.vs__search::-webkit-search-decoration,\n.vs__search::-webkit-search-results-button,\n.vs__search::-webkit-search-results-decoration,\n.vs__search::-ms-clear {\n  display: none;\n}\n.vs__search,\n.vs__search:focus {\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  line-height: 1.8;\n  font-size: 1em;\n  outline: none;\n  -webkit-box-shadow: none;\n  width: 0;\n  max-width: 100%;\n  -webkit-box-flex: 1;\n      -ms-flex-positive: 1;\n          flex-grow: 1;\n  z-index: 1;\n}\n[dir] .vs__search, [dir] .vs__search:focus {\n  border: 1px solid transparent;\n  margin: 4px 0 0 0;\n  padding: 0 7px;\n  background: none;\n          -webkit-box-shadow: none;\n                  box-shadow: none;\n}\n[dir=ltr] .vs__search, [dir=ltr] .vs__search:focus {\n  border-left: none;\n}\n[dir=rtl] .vs__search, [dir=rtl] .vs__search:focus {\n  border-right: none;\n}\n.vs__search::-webkit-input-placeholder {\n  color: #6e6b7b;\n}\n.vs__search::-moz-placeholder {\n  color: #6e6b7b;\n}\n.vs__search:-ms-input-placeholder {\n  color: #6e6b7b;\n}\n.vs__search::-ms-input-placeholder {\n  color: #6e6b7b;\n}\n.vs__search::placeholder {\n  color: #6e6b7b;\n}\n\n/**\n    States\n */\n.vs--unsearchable .vs__search {\n  opacity: 1;\n}\n[dir] .vs--unsearchable:not(.vs--disabled) .vs__search:hover {\n  cursor: pointer;\n}\n.vs--single.vs--searching:not(.vs--open):not(.vs--loading) .vs__search {\n  opacity: 0.2;\n}\n\n/* Loading Spinner */\n.vs__spinner {\n  -ms-flex-item-align: center;\n      align-self: center;\n  opacity: 0;\n  font-size: 5px;\n  text-indent: -9999em;\n  overflow: hidden;\n  -webkit-transform: translateZ(0);\n  -webkit-transition: opacity 0.1s;\n  transition: opacity 0.1s;\n}\n[dir] .vs__spinner {\n  border-top: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-bottom: 0.9em solid rgba(100, 100, 100, 0.1);\n          -webkit-transform: translateZ(0);\n                  transform: translateZ(0);\n  -webkit-transition: opacity 0.1s;\n}\n[dir=ltr] .vs__spinner {\n  border-right: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-left: 0.9em solid rgba(60, 60, 60, 0.45);\n  -webkit-animation:  vSelectSpinner-ltr 1.1s infinite linear;\n  animation:  vSelectSpinner-ltr 1.1s infinite linear;\n}\n[dir=rtl] .vs__spinner {\n  border-left: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-right: 0.9em solid rgba(60, 60, 60, 0.45);\n  -webkit-animation:  vSelectSpinner-rtl 1.1s infinite linear;\n          animation:  vSelectSpinner-rtl 1.1s infinite linear;\n}\n.vs__spinner,\n.vs__spinner:after {\n  width: 5em;\n  height: 5em;\n}\n[dir] .vs__spinner, [dir] .vs__spinner:after {\n  border-radius: 50%;\n}\n\n/* Loading Spinner States */\n.vs--loading .vs__spinner {\n  opacity: 1;\n}\n.vs__open-indicator {\n  fill: none;\n}\n[dir] .vs__open-indicator {\n  margin-top: 0.15rem;\n}\n.vs__dropdown-toggle {\n  -webkit-transition: all 0.25s ease-in-out;\n  transition: all 0.25s ease-in-out;\n}\n[dir] .vs__dropdown-toggle {\n  padding: 0.59px 0 4px 0;\n  -webkit-transition: all 0.25s ease-in-out;\n}\n[dir=ltr] .vs--single .vs__dropdown-toggle {\n  padding-left: 6px;\n}\n[dir=rtl] .vs--single .vs__dropdown-toggle {\n  padding-right: 6px;\n}\n.vs__dropdown-option--disabled {\n  opacity: 0.5;\n}\n[dir] .vs__dropdown-option--disabled.vs__dropdown-option--selected {\n  background: #06608f !important;\n}\n.vs__dropdown-option {\n  color: #6e6b7b;\n}\n[dir] .vs__dropdown-option, [dir] .vs__no-options {\n  padding: 7px 20px;\n}\n.vs__dropdown-option--selected {\n  background-color: #06608f;\n  color: #fff;\n  position: relative;\n}\n.vs__dropdown-option--selected::after {\n  content: \"\";\n  height: 1.1rem;\n  width: 1.1rem;\n  display: inline-block;\n  position: absolute;\n  top: 50%;\n  -webkit-transform: translateY(-50%);\n          transform: translateY(-50%);\n  right: 20px;\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-check'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 1.1rem;\n}\n[dir=rtl] .vs__dropdown-option--selected::after {\n  left: 20px;\n  right: unset;\n}\n.vs__dropdown-option--selected.vs__dropdown-option--highlight {\n  color: #fff !important;\n  background-color: #06608f !important;\n}\n.vs__clear svg {\n  color: #6e6b7b;\n}\n.vs__selected {\n  color: #fff;\n}\n.v-select.vs--single .vs__selected {\n  color: #6e6b7b;\n  transition: -webkit-transform 0.2s ease;\n  -webkit-transition: -webkit-transform 0.2s ease;\n  transition: transform 0.2s ease;\n  transition: transform 0.2s ease, -webkit-transform 0.2s ease;\n}\n[dir] .v-select.vs--single .vs__selected {\n  margin-top: 5px;\n  -webkit-transition: -webkit-transform 0.2s ease;\n}\n[dir=ltr] .v-select.vs--single .vs__selected input {\n  padding-left: 0;\n}\n[dir=rtl] .v-select.vs--single .vs__selected input {\n  padding-right: 0;\n}\n[dir=ltr] .vs--single.vs--open .vs__selected {\n  -webkit-transform: translateX(5px);\n  transform: translateX(5px);\n}\n[dir=rtl] .vs--single.vs--open .vs__selected {\n  -webkit-transform: translateX(-5px);\n          transform: translateX(-5px);\n}\n.vs__selected .vs__deselect {\n  color: inherit;\n}\n.v-select:not(.vs--single) .vs__selected {\n  font-size: 0.9rem;\n}\n[dir] .v-select:not(.vs--single) .vs__selected {\n  border-radius: 3px;\n  padding: 0 0.6em;\n}\n[dir=ltr] .v-select:not(.vs--single) .vs__selected {\n  margin: 5px 2px 2px 5px;\n}\n[dir=rtl] .v-select:not(.vs--single) .vs__selected {\n  margin: 5px 5px 2px 2px;\n}\n.v-select:not(.vs--single) .vs__deselect svg {\n  -webkit-transform: scale(0.8);\n  vertical-align: text-top;\n}\n[dir] .v-select:not(.vs--single) .vs__deselect svg {\n          -webkit-transform: scale(0.8);\n                  transform: scale(0.8);\n}\n.vs__dropdown-menu {\n  top: calc(100% + 1rem);\n}\n[dir] .vs__dropdown-menu {\n  border: none;\n  border-radius: 6px;\n  padding: 0;\n}\n.vs--open .vs__dropdown-toggle {\n  -webkit-box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n[dir] .vs--open .vs__dropdown-toggle {\n  border-color: #06608f;\n  border-bottom-color: #06608f;\n          -webkit-box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n                  box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n[dir=ltr] .vs--open .vs__dropdown-toggle {\n  border-bottom-left-radius: 0.357rem;\n  border-bottom-right-radius: 0.357rem;\n}\n[dir=rtl] .vs--open .vs__dropdown-toggle {\n  border-bottom-right-radius: 0.357rem;\n  border-bottom-left-radius: 0.357rem;\n}\n.select-size-lg .vs__selected {\n  font-size: 1rem !important;\n}\n[dir] .select-size-lg.vs--single.vs--open .vs__selected {\n  margin-top: 6px;\n}\n.select-size-lg .vs__dropdown-toggle,\n.select-size-lg .vs__selected {\n  font-size: 1.25rem;\n}\n[dir] .select-size-lg .vs__dropdown-toggle {\n  padding: 5px;\n}\n[dir] .select-size-lg .vs__dropdown-toggle input {\n  margin-top: 0;\n}\n.select-size-lg .vs__deselect svg {\n  -webkit-transform: scale(1) !important;\n  vertical-align: middle !important;\n}\n[dir] .select-size-lg .vs__deselect svg {\n          -webkit-transform: scale(1) !important;\n                  transform: scale(1) !important;\n}\n[dir] .select-size-sm .vs__dropdown-toggle {\n  padding-bottom: 0;\n  padding: 1px;\n}\n[dir] .select-size-sm.vs--single .vs__dropdown-toggle {\n  padding: 2px;\n}\n.select-size-sm .vs__dropdown-toggle,\n.select-size-sm .vs__selected {\n  font-size: 0.9rem;\n}\n[dir] .select-size-sm .vs__actions {\n  padding-top: 2px;\n  padding-bottom: 2px;\n}\n.select-size-sm .vs__deselect svg {\n  vertical-align: middle !important;\n}\n[dir] .select-size-sm .vs__search {\n  margin-top: 0;\n}\n.select-size-sm.v-select .vs__selected {\n  font-size: 0.75rem;\n}\n[dir] .select-size-sm.v-select .vs__selected {\n  padding: 0 0.3rem;\n}\n[dir] .select-size-sm.v-select:not(.vs--single) .vs__selected {\n  margin: 4px 5px;\n}\n[dir] .select-size-sm.v-select.vs--single .vs__selected {\n  margin-top: 1px;\n}\n[dir] .select-size-sm.vs--single.vs--open .vs__selected {\n  margin-top: 4px;\n}\n.dark-layout .vs__dropdown-toggle {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .vs__dropdown-toggle {\n  background: #283046;\n  border-color: #404656;\n}\n.dark-layout .vs__selected-options input {\n  color: #b4b7bd;\n}\n.dark-layout .vs__selected-options input::-webkit-input-placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__selected-options input::-moz-placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__selected-options input:-ms-input-placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__selected-options input::-ms-input-placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__selected-options input::placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__actions svg {\n  fill: #404656;\n}\n[dir] .dark-layout .vs__dropdown-menu {\n  background: #283046;\n}\n.dark-layout .vs__dropdown-menu li {\n  color: #b4b7bd;\n}\n.dark-layout .v-select:not(.vs--single) .vs__selected {\n  color: #06608f;\n}\n[dir] .dark-layout .v-select:not(.vs--single) .vs__selected {\n  background-color: rgba(6, 96, 143, 0.12);\n}\n.dark-layout .v-select.vs--single .vs__selected {\n  color: #b4b7bd !important;\n}\n.dark-layout .vs--disabled .vs__dropdown-toggle,\n.dark-layout .vs--disabled .vs__clear,\n.dark-layout .vs--disabled .vs__search,\n.dark-layout .vs--disabled .vs__selected,\n.dark-layout .vs--disabled .vs__open-indicator {\n  opacity: 0.5;\n}\n[dir] .dark-layout .vs--disabled .vs__dropdown-toggle, [dir] .dark-layout .vs--disabled .vs__clear, [dir] .dark-layout .vs--disabled .vs__search, [dir] .dark-layout .vs--disabled .vs__selected, [dir] .dark-layout .vs--disabled .vs__open-indicator {\n  background-color: #283046;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.enlargeable-image .full {\n  z-index: 9999 !important;\n}\n[dir] .enlargeable-image .full {\n\n  background-color: rgba(0, 0, 0, 0.5) !important;\n}\n.customHead {\n  color: #fff;\n}\n[dir] .customHead {\n  background-color: #06608f !important;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTable.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./employeeTableView.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTable.vue?vue&type=template&id=5625242f& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-row",
    [
      _c(
        "b-col",
        { attrs: { cols: "12" } },
        [_c("table-rowdetails-support")],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./frontend/src/views/table/bs-table/employeeTableView.vue?vue&type=template&id=2ace44f4& ***!
  \******************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "div",
        { staticClass: "custom-search d-flex justify-content-between" },
        [
          _c("b-form-group", [
            _c(
              "div",
              { staticClass: "d-flex align-items-center" },
              [
                _c(
                  "b-input-group",
                  [
                    _c(
                      "b-input-group-prepend",
                      [
                        _c(
                          "b-button",
                          {
                            attrs: { variant: "outline-primary" },
                            on: {
                              click: function($event) {
                                ;(_vm.page = 1), _vm.getEmployees()
                              }
                            }
                          },
                          [
                            _c("feather-icon", {
                              attrs: { icon: "SearchIcon" }
                            })
                          ],
                          1
                        )
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("b-form-input", {
                      staticClass: "d-inline-block",
                      attrs: { placeholder: "Search", type: "text" },
                      on: {
                        keyup: function($event) {
                          return _vm.searchTimeOut()
                        }
                      },
                      model: {
                        value: _vm.searchTerm,
                        callback: function($$v) {
                          _vm.searchTerm = $$v
                        },
                        expression: "searchTerm"
                      }
                    }),
                    _vm._v(" "),
                    _c(
                      "b-input-group-append",
                      [
                        _c(
                          "b-button",
                          {
                            directives: [
                              {
                                name: "ripple",
                                rawName: "v-ripple.400",
                                value: "rgba(113, 102, 240, 0.15)",
                                expression: "'rgba(113, 102, 240, 0.15)'",
                                modifiers: { "400": true }
                              },
                              {
                                name: "b-modal",
                                rawName: "v-b-modal.modal-advancesearch",
                                modifiers: { "modal-advancesearch": true }
                              }
                            ],
                            attrs: { variant: "outline-primary" }
                          },
                          [
                            _vm._v(
                              "\n              Advance search\n            "
                            )
                          ]
                        )
                      ],
                      1
                    )
                  ],
                  1
                )
              ],
              1
            )
          ]),
          _vm._v(" "),
          _vm.$ability.can("add/copy", "employees")
            ? _c("b-form-group", [
                _c(
                  "div",
                  { staticClass: "d-flex align-items-center" },
                  [
                    _c(
                      "b-input-group",
                      [
                        _c(
                          "b-input-group-prepend",
                          [
                            _c(
                              "b-button",
                              {
                                directives: [
                                  {
                                    name: "b-modal",
                                    rawName: "v-b-modal.modal-export",
                                    modifiers: { "modal-export": true }
                                  },
                                  {
                                    name: "ripple",
                                    rawName: "v-ripple.400",
                                    value: "rgba(255, 255, 255, 0.15)",
                                    expression: "'rgba(255, 255, 255, 0.15)'",
                                    modifiers: { "400": true }
                                  }
                                ],
                                ref: "button",
                                attrs: {
                                  title: "Export",
                                  variant: "outline-primary"
                                }
                              },
                              [
                                _c("feather-icon", {
                                  attrs: { icon: "DownloadIcon" }
                                })
                              ],
                              1
                            )
                          ],
                          1
                        ),
                        _vm._v(" "),
                        _c(
                          "b-input-group-prepend",
                          [
                            _c(
                              "b-button",
                              {
                                directives: [
                                  {
                                    name: "ripple",
                                    rawName: "v-ripple.400",
                                    value: "rgba(255, 255, 255, 0.15)",
                                    expression: "'rgba(255, 255, 255, 0.15)'",
                                    modifiers: { "400": true }
                                  }
                                ],
                                attrs: {
                                  variant: "primary",
                                  to: { name: "add-employee" },
                                  title: "Add Employee"
                                }
                              },
                              [_vm._v("\n              ADD+\n            ")]
                            )
                          ],
                          1
                        )
                      ],
                      1
                    )
                  ],
                  1
                )
              ])
            : _vm._e()
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "exportModel",
          attrs: {
            id: "modal-export",
            "hide-footer": "",
            title: "Export Table",
            size: "lg",
            "no-close-on-backdrop": true
          }
        },
        [
          _c(
            "b-form",
            {
              on: {
                submit: function($event) {
                  $event.preventDefault()
                }
              }
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Search Filter" } },
                        [
                          _c("b-form-input", {
                            attrs: { placeholder: "" },
                            model: {
                              value: _vm.searchTerm,
                              callback: function($$v) {
                                _vm.searchTerm = $$v
                              },
                              expression: "searchTerm"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Iqama Expiry Date" } },
                        [
                          _c("b-form-datepicker", {
                            model: {
                              value: _vm.iqama_expiry_date,
                              callback: function($$v) {
                                _vm.iqama_expiry_date = $$v
                              },
                              expression: "iqama_expiry_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Vendor" } },
                        [
                          _c("v-select", {
                            attrs: {
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.vendors,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.vendor,
                              callback: function($$v) {
                                _vm.vendor = $$v
                              },
                              expression: "vendor"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Client" } },
                        [
                          _c("v-select", {
                            attrs: {
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.clients,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.client,
                              callback: function($$v) {
                                _vm.client = $$v
                              },
                              expression: "client"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Status" } },
                        [
                          _c("v-select", {
                            attrs: {
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.statuses,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.exportStatus,
                              callback: function($$v) {
                                _vm.exportStatus = $$v
                              },
                              expression: "exportStatus"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Records Limit" } },
                        [
                          _c("b-form-input", {
                            attrs: { placeholder: "" },
                            model: {
                              value: _vm.exportLimit,
                              callback: function($$v) {
                                _vm.exportLimit = $$v
                              },
                              expression: "exportLimit"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: {
                            type: "submit",
                            variant: "primary",
                            href:
                              "/api/employeeCsv?searchTerm=" +
                              _vm.searchTerm +
                              " &limit=" +
                              _vm.exportLimit +
                              " &iqama_expiry_date=" +
                              _vm.iqama_expiry_date +
                              " &vendor=" +
                              _vm.vendor +
                              " &client=" +
                              _vm.client +
                              " &status=" +
                              _vm.exportStatus,
                            target: "_blank"
                          },
                          on: { click: _vm.closeExpot }
                        },
                        [_c("span", [_vm._v("Export")])]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: { type: "submit", variant: "outline-primary" },
                          on: { click: _vm.resetExport }
                        },
                        [_c("span", [_vm._v("Reset")])]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: {
                            type: "submit",
                            variant: "outline-secondary"
                          },
                          on: { click: _vm.closeExpot }
                        },
                        [_c("span", [_vm._v("Cancel")])]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "searchModel",
          attrs: {
            id: "modal-advancesearch",
            "hide-footer": "",
            title: "Advance Search",
            size: "lg",
            "cancel-variant": "outline-secondary"
          }
        },
        [
          _c(
            "b-form",
            {
              on: {
                submit: function($event) {
                  $event.preventDefault()
                }
              }
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        [
                          _c("label", { attrs: { for: "name" } }, [
                            _vm._v(
                              "\n              Enter Name\n              "
                            ),
                            _c("small", { staticClass: "text-muted" }, [
                              _vm._v(" Contains. ")
                            ])
                          ]),
                          _vm._v(" "),
                          _c("b-form-input", {
                            attrs: { id: "name", placeholder: "Enter name" },
                            model: {
                              value: _vm.formValues.name,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "name", $$v)
                              },
                              expression: "formValues.name"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "GL ID", "label-for": "emp_id" } },
                        [
                          _c("b-form-input", {
                            attrs: { id: "emp_id", placeholder: "" },
                            model: {
                              value: _vm.formValues.emp_id,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "emp_id", $$v)
                              },
                              expression: "formValues.emp_id"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Nationality",
                            "label-for": "v-nationality"
                          }
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-nationality",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.nationalities,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.formValues.nationality,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "nationality", $$v)
                              },
                              expression: "formValues.nationality"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Religion",
                            "label-for": "v-religion"
                          }
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-religion",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.religions,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.formValues.religion,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "religion", $$v)
                              },
                              expression: "formValues.religion"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Date of Birth",
                            "label-for": "v-dob"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-dob" },
                            model: {
                              value: _vm.formValues.dob,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "dob", $$v)
                              },
                              expression: "formValues.dob"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Age", "label-for": "v-age" } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-age",
                              placeholder: "Enter age",
                              type: "number"
                            },
                            model: {
                              value: _vm.formValues.age,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "age", $$v)
                              },
                              expression: "formValues.age"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Joining date",
                            "label-for": "v-joining_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-joining_date" },
                            model: {
                              value: _vm.formValues.joining_date,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "joining_date", $$v)
                              },
                              expression: "formValues.joining_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Mobile Number",
                            "label-for": "v-contact_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-contact_number",
                              placeholder: "Enter Mobile number",
                              type: "number"
                            },
                            model: {
                              value: _vm.formValues.contact_number,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "contact_number", $$v)
                              },
                              expression: "formValues.contact_number"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Benefits Details",
                            "label-for": "v-benefits"
                          }
                        },
                        [
                          _c("b-form-textarea", {
                            attrs: {
                              id: "v-benefits",
                              placeholder: "Benefits Detail",
                              rows: "3"
                            },
                            model: {
                              value: _vm.formValues.benefits,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "benefits", $$v)
                              },
                              expression: "formValues.benefits"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: { label: "IBAN (NCB)", "label-for": "v-iban" }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-iban",
                              type: "text",
                              placeholder: "IBAN number"
                            },
                            model: {
                              value: _vm.formValues.iban,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "iban", $$v)
                              },
                              expression: "formValues.iban"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Vacation Due Date",
                            "label-for": "v-vacation_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-vacation_date" },
                            model: {
                              value: _vm.formValues.vacation_date,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "vacation_date", $$v)
                              },
                              expression: "formValues.vacation_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { "label-for": "v-notes", label: "Notes" } },
                        [
                          _c("b-form-textarea", {
                            attrs: {
                              id: "v-notes",
                              placeholder: "Notes",
                              rows: "3"
                            },
                            model: {
                              value: _vm.formValues.notes,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "notes", $$v)
                              },
                              expression: "formValues.notes"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Iqama Number",
                            "label-for": "v-iqama_no"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-iqama_no",
                              placeholder: "Iqama Number"
                            },
                            model: {
                              value: _vm.formValues.iqama_no,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "iqama_no", $$v)
                              },
                              expression: "formValues.iqama_no"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Iqama Expiry Date",
                            "label-for": "v-iqama_expiry_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-iqama_expiry_date" },
                            model: {
                              value: _vm.formValues.iqama_expiry_date,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "iqama_expiry_date",
                                  $$v
                                )
                              },
                              expression: "formValues.iqama_expiry_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Iqama Profession",
                            "label-for": "v-iqama_profession"
                          }
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-iqama_profession",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.professions,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text"
                            },
                            model: {
                              value: _vm.formValues.iqama_profession,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "iqama_profession",
                                  $$v
                                )
                              },
                              expression: "formValues.iqama_profession"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Passport Number",
                            "label-for": "v-passport_number"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-passport_number",
                              placeholder: "Passport Number"
                            },
                            model: {
                              value: _vm.formValues.passport_number,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "passport_number", $$v)
                              },
                              expression: "formValues.passport_number"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Passport Expiry Date",
                            "label-for": "v-passport_expiry_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-passport_expiry_date" },
                            model: {
                              value: _vm.formValues.passport_expiry_date,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "passport_expiry_date",
                                  $$v
                                )
                              },
                              expression: "formValues.passport_expiry_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "AJEER Expiry Date",
                            "label-for": "v-ajeer_expiration_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-ajeer_expiration_date" },
                            model: {
                              value: _vm.formValues.ajeer_expiration_date,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "ajeer_expiration_date",
                                  $$v
                                )
                              },
                              expression: "formValues.ajeer_expiration_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Medical Insurance Expiry Date",
                            "label-for": "v-insurance_card_expirationDate"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-insurance_card_expirationDate" },
                            model: {
                              value:
                                _vm.formValues.insurance_card_expirationDate,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "insurance_card_expirationDate",
                                  $$v
                                )
                              },
                              expression:
                                "formValues.insurance_card_expirationDate"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Vendor", "label-for": "v-vendor" } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-vendor",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.vendors,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text"
                            },
                            model: {
                              value: _vm.formValues.vendor,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "vendor", $$v)
                              },
                              expression: "formValues.vendor"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Basic Salary",
                            "label-for": "v-salary_rate"
                          }
                        },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "v-salary_rate",
                              placeholder: "Basic Salary"
                            },
                            model: {
                              value: _vm.formValues.salary_rate,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "salary_rate", $$v)
                              },
                              expression: "formValues.salary_rate"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Client", "label-for": "v-client" } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-client",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.clients,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text"
                            },
                            model: {
                              value: _vm.formValues.client,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "client", $$v)
                              },
                              expression: "formValues.client"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Status", "label-for": "v-status" } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-status",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.statuses,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text",
                              reduce: function(text) {
                                return text.value
                              }
                            },
                            model: {
                              value: _vm.formValues.status,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "status", $$v)
                              },
                              expression: "formValues.status"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "accommodation",
                            "label-for": "v-accommodation"
                          }
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "v-accommodation",
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                              options: _vm.accommodations,
                              selectable: function(option) {
                                return !option.value.includes("select_value")
                              },
                              label: "text"
                            },
                            model: {
                              value: _vm.formValues.accommodation,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "accommodation", $$v)
                              },
                              expression: "formValues.accommodation"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Start Date",
                            "label-for": "v-contract_start"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-contract_start" },
                            model: {
                              value: _vm.formValues.contract_start,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "contract_start", $$v)
                              },
                              expression: "formValues.contract_start"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Stop Date",
                            "label-for": "v-project_stop_date"
                          }
                        },
                        [
                          _c("b-form-datepicker", {
                            staticClass: "mb-1",
                            attrs: { id: "v-project_stop_date" },
                            model: {
                              value: _vm.formValues.project_stop_date,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "project_stop_date",
                                  $$v
                                )
                              },
                              expression: "formValues.project_stop_date"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "English Language",
                            "label-for": "v-lang_eng"
                          }
                        },
                        [
                          _c("b-form-rating", {
                            attrs: { "no-border": "", variant: "warning" },
                            model: {
                              value: _vm.formValues.lang_eng,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "lang_eng", $$v)
                              },
                              expression: "formValues.lang_eng"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Arabic Language",
                            "label-for": "v-lang_ar"
                          }
                        },
                        [
                          _c("b-form-rating", {
                            attrs: { "no-border": "", variant: "warning" },
                            model: {
                              value: _vm.formValues.lang_ar,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "lang_ar", $$v)
                              },
                              expression: "formValues.lang_ar"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Hindi Language",
                            "label-for": "v-lang_hind"
                          }
                        },
                        [
                          _c("b-form-rating", {
                            attrs: { "no-border": "", variant: "warning" },
                            model: {
                              value: _vm.formValues.lang_hind,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "lang_hind", $$v)
                              },
                              expression: "formValues.lang_hind"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Presentable Rating",
                            "label-for": "v-appearance_presentable"
                          }
                        },
                        [
                          _c("b-form-rating", {
                            attrs: { "no-border": "", variant: "warning" },
                            model: {
                              value: _vm.formValues.appearance_presentable,
                              callback: function($$v) {
                                _vm.$set(
                                  _vm.formValues,
                                  "appearance_presentable",
                                  $$v
                                )
                              },
                              expression: "formValues.appearance_presentable"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c("b-form-group", { attrs: { label: "Beard?" } }, [
                        _c(
                          "div",
                          { staticClass: "demo-inline-spacing" },
                          [
                            _c(
                              "b-form-radio",
                              {
                                attrs: {
                                  name: "v-apprearance_beard",
                                  value: "yes"
                                },
                                model: {
                                  value: _vm.formValues.apprearance_beard,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.formValues,
                                      "apprearance_beard",
                                      $$v
                                    )
                                  },
                                  expression: "formValues.apprearance_beard"
                                }
                              },
                              [_vm._v("\n                Yes\n              ")]
                            ),
                            _vm._v(" "),
                            _c(
                              "b-form-radio",
                              {
                                attrs: {
                                  name: "v-apprearance_beard",
                                  value: "no"
                                },
                                model: {
                                  value: _vm.formValues.apprearance_beard,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.formValues,
                                      "apprearance_beard",
                                      $$v
                                    )
                                  },
                                  expression: "formValues.apprearance_beard"
                                }
                              },
                              [_vm._v("\n                No\n              ")]
                            )
                          ],
                          1
                        )
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: "Skills", "label-for": "v-skills" } },
                        [
                          _c(
                            "b-form-checkbox-group",
                            {
                              staticClass: "demo-inline-spacing",
                              attrs: { id: "checkbox-group-2", name: "Skills" },
                              model: {
                                value: _vm.formValues.skills,
                                callback: function($$v) {
                                  _vm.$set(_vm.formValues, "skills", $$v)
                                },
                                expression: "formValues.skills"
                              }
                            },
                            [
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "Forklift" } },
                                [_vm._v(" Forklift ")]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "Waiter" } },
                                [_vm._v(" Waiter ")]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "Kitchen Helper" } },
                                [
                                  _vm._v(
                                    "\n                Kitchen Helper\n              "
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "Electrician" } },
                                [
                                  _vm._v(
                                    "\n                Electrician\n              "
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "Plumber" } },
                                [_vm._v(" Plumber ")]
                              ),
                              _vm._v(" "),
                              _c(
                                "b-form-checkbox",
                                { attrs: { value: "AC technician" } },
                                [
                                  _vm._v(
                                    "\n                AC technician\n              "
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Misconduct Report",
                            "label-for": "v-misconduct"
                          }
                        },
                        [
                          _c("b-form-textarea", {
                            attrs: {
                              id: "v-misconduct",
                              placeholder: "Misconduct Report",
                              rows: "3"
                            },
                            model: {
                              value: _vm.formValues.misconduct,
                              callback: function($$v) {
                                _vm.$set(_vm.formValues, "misconduct", $$v)
                              },
                              expression: "formValues.misconduct"
                            }
                          })
                        ],
                        1
                      )
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: { type: "submit", variant: "primary" },
                          on: {
                            click: function($event) {
                              ;(_vm.page = 1),
                                (_vm.searchTerm = ""),
                                _vm.closeSearch(),
                                _vm.getEmployees()
                            }
                          }
                        },
                        [_c("span", [_vm._v("GO")])]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: { type: "submit", variant: "outline-primary" },
                          on: { click: _vm.resetSearch }
                        },
                        [_c("span", [_vm._v("Reset")])]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-button",
                        {
                          directives: [
                            {
                              name: "ripple",
                              rawName: "v-ripple.400",
                              value: "rgba(255, 255, 255, 0.15)",
                              expression: "'rgba(255, 255, 255, 0.15)'",
                              modifiers: { "400": true }
                            }
                          ],
                          staticClass: "mr-1",
                          attrs: {
                            type: "submit",
                            variant: "outline-secondary"
                          },
                          on: { click: _vm.closeSearch }
                        },
                        [_c("span", [_vm._v("Cancel")])]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("b-table", {
        staticClass: "mb-0 bg-white",
        attrs: {
          items: _vm.items,
          fields: _vm.fields,
          responsive: "",
          busy: _vm.isBusy
        },
        scopedSlots: _vm._u([
          {
            key: "cell(Options)",
            fn: function(row) {
              return [
                _c("b-form-group", [
                  _c(
                    "div",
                    { staticClass: "d-flex align-items-center w-fit-content" },
                    [
                      _c(
                        "b-input-group",
                        [
                          _c(
                            "b-input-group-prepend",
                            [
                              _c(
                                "b-button",
                                {
                                  directives: [
                                    {
                                      name: "ripple",
                                      rawName: "v-ripple.400",
                                      value: "rgba(113, 102, 240, 0.15)",
                                      expression: "'rgba(113, 102, 240, 0.15)'",
                                      modifiers: { "400": true }
                                    }
                                  ],
                                  class: row.detailsShowing ? "active" : "",
                                  attrs: {
                                    size: "sm",
                                    variant: "outline-primary"
                                  },
                                  on: { click: row.toggleDetails }
                                },
                                [
                                  row.detailsShowing
                                    ? _c("feather-icon", {
                                        attrs: { icon: "ChevronUpIcon" }
                                      })
                                    : _c("feather-icon", {
                                        attrs: { icon: "ChevronDownIcon" }
                                      })
                                ],
                                1
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _vm.$ability.can("edit", "employees")
                            ? _c(
                                "b-input-group-append",
                                [
                                  _c(
                                    "b-button",
                                    {
                                      directives: [
                                        {
                                          name: "ripple",
                                          rawName: "v-ripple.400",
                                          value: "rgba(40, 199, 111, 0.15)",
                                          expression:
                                            "'rgba(40, 199, 111, 0.15)'",
                                          modifiers: { "400": true }
                                        }
                                      ],
                                      attrs: {
                                        size: "sm",
                                        to: {
                                          name: "Employee-edit",
                                          params: { empId: row.item.emp_id }
                                        },
                                        variant: "outline-primary text-success"
                                      }
                                    },
                                    [
                                      _c("feather-icon", {
                                        attrs: { icon: "EditIcon" }
                                      })
                                    ],
                                    1
                                  )
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.$ability.can("delete", "employees")
                            ? _c(
                                "b-input-group-append",
                                [
                                  _c(
                                    "b-button",
                                    {
                                      directives: [
                                        {
                                          name: "ripple",
                                          rawName: "v-ripple.400",
                                          value: "rgba(40, 199, 111, 0.15)",
                                          expression:
                                            "'rgba(40, 199, 111, 0.15)'",
                                          modifiers: { "400": true }
                                        }
                                      ],
                                      attrs: {
                                        size: "sm",
                                        variant: "outline-primary text-danger"
                                      },
                                      on: {
                                        click: function($event) {
                                          return _vm.deleteDocument(row.item.id)
                                        }
                                      }
                                    },
                                    [
                                      _c("feather-icon", {
                                        attrs: { icon: "Trash2Icon" }
                                      })
                                    ],
                                    1
                                  )
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "b-input-group-append",
                            [
                              _c(
                                "b-button",
                                {
                                  directives: [
                                    {
                                      name: "ripple",
                                      rawName: "v-ripple.400",
                                      value: "rgba(113, 102, 240, 0.15)",
                                      expression: "'rgba(113, 102, 240, 0.15)'",
                                      modifiers: { "400": true }
                                    }
                                  ],
                                  attrs: {
                                    size: "sm",
                                    variant: "outline-primary"
                                  }
                                },
                                [
                                  _c(
                                    "a",
                                    {
                                      attrs: {
                                        href: "tel:+" + row.item.contact_number
                                      }
                                    },
                                    [
                                      _c("feather-icon", {
                                        attrs: { icon: "PhoneIcon" }
                                      })
                                    ],
                                    1
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                ])
              ]
            }
          },
          {
            key: "row-details",
            fn: function(row) {
              return [
                _c(
                  "b-card",
                  { attrs: { "no-body": "" } },
                  [
                    _c(
                      "b-row",
                      [
                        _c(
                          "b-col",
                          { attrs: { cols: "12" } },
                          [
                            _c(
                              "b-tabs",
                              { attrs: { pills: "", card: "", vertical: "" } },
                              [
                                _c(
                                  "b-tab",
                                  {
                                    attrs: {
                                      title: "Employee Details",
                                      active: ""
                                    }
                                  },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Name")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.name) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Emp ID")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.emp_id) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Nationality")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.nationality
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Religion")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.religion) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Date of Birth")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      _vm._f("moment")(
                                                        row.item.dob,
                                                        "DD/MM/YYYY"
                                                      )
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Age")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.age) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Joining Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      _vm._f("moment")(
                                                        row.item.joining_date,
                                                        "DD/MM/YYYY"
                                                      )
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Mobile Number")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.contact_number
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Profile Photo")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.emp_photo)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href:
                                                            row.item.emp_photo,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src:
                                                            row.item.emp_photo,
                                                          src_large:
                                                            row.item.emp_photo,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src:
                                                              row.item.emp_photo
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-tab",
                                  { attrs: { title: "Benefits" } },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Benefits Details")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.benefits) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("IBAN (NCB)")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.iban) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Vacation Due Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      _vm._f("moment")(
                                                        row.item.vacation_date,
                                                        "DD/MM/YYYY"
                                                      )
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Notes")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.notes) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-tab",
                                  { attrs: { title: "Legal Details" } },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Iqama Number")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.iqama_no) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Iqama Expiry Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c(
                                                  "b-badge",
                                                  {
                                                    attrs: {
                                                      pill: "",
                                                      variant:
                                                        row.item
                                                          .iqamaExpireVarient[
                                                          "dateVariant"
                                                        ]
                                                    }
                                                  },
                                                  [
                                                    _vm._v(
                                                      "\n                        " +
                                                        _vm._s(
                                                          _vm._f("moment")(
                                                            row.item
                                                              .iqamaExpireVarient[
                                                              "iqama_expiry_date"
                                                            ],
                                                            "DD/MM/YYYY"
                                                          )
                                                        ) +
                                                        "\n                      "
                                                    )
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Iqama Profession")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.iqama_profession
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Iqama Upload")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.iqama)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href: row.item.iqama,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src: row.item.iqama,
                                                          src_large:
                                                            row.item.iqama,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src: row.item.iqama
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Passport Number")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.passport_number
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Passport Expiry Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c(
                                                  "b-badge",
                                                  {
                                                    attrs: {
                                                      pill: "",
                                                      variant:
                                                        row.item
                                                          .passportExpireVarient[
                                                          "dateVariant"
                                                        ]
                                                    }
                                                  },
                                                  [
                                                    _vm._v(
                                                      "\n                        " +
                                                        _vm._s(
                                                          _vm._f("moment")(
                                                            row.item
                                                              .passportExpireVarient[
                                                              "passport_expiry_date"
                                                            ],
                                                            "DD/MM/YYYY"
                                                          )
                                                        ) +
                                                        "\n                      "
                                                    )
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Passport File")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.passport)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href:
                                                            row.item.passport,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src:
                                                            row.item.passport,
                                                          src_large:
                                                            row.item.passport,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src:
                                                              row.item.passport
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Visa File")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.passport_2)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href:
                                                            row.item.passport_2,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src:
                                                            row.item.passport_2,
                                                          src_large:
                                                            row.item.passport_2,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src:
                                                              row.item
                                                                .passport_2
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("AJEER File")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.ajeer)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href: row.item.ajeer,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src: row.item.ajeer,
                                                          src_large:
                                                            row.item.ajeer,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src: row.item.ajeer
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v(
                                                    "AJEER Expiration Date"
                                                  )
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c(
                                                  "b-badge",
                                                  {
                                                    attrs: {
                                                      pill: "",
                                                      variant:
                                                        row.item
                                                          .ajeerExpireVarient[
                                                          "dateVariant"
                                                        ]
                                                    }
                                                  },
                                                  [
                                                    _vm._v(
                                                      "\n                        " +
                                                        _vm._s(
                                                          _vm._f("moment")(
                                                            row.item
                                                              .ajeerExpireVarient[
                                                              "ajeer_expiration_date"
                                                            ],
                                                            "DD/MM/YYYY"
                                                          )
                                                        ) +
                                                        "\n                      "
                                                    )
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v(
                                                    "Medical Insurance File"
                                                  )
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _vm.isPdf(row.item.insurance_card)
                                              ? _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        staticClass:
                                                          "btn btn-primary",
                                                        attrs: {
                                                          href:
                                                            row.item
                                                              .insurance_card,
                                                          target: "_blank"
                                                        }
                                                      },
                                                      [_vm._v("View file")]
                                                    )
                                                  ]
                                                )
                                              : _c(
                                                  "b-col",
                                                  { attrs: { cols: "10" } },
                                                  [
                                                    _c(
                                                      "enlargeable-image",
                                                      {
                                                        attrs: {
                                                          src:
                                                            row.item
                                                              .insurance_card,
                                                          src_large:
                                                            row.item
                                                              .insurance_card,
                                                          animation_duration:
                                                            "600"
                                                        }
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticStyle: {
                                                            "max-height": "80px"
                                                          },
                                                          attrs: {
                                                            thumbnail: "",
                                                            src:
                                                              row.item
                                                                .insurance_card
                                                          }
                                                        })
                                                      ],
                                                      1
                                                    )
                                                  ],
                                                  1
                                                )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v(
                                                    "Medical Insurance Expiry Date"
                                                  )
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c(
                                                  "b-badge",
                                                  {
                                                    attrs: {
                                                      pill: "",
                                                      variant:
                                                        row.item
                                                          .insuranceExpireVarient[
                                                          "dateVariant"
                                                        ]
                                                    }
                                                  },
                                                  [
                                                    _vm._v(
                                                      "\n                        " +
                                                        _vm._s(
                                                          _vm._f("moment")(
                                                            row.item
                                                              .insuranceExpireVarient[
                                                              "insurance_card_expirationDate"
                                                            ],
                                                            "DD/MM/YYYY"
                                                          )
                                                        ) +
                                                        "\n                      "
                                                    )
                                                  ]
                                                )
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-tab",
                                  { attrs: { title: "Vendor Details" } },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Vendor")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.vendor) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Basic Salary")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      Number(
                                                        row.item.salary_rate
                                                      ).toLocaleString()
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-tab",
                                  { attrs: { title: "Client Details" } },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Client")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.client) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Location")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.client_location
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Status")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.status) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Accommodation")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.accommodation
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Start Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      _vm._f("moment")(
                                                        row.item.contract_start,
                                                        "DD/MM/YYYY"
                                                      )
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Stop Date")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      _vm._f("moment")(
                                                        row.item
                                                          .project_stop_date,
                                                        "DD/MM/YYYY"
                                                      )
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-tab",
                                  { attrs: { title: "Employee Review" } },
                                  [
                                    _c(
                                      "b-card-text",
                                      [
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("English Language")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c("b-form-rating", {
                                                  attrs: {
                                                    "no-border": "",
                                                    value: row.item.lang_eng,
                                                    variant: "warning",
                                                    readonly: "",
                                                    inline: ""
                                                  }
                                                })
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Arabic Language")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c("b-form-rating", {
                                                  attrs: {
                                                    "no-border": "",
                                                    value: row.item.lang_ar,
                                                    variant: "warning",
                                                    readonly: "",
                                                    inline: ""
                                                  }
                                                })
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Hindi Language")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c("b-form-rating", {
                                                  attrs: {
                                                    "no-border": "",
                                                    value: row.item.lang_hind,
                                                    variant: "warning",
                                                    readonly: "",
                                                    inline: ""
                                                  }
                                                })
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Presentable rating")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c("b-form-rating", {
                                                  attrs: {
                                                    "no-border": "",
                                                    value:
                                                      row.item
                                                        .appearance_presentable,
                                                    variant: "warning",
                                                    readonly: "",
                                                    inline: ""
                                                  }
                                                })
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Beard?")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item
                                                        .apprearance_beard == 1
                                                        ? "Yes"
                                                        : "No"
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [_c("strong", [_vm._v("Skills")])]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _c(
                                                  "b-list-group",
                                                  { attrs: { horizontal: "" } },
                                                  _vm._l(
                                                    row.item.skills,
                                                    function(oneSkill) {
                                                      return _c(
                                                        "b-list-group-item",
                                                        { key: oneSkill },
                                                        [
                                                          _vm._v(
                                                            "\n                          " +
                                                              _vm._s(oneSkill) +
                                                              "\n                        "
                                                          )
                                                        ]
                                                      )
                                                    }
                                                  ),
                                                  1
                                                )
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Misconduct Report")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(
                                                      row.item.misconduct
                                                    ) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "b-row",
                                          {
                                            staticClass:
                                              "border-bottom border-left"
                                          },
                                          [
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "2" } },
                                              [
                                                _c("strong", [
                                                  _vm._v("Overall Rating")
                                                ])
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-col",
                                              { attrs: { cols: "10" } },
                                              [
                                                _vm._v(
                                                  "\n                      " +
                                                    _vm._s(row.item.rating) +
                                                    "\n                    "
                                                )
                                              ]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ],
                                  1
                                )
                              ],
                              1
                            )
                          ],
                          1
                        )
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "b-row",
                      [
                        _c(
                          "b-col",
                          {
                            staticClass: "text-right px-3",
                            attrs: { cols: "12" }
                          },
                          [
                            _c("strong", [_vm._v("Created By : ")]),
                            _vm._v(
                              "\n            " +
                                _vm._s(row.item.created_by) +
                                "\n          "
                            )
                          ]
                        )
                      ],
                      1
                    ),
                    _vm._v(" "),
                    row.item.updated_by
                      ? _c(
                          "b-row",
                          [
                            _c(
                              "b-col",
                              {
                                staticClass: "text-right px-3",
                                attrs: { cols: "12" }
                              },
                              [
                                _c("strong", [_vm._v("Updated By : ")]),
                                _vm._v(
                                  "\n            " +
                                    _vm._s(row.item.updated_by) +
                                    "\n          "
                                )
                              ]
                            )
                          ],
                          1
                        )
                      : _vm._e()
                  ],
                  1
                )
              ]
            }
          },
          {
            key: "cell(iqama)",
            fn: function(data) {
              return [
                _c(
                  "enlargeable-image",
                  {
                    attrs: {
                      src: data.value,
                      src_large: data.value,
                      animation_duration: "600"
                    }
                  },
                  [_c("b-img", { attrs: { thumbnail: "", src: data.value } })],
                  1
                )
              ]
            }
          },
          {
            key: "cell(passport)",
            fn: function(data) {
              return [
                _c(
                  "enlargeable-image",
                  {
                    attrs: {
                      src: data.value,
                      src_large: data.value,
                      animation_duration: "600"
                    }
                  },
                  [_c("b-img", { attrs: { thumbnail: "", src: data.value } })],
                  1
                )
              ]
            }
          },
          {
            key: "cell(passport_2)",
            fn: function(data) {
              return [
                _c(
                  "enlargeable-image",
                  {
                    attrs: {
                      src: data.value,
                      src_large: data.value,
                      animation_duration: "600"
                    }
                  },
                  [_c("b-img", { attrs: { thumbnail: "", src: data.value } })],
                  1
                )
              ]
            }
          },
          {
            key: "cell(ajeer)",
            fn: function(data) {
              return [
                _c(
                  "enlargeable-image",
                  {
                    attrs: {
                      src: data.value,
                      src_large: data.value,
                      animation_duration: "600"
                    }
                  },
                  [_c("b-img", { attrs: { thumbnail: "", src: data.value } })],
                  1
                )
              ]
            }
          },
          {
            key: "cell(insurance_card)",
            fn: function(data) {
              return [
                _c(
                  "enlargeable-image",
                  {
                    attrs: {
                      src: data.value,
                      src_large: data.value,
                      animation_duration: "600"
                    }
                  },
                  [_c("b-img", { attrs: { thumbnail: "", src: data.value } })],
                  1
                )
              ]
            }
          },
          {
            key: "cell(iqamaExpireVarient)",
            fn: function(data) {
              return [
                _c(
                  "b-badge",
                  { attrs: { pill: "", variant: data.value["dateVariant"] } },
                  [
                    _vm._v(
                      "\n        " +
                        _vm._s(
                          _vm._f("moment")(
                            data.value["iqama_expiry_date"],
                            "DD/MM/YYYY"
                          )
                        ) +
                        "\n      "
                    )
                  ]
                )
              ]
            }
          },
          {
            key: "cell(status)",
            fn: function(data) {
              return [
                _c(
                  "b-badge",
                  {
                    attrs: { pill: "", variant: _vm.statusVariant(data.value) }
                  },
                  [_vm._v("\n        " + _vm._s(data.value) + "\n      ")]
                )
              ]
            }
          },
          {
            key: "table-busy",
            fn: function() {
              return [
                _c(
                  "div",
                  { staticClass: "text-center text-primary my-2" },
                  [
                    _c("b-spinner", { staticClass: "align-middle" }),
                    _vm._v(" "),
                    _c("strong", [_vm._v("Loading...")])
                  ],
                  1
                )
              ]
            },
            proxy: true
          }
        ])
      }),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "d-flex justify-content-end" },
        [
          _c("b-pagination", {
            attrs: {
              "total-rows": _vm.count,
              "per-page": _vm.pageSize,
              pills: ""
            },
            on: { change: _vm.handlePageChange },
            model: {
              value: _vm.page,
              callback: function($$v) {
                _vm.page = $$v
              },
              expression: "page"
            }
          })
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);