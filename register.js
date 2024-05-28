document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById('registerForm'));
        fetch('traitement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === "Compte créé avec succès") {
                alert("Compte créé avec succès");
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
