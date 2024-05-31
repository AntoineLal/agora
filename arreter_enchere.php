<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un vendeur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'seller') {
    header("Location: login.html");
    exit();
}

include 'config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer l'identifiant de l'article depuis l'URL
$article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;

// Requête SQL pour récupérer les détails de l'article
$sql = "SELECT * FROM Articles WHERE ArticleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier s'il y a des résultats
if ($result->num_rows == 0) {
    echo "Article non trouvé.";
    exit();
}

$article = $result->fetch_assoc();
$starting_price = $article['Price'];
$max_price = $starting_price * 10;

// Récupérer les enchères pour l'article
$sql = "SELECT e.*, u.UserName FROM Enchere e JOIN Users u ON e.UserID = u.UserID WHERE e.ArticleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$enchere = $result->fetch_assoc();

$current_bid = $enchere['BidAmount'];
$last_bid_user_id = $enchere['UserID'];
$last_bid_user_name = $enchere['UserName'];
$bid_time = $enchere['BidTime'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arrêter Enchère - <?php echo htmlspecialchars($article['ArticleName']); ?></title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Arrêter Enchère</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="panier.php">Panier</a>
    <a href="logout.php">Déconnexion</a>
</nav>
<div class="content">
    <h2><?php echo htmlspecialchars($article['ArticleName']); ?></h2>
    <p>Description : <?php echo htmlspecialchars($article['Description']); ?></p>
    <p>Prix de départ : <?php echo number_format($starting_price, 2); ?> €</p>
    <p>Prix actuel : <?php echo number_format($current_bid, 2); ?> €</p>
    <p>Dernière enchère par : <?php echo htmlspecialchars($last_bid_user_name); ?></p>

    <div class="encherir-form">
        <form action="arreter_enchere_traitement.php" method="post">
            <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
            <button type="submit">Arrêter l'enchère</button>
        </form>
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
</body>
</html>
