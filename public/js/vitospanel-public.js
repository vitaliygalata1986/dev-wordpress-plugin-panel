(function ($) {
    const el = $('#slidebox')
    if (el.length > 0) {
        el.slideBox({
            position: 'bottom right', // can be [bottom|middle|top] and [left|center|right]
            appearsFrom: 'right', // can be [left|top|right|bottom]
            slideDuration: 500, // animation duration in ms
            target: 'h2', // can be a string (jQuery selector) or an integer (offset in px)
            closeLink: '#close' // a string that is the jQuery selector of the closing element
        });
    }
})(jQuery);