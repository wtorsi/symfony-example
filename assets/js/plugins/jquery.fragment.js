"use strict";
import $ from "jquery";
import '../bootstrap/modal'

const ns = ".dev.fragment";
const $doc = $(document);
const $body = $('body');
const selector = '[data-toggle=modal][href]';


$doc.on("init" + ns, selector, function (event) {

    const $$ = $(this);
    const target = $$.data('target');
    let $target = $(target);

    if ($target.length) {
        return;
    }


    $target = $('<div>', {id: target.replace('#', '')});
    $body.append($target);

    if (this.initialized) return;
    this.initialized = true;

    const url = $$.attr("href");
    $.ajax({
        url: url,
        dataType: "json",
        success: function (response) {
            let $target = $target.replaceWith(response.html);
            $target.attr('id', target.replace('#', ''))
            // $target.html(response.html);
        }
    })
});


$(() => {
    $(selector).trigger("init" + ns);
});