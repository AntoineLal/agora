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
        <button type="submit">Créer un compte</button>
    `;
    document.querySelector('.toggle-link').innerHTML = `
        <a href="#" id="loginLink">Déjà un compte ? Se connecter</a>
    `;
    document.getElementById('loginLink').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.container h2').textContent = 'Connexion';
        document.getElementById('loginForm').innerHTML = `
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
        `;
        document.querySelector('.toggle-link').innerHTML = `
            <a href="#" id="createAccountLink">Créer un compte</a>
        `;
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
                <button type="submit">Créer un compte</button>
            `;
            document.querySelector('.toggle-link').innerHTML = `
                <a href="#" id="loginLink">Déjà un compte ? Se connecter</a>
            `;
        });
    });
});
