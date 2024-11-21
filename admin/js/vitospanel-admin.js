jQuery(document).ready(function ($) {

    $( "#accordion" ).accordion({
        collapsible: true, // изначально будет все свернуто
        active: false // все панели будут свернуты. (Работает только в сочетании с collapsible: true.)
        // active: 0
    });

});
