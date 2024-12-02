jQuery(document).ready(function ($) {

    $("#accordion").accordion({
        collapsible: true, // initially everything will be collapsed
        active: false // all panels will be collapsed. (Only works in combination with collapsible: true.)
        // active: 0
    });

    // event handler on select
    $('.vitospanel-select').on('change', function () {
        let $this = $(this) // current element
        let slideId = $(this).val() // value of the selected select (option)
        let articleId = $(this).data('article') // value of the selected data-article
        // console.log('$slideId = ',slideId, '$articleId = ', articleId); // slideId = 2 articleId = 334
        $.ajax({
            type: 'POST',
            url: ajaxurl, // this is variable predetermined
            data: {
                slideId,
                articleId,
                action: 'vitospanel_action',
                vitospanel_change_slide: vitospanelSlide.nonce, // nonce-сode
            },
            beforeSend: function () {
                $('#vitospanel_loader').fadeIn()
            },
            success: function (res) {
                res = JSON.parse(res) // parse the incoming JSON
                $('#vitospanel_loader').fadeOut(300, function () {
                    Swal.fire({
                        text: res.text,
                        icon: res.answer // в answer success / error
                    });
                    // res.text; text -> {"answer":"success","text":"Saved successfully"}
                })
            },
            error: function () {
                Swal.fire({
                    text: 'Error!',
                    icon: 'error'
                });
                // alert('Error!')
            }
        })
    })
});
