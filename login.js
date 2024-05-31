document.getElementById('loginForm').addEventListener('submit', function(e) {
    // Empêcher l'envoi du formulaire par défaut
    e.preventDefault();

    // Récupérer les valeurs des champs
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Vérifier si les champs ne sont pas vides 
    if (email && password) {
        // Envoyer les données du formulaire au serveur
        this.submit();
    } else {
        // Afficher un message d'erreur si des champs sont vides
        alert('Veuillez remplir tous les champs.');
    }
});
