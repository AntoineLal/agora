document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carousel');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    let scrollAmount = 0;

    nextButton.addEventListener('click', function() {
        carousel.scrollBy({
            left: 300,
            behavior: 'smooth'
        });
        scrollAmount += 300;
        if (scrollAmount >= carousel.scrollWidth - carousel.clientWidth) {
            scrollAmount = 0;
            carousel.scrollTo({
                left: 0,
                behavior: 'smooth'
            });
        }
    });

    prevButton.addEventListener('click', function() {
        carousel.scrollBy({
            left: -300,
            behavior: 'smooth'
        });
        scrollAmount -= 300;
        if (scrollAmount < 0) {
            scrollAmount = carousel.scrollWidth - carousel.clientWidth;
            carousel.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    });
});
