document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carousel');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    // Défilement automatique du carrousel
    let scrollInterval;
    function startScroll() {
        scrollInterval = setInterval(function() {
            carousel.scrollBy({
                left: 1, // Défilement d'un pixel à la fois
                behavior: 'smooth'
            });
        }, 10); // Vitesse de défilement
    }

    // Arrêt du défilement automatique
    function stopScroll() {
        clearInterval(scrollInterval);
    }

    // Démarrer le défilement automatique au chargement de la page
    startScroll();

    // Arrêter le défilement automatique au survol du carrousel
    carousel.addEventListener('mouseover', stopScroll);
    carousel.addEventListener('mouseout', startScroll);

    // Défilement manuel avec les boutons
    prevButton.addEventListener('click', function() {
        carousel.scrollBy({
            left: -350, // Ajuster la valeur selon votre mise en page
            behavior: 'smooth'
        });
    });

    nextButton.addEventListener('click', function() {
        carousel.scrollBy({
            left: 350, // Ajuster la valeur selon votre mise en page
            behavior: 'smooth'
        });
    });
});
