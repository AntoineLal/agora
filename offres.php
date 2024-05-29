<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un vendeur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'seller') {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Récupérer les articles de l'utilisateur connecté
$sql = "SELECT * FROM Articles WHERE UserID='$user_id'";
$result = $conn->query($sql);
$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Offres - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Mes Offres</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="offres.php">Mes Offres</a>
    <a href="logout.php">Se déconnecter</a>
</nav>
<div class="content">
    <h2>Articles en vente</h2>
    <div class="article-list">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-item">
                    <h3><?php echo htmlspecialchars($article['ArticleName']); ?></h3>
                    <img src="<?php echo htmlspecialchars($article['ImageURL']); ?>" alt="<?php echo htmlspecialchars($article['ArticleName']); ?>" style="max-width: 150px; max-height: 150px;">
                    <p>Description : <?php echo htmlspecialchars($article['Description']); ?></p>
                    <p>Prix : <?php echo htmlspecialchars($article['Price']); ?> €</p>
                    <p>Qualité : <?php echo htmlspecialchars($article['Quality']); ?></p>
                    <p>Stock : <?php echo htmlspecialchars($article['Stock']); ?></p>
                    <p>Type de vente : <?php echo htmlspecialchars($article['TypeVente']); ?></p>
                    <p>Type d'article : <?php echo htmlspecialchars($article['ItemType']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez aucun article en vente actuellement.</p>
        <?php endif; ?>
    </div>
    <div class="create-offer">
        <a href="ajouter_article.php" class="btn">Créer une nouvelle offre</a>
    </div>
</div>
<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="#contact">Contact</a>
    </p>
</div>
</body>
</html>
