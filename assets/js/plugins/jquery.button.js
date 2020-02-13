"use strict";
import $ from "jquery";

const ns = ".dev.button";
const defaults = {
    "loadingText": "<i class='fad fa-sync-alt fa-spin fa-fw'></i>"
};

function attachHandlers($$, options) {

    $$.on("reset" + ns, function () {
        $$.prop("disabled", false);
        const text = options['resetText'] || "";

        $$.html(text);
        $$.css("min-width", "");
    });

    $$.on("loading" + ns, function () {
        $$.prop("disabled", true);
        $$.trigger("set" + ns, ["loading"]);
    });

    $$.on("set" + ns, function (event, state) {

        const key = state + "Text";
        const text = options[key] || "";

        $$.css("min-width", options.width);
        $$.css("min-height", options.height);
        $$.html(text);
    })
}

$.fn.button = function (state) {

    return this.each(function () {

        const $button = $(this);
        const data = $button.data(ns);

        if (!data) {
            const options = $.extend({}, defaults, $button.data(), {
                resetText: $button.html(),
                width: $button.outerWidth(),
                height: $button.outerHeight()
            });
            $button.data(ns, options);
            attachHandlers($button, options);
        }

        $button.trigger(state + ns)
    });
};
