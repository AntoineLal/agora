<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Bienvenue sur Agora Francia</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="#panier">Panier</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Se déconnecter</a>
    <?php else: ?>
        <a href="login.html">Votre Compte</a>
    <?php endif; ?>
</nav>
<div class="content">
    <h2>Page d'accueil</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> !</p>
        <p>Email : <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    <?php else: ?>
        <p>Bienvenue sur Agora Francia, votre plateforme de choix pour explorer et acheter des produits variés.</p>
        <p>Utilisez la navigation ci-dessus pour accéder aux différentes sections de notre site.</p>
    <?php endif; ?>
</div>
<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="mailto:agora78@gmail.com">Contact</a>
    </p>
</div>
</body>
</html>
