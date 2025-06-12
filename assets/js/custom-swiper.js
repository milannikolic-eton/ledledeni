/*document.addEventListener('DOMContentLoaded', function () {
    const swiperContainers = document.querySelectorAll('.swiper-gallery');

    swiperContainers.forEach((container) => {
        new Swiper(container, {
            loop: true,
            slidesPerView: 'auto', // Each slide will have its own width
            spaceBetween: 2, // Space between slides
            freeMode: true, // Allows free movement of slides
            grabCursor: true,
            autoHeight: false,
            autoplay:true,
        });
    });



});*/

document.addEventListener('DOMContentLoaded', function () {
    const swiperContainers = document.querySelectorAll('.swiper-gallery');

    swiperContainers.forEach((container) => {
        new Swiper(container, {
            loop: true,
            slidesPerView: 'auto', // Each slide will have its own width
            spaceBetween: 2, // Space between slides
            centeredSlides: false, 
            speed: 20000, 
            autoplay: {
                delay: 0, // Immediately move to next slide
                disableOnInteraction: false, // Continue after user interaction
            },
            grabCursor: true,
            allowTouchMove: true,
            freeMode: {
                enabled: true,
                momentum: false, // Disable momentum to keep consistent speed
                sticky: true,
            },
        });
    });
});