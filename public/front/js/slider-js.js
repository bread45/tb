jQuery(document).ready(function($) {
    let slider = $('.owl-carousel');
    slider.each(function() {
        $(this).owlCarousel({
            nav: true,
            loop: true,
            autoplay: false,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            dots: false,
            pagination: false,
            margin: 5,
            autoHeight: false,
            stagePadding: 0,
            responsive: {
                0: {
                    items: 1,
                },
                767: {
                    items: 2,

                },
                1000: {
                    items: 2,
                }
            }
        });
    });
});