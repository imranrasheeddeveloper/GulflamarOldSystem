(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[45],{

/***/ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue":
/*!****************************************************************************!*\
  !*** ./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea& */ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea&");
/* harmony import */ var _VueGoogleAutocomplete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./VueGoogleAutocomplete.vue?vue&type=script&lang=js& */ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _VueGoogleAutocomplete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__["render"],
  _VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************!*\
  !*** ./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vue_loader_lib_index_js_vue_loader_options_VueGoogleAutocomplete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../vue-loader/lib??vue-loader-options!./VueGoogleAutocomplete.vue?vue&type=script&lang=js& */ "./node_modules/vue-loader/lib/index.js?!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_vue_loader_lib_index_js_vue_loader_options_VueGoogleAutocomplete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea&":
/*!***********************************************************************************************************!*\
  !*** ./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea& ***!
  \***********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vue_loader_lib_loaders_templateLoader_js_vue_loader_options_vue_loader_lib_index_js_vue_loader_options_VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../vue-loader/lib??vue-loader-options!./VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _vue_loader_lib_loaders_templateLoader_js_vue_loader_options_vue_loader_lib_index_js_vue_loader_options_VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _vue_loader_lib_loaders_templateLoader_js_vue_loader_options_vue_loader_lib_index_js_vue_loader_options_VueGoogleAutocomplete_vue_vue_type_template_id_a72a90ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./node_modules/vue-loader/lib/index.js?!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib??vue-loader-options!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

    const ADDRESS_COMPONENTS = {
        subpremise : 'short_name',
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        administrative_area_level_2: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    const CITIES_TYPE = ['locality', 'administrative_area_level_3'];
    const REGIONS_TYPE = ['locality', 'sublocality', 'postal_code', 'country',
        'administrative_area_level_1', 'administrative_area_level_2'];

    /*
      By default, we're only including basic place data because requesting these 
      fields place data is not additionally charged by Google. Please refer to:

      https://developers.google.com/maps/billing/understanding-cost-of-use#basic-data
    */
    const BASIC_DATA_FIELDS = ['address_components', 'adr_address', 'alt_id', 
        'formatted_address', 'geometry', 'icon', 'id', 'name', 
        'business_status', 'photo', 'place_id', 'scope', 'type', 'url', 
        'utc_offset_minutes', 'vicinity'];

    /* harmony default export */ __webpack_exports__["default"] = ({
        name: 'VueGoogleAutocomplete',

        props: {
          id: {
            type: String,
            required: true
          },

          classname: String,

          placeholder: {
            type: String,
            default: 'Start typing'
          },

          disabled: {
            type: Boolean,
            default: false
          },

          types: {
            type: String,
            default: 'address'
          },

          fields: {
            type: Array,
            default: function() {
              return BASIC_DATA_FIELDS;
            },
          },

          country: {
            type: [String, Array],
            default: null
          },

          enableGeolocation: {
            type: Boolean,
            default: false
          },

          geolocationOptions: {
            type: Object,
            default: null
          }
        },

        data() {
            return {
                /**
                 * The Autocomplete object.
                 *
                 * @type {Autocomplete}
                 * @link https://developers.google.com/maps/documentation/javascript/reference#Autocomplete
                 */
                autocomplete: null,

                /**
                 * Autocomplete input text
                 * @type {String}
                 */
                autocompleteText: '',

                geolocation: {
                    /**
                     * Google Geocoder Objet
                     * @type {Geocoder}
                     * @link https://developers.google.com/maps/documentation/javascript/reference#Geocoder
                     */
                    geocoder: null,

                    /**
                     * Filled after geolocate result
                     * @type {Coordinates}
                     * @link https://developer.mozilla.org/en-US/docs/Web/API/Coordinates
                     */
                    loc: null,

                    /**
                     * Filled after geolocate result
                     * @type {Position}
                     * @link https://developer.mozilla.org/en-US/docs/Web/API/Position
                     */
                    position: null
                }
            }
        },

        watch: {
            autocompleteText: function (newVal, oldVal) {
	            this.$emit('inputChange', { newVal, oldVal }, this.id);
            },
            country: function(newVal, oldVal) {
              this.autocomplete.setComponentRestrictions({
                country: this.country === null ? [] : this.country
              });
            }
        },

        mounted: function() {
          const options = {};

          if (this.types) {
            options.types = [this.types];
          }

          if (this.country) {
            options.componentRestrictions = {
              country: this.country
            };
          }

          this.autocomplete = new google.maps.places.Autocomplete(
                document.getElementById(this.id),
                options
            );

          this.autocomplete.setFields(this.fields);

          this.autocomplete.addListener('place_changed', this.onPlaceChanged);
        },

        methods: {
            /**
             * When a place changed
             */
            onPlaceChanged() {
                let place = this.autocomplete.getPlace();

                if (!place.geometry) {
                  // User entered the name of a Place that was not suggested and
                  // pressed the Enter key, or the Place Details request failed.
                  this.$emit('no-results-found', place, this.id);
                  return;
                }

                if (place.address_components !== undefined) {
                    // return returnData object and PlaceResult object
                    this.$emit('placechanged', this.formatResult(place), place, this.id);

                    // update autocompleteText then emit change event
                    this.autocompleteText = document.getElementById(this.id).value
                    this.onChange()
                }
            },

            /**
             * When the input gets focus
             */
            onFocus() {
              this.biasAutocompleteLocation();
              this.$emit('focus');
            },

            /**
             * When the input loses focus
             */
            onBlur() {
              this.$emit('blur');
            },

            /**
             * When the input got changed
             */
            onChange() {
              this.$emit('change', this.autocompleteText);
            },

            /**
             * When a key gets pressed
             * @param  {Event} event A keypress event
             */
            onKeyPress(event) {
              this.$emit('keypress', event);
            },

            /**
             * When a keyup occurs
             * @param  {Event} event A keyup event
             */
            onKeyUp(event) {
              this.$emit('keyup', event);
            },

            /**
             * Clear the input
             */
            clear() {
              this.autocompleteText = ''
            },

            /**
             * Focus the input
             */
            focus() {
              this.$refs.autocomplete.focus()
            },

            /**
             * Blur the input
             */
            blur() {
              this.$refs.autocomplete.blur()
            },

            /**
             * Update the value of the input
             * @param  {String} value
             */
            update (value) {
              this.autocompleteText = value
            },

            /**
             * Update the coordinates of the input
             * @param  {Coordinates} value
             */
            updateCoordinates (value) {
                if (!value && !(value.lat || value.lng)) return;
                if (!this.geolocation.geocoder) this.geolocation.geocoder = new google.maps.Geocoder();
                this.geolocation.geocoder.geocode({'location': value}, (results, status) => {
                    if (status === 'OK') {
                        results = this.filterGeocodeResultTypes(results);
                        if (results[0]) {
                            this.$emit('placechanged', this.formatResult(results[0]), results[0], this.id);
                            this.update(results[0].formatted_address);
                        } else {
                            this.$emit('error', 'no result for provided coordinates');
                        }
                    } else {
                        this.$emit('error', 'error getting address from coords');
                    }
                })
            },

            /**
             * Update location based on navigator geolocation
             */
            geolocate () {
                this.updateGeolocation ((geolocation, position) => {
                    this.updateCoordinates(geolocation)
                })
            },

            /**
             * Update internal location from navigator geolocation
             * @param  {Function} (geolocation, position)
             */
            updateGeolocation (callback = null) {
                if (navigator.geolocation) {
                    let options = {};
                    if(this.geolocationOptions) Object.assign(options, this.geolocationOptions);
                    navigator.geolocation.getCurrentPosition(position => {
                        let geolocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        this.geolocation.loc = geolocation;
                        this.geolocation.position = position;

                        if (callback) callback(geolocation, position);
                    }, err => {
                        this.$emit('error', 'Cannot get Coordinates from navigator', err);
                    }, options);
                }
            },


            // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.
            biasAutocompleteLocation () {
                if (this.enableGeolocation) {
                    this.updateGeolocation((geolocation, position) => {
                        let circle = new google.maps.Circle({
                            center: geolocation,
                            radius: position.coords.accuracy
                        });
                        this.autocomplete.setBounds(circle.getBounds());
                    })
                }
            },

            /**
             * Format result from Geo google APIs
             * @param place
             * @returns {{formatted output}}
             */
            formatResult (place) {
                let returnData = {};
                for (let i = 0; i < place.address_components.length; i++) {
                    let addressType = place.address_components[i].types[0];

                    if (ADDRESS_COMPONENTS[addressType]) {
                        let val = place.address_components[i][ADDRESS_COMPONENTS[addressType]];
                        returnData[addressType] = val;
                    }
                }

                returnData['latitude'] = place.geometry.location.lat();
                returnData['longitude'] = place.geometry.location.lng();
                return returnData
            },

            /**
             * Extract configured types out of raw result as
             * Geocode API does not allow to do it
             * @param results
             * @returns {GeocoderResult}
             * @link https://developers.google.com/maps/documentation/javascript/reference#GeocoderResult
             */
            filterGeocodeResultTypes (results) {
                if (!results || !this.types) return results;
                let output = [];
                let types = [this.types];
                if (types.includes('(cities)')) types = types.concat(CITIES_TYPE);
                if (types.includes('(regions)')) types = types.concat(REGIONS_TYPE);

                for (let r of results) {
                    for (let t of r.types) {
                        if (types.includes(t)) {
                            output.push(r);
                            break;
                        }
                    }
                }
                return output;
            }
        }
    });


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue?vue&type=template&id=a72a90ea& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
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
  return _c("input", {
    directives: [
      {
        name: "model",
        rawName: "v-model",
        value: _vm.autocompleteText,
        expression: "autocompleteText"
      }
    ],
    ref: "autocomplete",
    class: _vm.classname,
    attrs: {
      type: "text",
      id: _vm.id,
      placeholder: _vm.placeholder,
      disabled: _vm.disabled
    },
    domProps: { value: _vm.autocompleteText },
    on: {
      focus: function($event) {
        return _vm.onFocus()
      },
      blur: function($event) {
        return _vm.onBlur()
      },
      change: _vm.onChange,
      keypress: _vm.onKeyPress,
      keyup: _vm.onKeyUp,
      input: function($event) {
        if ($event.target.composing) {
          return
        }
        _vm.autocompleteText = $event.target.value
      }
    }
  })
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);