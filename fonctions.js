document.getElementById('registerForm').addEventListener('submit', function(e) {
    // Empêcher l'envoi du formulaire par défaut
    e.preventDefault();

    // Récupérer les valeurs des champs
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var usertype = document.getElementById('usertype').value;

    // Vérifier si les champs ne sont pas vides
    if (username && email && password && usertype) {
        // Envoyer les données du formulaire au serveur
        this.submit();
    } else {
        // Afficher un message d'erreur si des champs sont vides
        alert('Veuillez remplir tous les champs.');
    }
});
