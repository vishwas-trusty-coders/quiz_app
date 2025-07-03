jQuery(document).ready(function ($) {
    // Tab switching functionality
    $('.tab-button').on('click', function () {
        const tabId = $(this).data('tab'); // Get the target tab's ID

        // Remove active class from all buttons and content
        $('.tab-button').removeClass('active');
        $('.tab-content').removeClass('active');

        // Add active class to the clicked button and corresponding content
        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });
});

$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        navText: ['<i class="fa-solid fa-arrow-left"></i>', '<i class="fa-solid fa-arrow-right"></i>'],
        responsive: {
            0: {
                items: 1
            },
            980: {
                items: 2
            }
        }
    });
});




document.addEventListener("DOMContentLoaded", function () {
    // Select all accordion header elements
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    // Add click event to each header
    accordionHeaders.forEach((header) => {
        header.addEventListener('click', function () {
            const item = this.parentElement; // Get the accordion item

            // Close all other accordion items
            document.querySelectorAll('.accordion-item').forEach((otherItem) => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });

            // Toggle the active class on the clicked item
            item.classList.toggle('active');
        });
    });
});





document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu-div');

    toggleButton.addEventListener('click', function () {
        menu.classList.toggle('show');
    });
});




$(document).ready(function() {
    $(".testimonial-slider").owlCarousel({
        items: 1,  // Display one testimonial at a time
        margin: 30,  // Space between testimonials
        loop: true,  // Infinite loop
        autoplay: true,  // Auto slide
        autoplayTimeout: 5000,  // Time between slides (in ms)
        autoplayHoverPause: true,  // Pause autoplay when hovering
        nav: true,  // Display next/prev buttons
        dots: true,  // Show dots below the carousel
        responsive: {
            0: {
                items: 1  // 1 item on mobile
            },
            768: {
                items: 2  // 2 items on tablets
            },
            1024: {
                items: 3  // 3 items on desktop
            }
        }
    });
});



  


