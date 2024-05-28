document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById('loginForm'));
        fetch('traitement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === "Connecté avec succès") {
                alert("Connexion réussie");
                window.location.href = 'accueil.php';
            } else {
                alert(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
