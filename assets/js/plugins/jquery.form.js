"use strict";
import $ from "jquery";
import validator from './jquery.form/validator';
import "./jquery.button";

const $doc = $(document);
const NAMESPACE = ".dv.form";

const Selector = {
    FORM: 'form',
    FEEDBACK: ".invalid-feedback, .invalid-tooltip",
    GROUP: '.form-group',
    INPUT: ':input',
};

const Events = {
    SUBMIT: 'submit',
    INPUT: 'input',
    ERROR: `error${NAMESPACE}`,
    COMPLETE: `complete${NAMESPACE}`,
    SUCCESS: `success${NAMESPACE}`,
    ABORTED: `aborted${NAMESPACE}`,
    VALIDATE: `validate${NAMESPACE}`,
    BEFORE_SUBMIT: `before_submit${NAMESPACE}`,
};

const Class = {
    VALIDATED: 'was-validated',
    INVALID: "is-invalid",
    VALID: "is-valid",
};

const Prototype = {
    ELEMENT_ERROR: '<span class="mb-0 d-block"><span class="form-error-message">__error__</span></span>',
    FORM_ERROR: "<div class='alert alert-danger alert-iconable' id='__id__'>" +
        "<div class='alert-body'>__message__</div>" +
        "<div class='alert-icon'><i class='fad fa-times-circle'></i></div>" +
        "</div>"
};


function setElementErrors($elem, errors) {
    if (!errors) {
        return;
    }
    let $errors = $elem.next(Selector.FEEDBACK);
    if (!$errors.length) {
        $errors = $elem.closest(Selector.GROUP).find(Selector.FEEDBACK);
    }

    if ($errors.length > 0) {
        $errors.html("");
        for (let i = 0; i < errors.length; i++) {
            $errors.append(Prototype.ELEMENT_ERROR.replace(/__error__/g, errors[i]))
        }
    }
}

//
function hideErrors($form) {
    $form.removeClass(Class.VALIDATED);
    $form.find(`.${Class.VALID}, .${Class.INVALID}`)
        .removeClass(`${Class.VALID} ${Class.INVALID}`)
        .find(Selector.FEEDBACK)
        .html("");

    const id = 'error_' + $form.attr('id');
    $('#' + id).remove();
}

function setValidationMessageIfNeeded(elem) {
    if (!elem.validity.valid) {
        const message = validator.errorMessage(elem);
        setElementErrors($(elem), [message]);
    }
}

//native validation
$doc.on(Events.VALIDATE, Selector.FORM, function (e) {

    if (this.checkValidity() === false) {
        e.preventDefault();
        e.stopPropagation();

        $(this).find(Selector.INPUT).each((ind, elem) => setValidationMessageIfNeeded(elem));
        const el = $(this).find(Selector.INPUT).filter((ind, el) => !el.validity.valid)[0];
        el.scrollIntoView()
    }

    this.classList.add(Class.VALIDATED);
});


// add native on change validation
$doc.on(Events.INPUT, `${Selector.FORM} ${Selector.INPUT}`, function () {
    setValidationMessageIfNeeded(this);
});


$doc.on(Events.BEFORE_SUBMIT, Selector.FORM, function (e) {
    const $$ = $(this);
    hideErrors($$);
    $$.prop("disabled", true);
    $$.find("[type='submit']").button("loading");
});

function showElementErrors($elem, errors) {
    setElementErrors($elem, errors);
    $elem
        .addClass(Class.INVALID)
        .attr('aria-describedby', $elem.attr('id') + '_error');
}

function showDefaultError($form, msg) {
    const id = 'error_' + $form.attr('id');
    const $html = $(Prototype.FORM_ERROR
        .replace('__id__', id)
        .replace('__message__', msg)
    );

    $('#' + id).remove();
    $form.prepend($html);
}

function showErrors($form, errors, parent) {

    let _errors, selector, $element;
    for (let key in errors) {

        selector = parent + "_" + key;
        _errors = errors[key];

        if (typeof errors[key] === "string") {
            showDefaultError($form, errors[key])
        } else if (!$.isArray(errors[key])) {
            showErrors($form, _errors, selector)
        } else {
            $element = $("#" + selector);
            showElementErrors($element, _errors)
        }
    }
}


$doc.on(`${Events.COMPLETE} ${Events.ABORTED}`, Selector.FORM, function () {
    const $$ = $(this);
    $$.prop("disabled", false);
    $$.find("[type='submit']").button("reset");
});


$doc.on(Events.ERROR, Selector.FORM, function (msg) {
    // showDefaultError(msg)
});


$doc.on(Events.SUBMIT, Selector.FORM, function (event) {

    const $$ = $(this);
    if ($$.data('type') === 'manual') {
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const parent = $$.attr("name");
    // const $result = $$.find("[data-form-control='result']");
    // const $content = $$.find("[data-form-control='content']");


    if (this.busy) return false;
    this.busy = true;

    //native validation first
    $$.trigger(event = $.Event(Events.VALIDATE));
    if (event.isDefaultPrevented()) {
        this.busy = false;
        return;
    }


    let data = {};
    let extend = {};

    if ($$.find("input[type='file']").length) {
        data = new FormData($$[0]);
        extend = {
            async: true, //recommendation to use false
            cache: false,
            contentType: false,
            processData: false
        };
    } else {
        data = $$.serialize();
    }

    let options = {
        url: $$.attr("action") || location.href,
        type: $$.attr("method"),
        data: data,
        dataType: 'json',
        complete: (jqXHR) => {
            this.busy = false;

            const data = jqXHR.responseJSON;
            const code = jqXHR.status;

            (function () {
                if ([201, 301, 302, 303, 307, 308].indexOf(code) >= 0) {
                    if (!data.location) {
                        console.error('RedirectResponse must contain location header');
                        return;
                    }

                    location.href = data.location;
                    return
                }

                if ([200].indexOf(code) >= 0) {
                    $$.trigger(event = $.Event(Events.SUCCESS), [data]);
                    if (event.isDefaultPrevented()) return;

                    if (data.html !== undefined) {
                        $$.html(data.html)
                    }

                    if (data.message !== undefined && data.message) {
                        console.info(data.message);
                    }

                    return;
                }


                if ([400].indexOf(code) >= 0) {
                    $$.trigger(event = $.Event(Events.ERROR), [data]);
                    if (event.isDefaultPrevented()) return;

                    //default behaviour
                    if (data.errors !== undefined) {
                        showErrors($$, data.errors, parent);
                    }

                    if (data.message !== undefined && data.message) {
                        console.error(data.message);
                    }
                }
            })();

            $$.trigger(Events.COMPLETE, [data])
        },
    };

    options = $.extend({}, options, extend);

    $$.trigger(event = $.Event(Events.BEFORE_SUBMIT), [options]);
    if (event.isDefaultPrevented()) {
        $$.trigger(Events.ABORTED);
        this.busy = false;
        return;
    }

    $.ajax(options);
});
