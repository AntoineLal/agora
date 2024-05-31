document.getElementById('registerForm').addEventListener('submit', function(e) {
    // Empêcher l'envoi du formulaire par défaut
    e.preventDefault();

    // Récupérer les valeurs des champs
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var usertype = document.getElementById('usertype').value;
    var admincode = document.getElementById('admincode').value;

    // Vérifier si les champs ne sont pas vides
    if (username && email && password && usertype) {
        // Si le type de compte est admin, vérifier le code admin
        if (usertype === 'admin' && !admincode) {
            alert('Veuillez entrer le code admin.');
        } else {
            // Envoyer les données du formulaire au serveur
            this.submit();
        }
    } else {
        // Afficher un message d'erreur si des champs sont vides
        alert('Veuillez remplir tous les champs.');
    }
});

function showAdminCodeField() {
    var userType = document.getElementById('usertype').value;
    var adminCodeField = document.getElementById('adminCodeField');
    if (userType === 'admin') {
        adminCodeField.style.display = 'block';
    } else {
        adminCodeField.style.display = 'none';
    }
}

// Initialiser l'état du champ de code admin au chargement de la page
showAdminCodeField();
