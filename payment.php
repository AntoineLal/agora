<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Agora Francia</title>
    <link rel="stylesheet" href="style1.css"> 
</head>
<body>
    <header>
        <h1>Paiement</h1>
    </header>
    <div class="content">
        <h2>Informations de Livraison</h2>
        <form action="process_payment.php" method="post">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required><br><br>
            <label for="adresse1">Adresse Ligne 1:</label>
            <input type="text" id="adresse1" name="adresse1" required><br><br>
            <label for="adresse2">Adresse Ligne 2:</label>
            <input type="text" id="adresse2" name="adresse2"><br><br>
            <label for="ville">Ville:</label>
            <input type="text" id="ville" name="ville" required><br><br>
            <label for="code_postal">Code Postal:</label>
            <input type="text" id="code_postal" name="code_postal" required><br><br>
            <label for="pays">Pays:</label>
            <input type="text" id="pays" name="pays" required><br><br>
            <label for="telephone">Numéro de téléphone:</label>
            <input type="tel" id="telephone" name="telephone" required><br><br>

            <h2>Informations de Paiement</h2>
            <label for="type_carte">Type de carte:</label>
            <select id="type_carte" name="type_carte" required>
                <option value="visa">Visa</option>
                <option value="mastercard">MasterCard</option>
                <option value="american_express">American Express</option>
                <option value="paypal">PayPal</option>
            </select><br><br>
            <label for="numero_carte">Numéro de la carte:</label>
            <input type="text" id="numero_carte" name="numero_carte" required><br><br>
            <label for="nom_carte">Nom affiché sur la carte:</label>
            <input type="text" id="nom_carte" name="nom_carte" required><br><br>
            <label for="date_expiration">Date d'expiration:</label>
            <input type="text" id="date_expiration" name="date_expiration" placeholder="MM/YY" required><br><br>
            <label for="code_secu">Code de sécurité:</label>
            <input type="text" id="code_secu" name="code_secu" required><br><br>

            <input type="submit" value="Valider le Paiement">
        </form>
    </div>
    <div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="contact.php">Contact</a>
    </p>
</div>
</body>
</html>
