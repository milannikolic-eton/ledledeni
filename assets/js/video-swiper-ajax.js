document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-button');

    // Initialize Swiper (empty initially)
    let swiper = new Swiper('.swiper-container', {
        slidesPerView: 1.6,
        spaceBetween: 32,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Load Swiper content by AJAX
    function loadSwiperContent(categoryId = '') {
        const data = new FormData();
        data.append('action', 'load_video_swiper');
        data.append('category_id', categoryId);

        fetch(ajax_params.ajax_url, {
            method: 'POST',
            body: data,
        })
        .then(response => response.text())
        .then(content => {
            document.getElementById('video-slider-content').innerHTML = content;

            // Reinitialize Swiper to apply changes
            swiper.update();

            // Reinitialize VenoBox for dynamically loaded content
            new VenoBox({
                selector: '.video-slide',
            });
        })
        .catch(error => console.error('Error loading content:', error));
    }

    // Initial load
    loadSwiperContent();

    // Filter button click event
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const categoryId = this.getAttribute('data-category');

            // Remove active class from all buttons and add to the clicked one
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Load content based on selected category
            loadSwiperContent(categoryId);
        });
    });


    new VenoBox({
        selector: '.video-slide',
    });
});
