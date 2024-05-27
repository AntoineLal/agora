document.getElementById('createAccountLink').addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('.container h2').textContent = 'Créer un compte';
    document.getElementById('loginForm').innerHTML = `
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" id="submitForm">Créer un compte</button>
    `;
    document.querySelector('.toggle-link').innerHTML = `
        <a href="#" id="loginLink">Déjà un compte ? Se connecter</a>
    `;
    document.getElementById('loginLink').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.container h2').textContent = 'Connexion';
        document.getElementById('loginForm').innerHTML = `
            <label for="login_email">Email :</label>
            <input type="email" id="login_email" name="login_email" required>
            <label for="login_password">Mot de passe :</label>
            <input type="password" id="login_password" name="login_password" required>
            <button type="submit" id="submitLoginForm">Se connecter</button>
        `;
        document.querySelector('.toggle-link').innerHTML = `
            <a href="#" id="createAccountLink">Créer un compte</a>
        `;
    });
});

document.addEventListener('submit', function(e) {
    if (e.target && e.target.id == 'submitLoginForm') {
        e.preventDefault();
        var formData = new FormData(document.getElementById('loginForm'));
        fetch('traitement.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                if (data === "Connecté avec succès") {
                    // Rediriger l'utilisateur vers une nouvelle page ou effectuer d'autres actions
                    console.log(data);
                } else {
                    // Afficher le message d'erreur à l'utilisateur
                    alert(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
