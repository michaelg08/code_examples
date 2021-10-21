define([
    'Magento_Ui/js/form/element/ui-select'
], function (Select) {
    'use strict';
    return Select.extend({
        /**
         * Parse data and set it to options.
         *
         * @param {Object} data - Response data object.
         * @returns {Object}
         */
        setParsed: function (data) {

            var option = this.parseData(data);

            if (data.error) {
                return this;
            }
            this.options([]);
            this.setOption(option);
            this.set('newOption', option);
        },
        /**
         * Normalize option object.
         *
         * @param {Object} data - Option object.
         * @returns {Object}
         */
        parseData: function (data) {

            return {
                value: data.product.entity_id,
                label: data.product.name
            };
        },
        /**
        * Filtered options list by value from filter options list
        *
        * @param {Array} list - option list
        * @param {String} value
        *
        * @returns {Array} filters result
        */
        _getFilteredArray: function (list, value) {

            var i = 0,
                array = [],
                curOption;

            for (i; i < list.length; i++) {
                curOption = list[i].label.toLowerCase();

                if (curOption.indexOf(value) > -1) {
                    array.push(list[i]); /*eslint max-depth: [2, 4]*/
                }
            }

            return array;
        },

        /**
 * Get selected element labels
 *
 * @returns {Array} array labels
 */
        getSelected: function () {
            var selected = this.value();

            return this.cacheOptions.plain.filter(function (opt) {
                return _.isArray(selected) ?
                    _.contains(selected, opt.value) :
                    selected == opt.value;//eslint-disable-line eqeqeq
            });
        },

        /**
         * Toggle activity list element
         *
         * @param {Object} data - selected option data
         * @returns {Object} Chainable
         */
        toggleOptionSelected: function (data) {
            var isSelected = this.isSelected(data.value);

            if (this.lastSelectable && data.hasOwnProperty(this.separator)) {
                return this;
            }

            if (!this.multiple) {
                if (!isSelected) {
                    this.value(data.value);
                }
                this.listVisible(false);
            } else {
                if (!isSelected) { /*eslint no-lonely-if: 0*/
                    this.value.push(data.value);
                } else {
                    this.value(_.without(this.value(), data.value));
                }
            }

            return this;
        }

    });
});
