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
    <title>Accueil - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        img {
    max-width: 150px;
    max-height: 150px;
}

    </style>
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

    <h2>Articles en vente</h2>
    <div class="carousel" id="carousel">
        <?php foreach ($articles as $article): ?>
            <div class="carousel-item">
                <h3><?php echo htmlspecialchars($article['ArticleName']); ?></h3>
                <img src="<?php echo htmlspecialchars($article['ImageURL']); ?>" alt="<?php echo htmlspecialchars($article['ArticleName']); ?>">
                
                <p>Prix : <?php echo htmlspecialchars($article['Price']); ?> €</p>
                <p>Vendu par : <?php echo htmlspecialchars($article['UserName']); ?></p>
                <p>Qualité : <?php echo htmlspecialchars($article['Quality']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
        <button id="prev">Précédent</button>
        <button id="next">Suivant</button>
    </div>
</div>
<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="mailto:agora78@gmail.com">Contact</a>
    </p>
</div>
<script src="carousel.js"></script>
</body>
</html>
