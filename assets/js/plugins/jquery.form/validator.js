'use strict';

import i18n from './i18n/index'

const validator = {
    errorMessage: (field) => {
        // Don't validate submits, buttons, file and reset inputs, and disabled fields
        if (field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') return;
        const validity = field.validity;
        if (validity.valid) return;

        const {message, attr} = validator.getDefaultMessage(field, validity);
        return i18n.trans(message, attr, 'ru');
    },


    // see https://html.spec.whatwg.org/multipage/form-control-infrastructure.html#the-constraint-validation-api
    getDefaultMessage: (field, validity) => {
        if (validity.valueMissing) return {message: 'Please fill out this field.', attr: {}};
        if (validity.typeMismatch) {
            if (field.type === 'email') return {message: 'Please type the valid Email address.', attr: {}};
            if (field.type === 'url') return {message: 'Please type the valid URL.', attr: {}};
            return {message: 'Please use the correct input type.', attr: {}};
        }
        if (validity.patternMismatch) return {message: 'Please match the requested format.', attr: {}};
        if (validity.tooLong) return {message: 'Please shorten this text.', attr: {}};
        if (validity.tooShort) return {message: 'Please lengthen this text.', attr: {}};
        if (validity.rangeUnderflow) return {
            message: 'validator.underflow',
            attr: {
                min: field.getAttribute('min')
            }
        };
        if (validity.rangeOverflow) return {
            message: 'validator.overflow',
            attr: {
                max: field.getAttribute('max')
            }
        };
        if (validity.stepMismatch) return {
            message: 'validator.step_mismatch',
            attr: {
                step: field.getAttribute('step')
            }
        };
        if (validity.badInput){
            if (field.type === 'email') return {message: 'validator.bad_input_email', attr: {}};
            if (field.type === 'url') return {message: 'validator.bad_input_url', attr: {}};
            if (field.type === 'datetime-local') return {message: 'validator.bad_input_datetime', attr: {}};
            if (field.type === 'datetime') return {message: 'validator.bad_input_datetime', attr: {}};
            if (field.type === 'time') return {message: 'validator.bad_input_datetime', attr: {}};
            if (field.type === 'number') return {message: 'validator.bad_input_number', attr: {}};
            return {message: 'validator.bad_input', attr: {}};
        }
        if (validity.customError) return {message: validity.customError, attr: {}};
        return {message: 'The value you entered for this field is invalid.', attr: {}};
    }
};

export default validator;