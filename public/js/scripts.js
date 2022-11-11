(function() {
    "use strict";

    // alert("ok");

    // $(document).ready(function($) {

        // Scroll Top
            $(window).scroll(function () {
                if ($(this).scrollTop() > 400) {
                    $('.scrollup').fadeIn();
                } else {
                    $('.scrollup').fadeOut();
                }
            });

            $('.scrollup').click(function () {
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
                return false;
            });

        // Featured On
        $('.feat-branding-area').owlCarousel({
            items: 1,
            loop: true,
            margin: 30,
            nav: true,
            navText: false,
            autoplay: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });


        // Date Picker
        $(".date").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });


        // Dropdown animation
        $('.dropdown').on('show.bs.dropdown', function (e) {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
        });

        $('.dropdown').on('hide.bs.dropdown', function (e) {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
        });


    $('.data-table').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],

    });

    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $(window).load(function() {

    });

}(jQuery));

// Select2

$('#select2').select2({
    placeholder: 'Select a month'
});

$('select').change(function() {
    
    var value = $(this).val();
 
    $(this).siblings('select').children('option').each(function() {
        if ( $(this).val() === value ) {
            $(this).attr('disabled', true).siblings().removeAttr('disabled');   
        }
    });
    
});
