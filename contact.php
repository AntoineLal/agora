<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

// Récupérer les articles de la base de données
$query = "SELECT Articles.*, Users.UserName FROM Articles JOIN Users ON Articles.UserID = Users.UserID";
$result = $conn->query($query);
$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Agora Francia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .contact-info, .contact-form {
            margin: 20px 0;
        }
        .contact-info p {
            font-size: 1.2em;
        }
        iframe {
            width: 100%;
            height: 400px;
            border: none;
        }
        .contact-form form {
            display: flex;
            flex-direction: column;
        }
        .contact-form label, .contact-form input, .contact-form textarea, .contact-form button {
            margin: 10px 0;
            padding: 10px;
            font-size: 1em;
        }
        .contact-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .contact-form button:hover {
            background-color: #45a049;
        }
    </style>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Contactez nous</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>

    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'buyer'): ?>
        <a href="#panier.php">Panier</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'seller'): ?>
        <a href="offres.php">Mes Offres</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
        <a href="gestion.php">Gestion</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="moncompte.php" style="display: inline-block; margin: 0; padding: 0;">
    <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;"></a>
        <a href="logout.php">déconnexion</a>

    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
    </header>

    <div class="container">
        <div class="contact-info">
            <h2>Informations de contact</h2>
            <p>Vous avez des questions ou des commentaires ? N'hésitez pas à nous contacter :</p>
            <p>Email : <a href="mailto:agora.francia@contact.com">agora.francia@contact.com</a></p>
            <p>Téléphone : +33 6 30 07 50 36</p>
            <p>Adresse : 1 Rue Jules Lefebvre, 75009 Paris, France</p>
        </div>

        <div class="map">
            <h2>Notre emplacement</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9900369370664!2d2.326566315675124!3d48.87992897928871!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e413128f257%3A0xa8b0e4b1cf16cf19!2s1%20Rue%20Jules%20Lefebvre%2C%2075009%20Paris%2C%20France!5e0!3m2!1sen!2sus!4v1599072547345!5m2!1sen!2sus" 
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>

    </div>

    <div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialie.html">Politique de confidentialité</a> |
        <a href="contact.php">Contact</a>
    </p>
    </div>
</body>
</html>
