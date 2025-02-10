$(".mySlide").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    nextArrow:
        '<div class="slickArrow slickNext"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>',
    prevArrow:
        '<div class="slickArrow slickPrev"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>',
    autoplay: true,
    arrows: true,
    autoplaySpeed: 3000,
    responsive: [
        {
        breakpoint: 1025,
            settings: {
                slidesToShow: 3,
            },
        },
        {
        breakpoint: 992,
            settings: {
                slidesToShow: 2,
            },
        },
        {
        breakpoint: 600,
            settings: {
                slidesToShow: 1,
            },
        },
    ],
});