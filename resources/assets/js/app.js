
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery-serializejson');

window.Vue = require('vue');

Vue.component('modal', require('./components/Modal.vue.html'));
Vue.component('panel', require('./components/Panel.vue.html'));
Vue.component('app-alert', require('./components/AppAlert.vue.html'));
Vue.component('flex-table', require('./components/FlexTable.vue.html'));
Vue.component('search-bar', require('./components/SearchBar.vue.html'));

import {APIPagerDatasource} from './components/APIPagerDatasource';
window.APIPagerDatasource = APIPagerDatasource;
window.Cookies = require('js-cookie');



window.dataDiff = function(obj, base) {
    let newObj = {};
    _.each(obj, (v, k) => {
        if (_.isObject(v) && _.isEqual(v, base[k])) {
            return;
        }

        if (v == base[k]) {
            return;
        }

        newObj[k] = v;
    });

    return newObj;
};


jQuery.addErrorStates = function(form, errors) {
    _.each(errors, (v, k) => {
        $(`[name=${k}]`, form).parents('.form-group').addClass('has-error').find('.tooltip-inner').text(v);
    });

    const elems = ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], " +
        "[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], " +
        "[type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], " +
        "[type='radio'], [type='checkbox']";

    $(`.has-error:eq(0)`).find(elems).focus();
};

jQuery.clearErrorStates = function(form, fields) {
    if (jQuery.isArray(fields)) {
        _.each(fields, (v) => {
            $(`[name=${v}]`, form).parents('.form-group').removeClass('has-error');
        });
    } else {
        $('.form-group', form).removeClass('has-error');
    }
};

jQuery.fn.fill = function(data) {
    if (!$(this).is('form')) {
        return;
    }

    const elems = $(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], " +
        "[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], " +
        "[type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], " +
        "[type='radio'], [type='checkbox'], [type='hidden']", this);

    _.each(data, (v, k) => {
        const elem = elems.filter(`[name=${k}]`);
        switch (true) {
            case elem.attr('type') == 'radio':
                elem.each(function (value, key) {
                    if($(key).attr('value') == v){
                        $(this).attr("checked", true);
                    }
                });
                break;
            default:
                elem.val(v);
                break;
        }
    });
};

jQuery(document).on('submit', 'form[data-toggle="jsvalidator"]', function (e) {
    if (!$(this).prop('jsvalidator-initialized')) {
        let rules = window.formValidators && window.formValidators[$(this).attr('name')];

        let elems = ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], " +
            "[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], " +
            "[type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], " +
            "[type='radio'], [type='checkbox']";
        $(elems, this).each(function() {
            let tip = $('<div class="tooltip bottom fade" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>');

            $(this).on('focus', function() {
                tip.addClass('in');
            }).on('blur', function() {
                tip.removeClass('in');
            });

            if ($(this).parent('.input-group').length || $(this).prop('type') === 'checkbox' || $(this).prop('type') === 'radio') {
                tip.insertAfter($(this).parent());
                // else just place the validation message immediatly after the input
            } else {
                tip.insertAfter($(this));
            }
        });


        //表单字段的前端验证
        if (rules) {
            $(this).validate({
                errorClass: 'hidden app-error-msg',
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').find('.tooltip-inner').text(
                        $(element).closest('.form-group').find('.app-error-msg').text()
                    );
                },
                showErrors: function(errors) {
                    this.defaultShowErrors();

                    for (let i = 0; this.errorList[ i ]; i++ ) {
                        let error = this.errorList[ i ];
                        $(error.element).closest('.form-group').find('.tooltip-inner').text(error.message);
                    }
                },
                rules
            });
        }

        $(this).prop('jsvalidator-initialized', 1);
    }

    $('.form-group', this).removeClass('has-error').removeClass('has-success');
    if (!$(this).valid()) {
        return false;
    }

    $(this).trigger('jsvalidator.ok');
    return false;
});