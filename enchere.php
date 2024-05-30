<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un acheteur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'buyer') {
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

// Insérer les détails de l'article dans la table Enchere si ce n'est pas déjà fait
$sql = "SELECT * FROM Enchere WHERE ArticleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $sql_insert = "INSERT INTO Enchere (ArticleID, UserID, StartingPrice, Description, ImageURL, VideoURL, Quality, ItemType, BidAmount)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iissssssd", $article_id, $_SESSION['user_id'], $article['Price'], $article['Description'], $article['ImageURL'], $article['VideoURL'], $article['Quality'], $article['ItemType'], $article['Price']);
    $stmt_insert->execute();
}

// Récupérer les enchères pour l'article
$sql = "SELECT * FROM Enchere WHERE ArticleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$enchere = $result->fetch_assoc();

$current_bid = $enchere['BidAmount'] ? $enchere['BidAmount'] : $enchere['StartingPrice'];
$last_bid_user_id = $enchere['UserID'];

// Traitement du formulaire d'enchère
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $increment = floatval($_POST['increment']);
    $new_bid_amount = $current_bid * (1 + $increment);
    $user_id = $_SESSION['user_id'];

    if ($new_bid_amount > $current_bid) {
        $sql = "UPDATE Enchere SET BidAmount = ?, UserID = ?, UpdatedAt = NOW() WHERE ArticleID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $new_bid_amount, $user_id, $article_id);
        if ($stmt->execute()) {
            echo "Enchère placée avec succès.";
            $current_bid = $new_bid_amount;
            $last_bid_user_id = $user_id;
        } else {
            echo "Erreur : " . $stmt->error;
        }
    } else {
        echo "L'enchère doit être supérieure à l'enchère actuelle.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'article - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        .article-image {
            width: 400px;
            height: 400px;
        }
        .details-container {
            display: flex;
            justify-content: space-between;
        }
        .details {
            width: 60%;
        }
        .additional-info {
            width: 35%;
        }
        .description {
            max-width: 400px;
            word-wrap: break-word; 
        }
        .bid-buttons button {
            margin: 5px;
        }
    </style>
</head>
<body>
<header>
    <h1>Détails de l'article - Agora Francia</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="panier.php">Panier</a>
    <a href="compte.html">Votre Compte</a>
</nav>
<div class="content">
    <h2><?php echo htmlspecialchars($article['ArticleName']); ?></h2>
    <div class="details-container">
        <div class="details">
            <img class="article-image" src="<?php echo htmlspecialchars($article['ImageURL']); ?>" alt="Image de l'article">
            <p class="description"><?php echo nl2br(htmlspecialchars($article['Description'])); ?></p>
            <p>Prix de départ : <?php echo number_format($enchere['StartingPrice'], 2); ?> €</p>
        </div>
        <div class="additional-info">
            <p>Enchère actuelle : <?php echo number_format($current_bid, 2); ?> €</p>
            <p>Qualité : <?php echo htmlspecialchars($article['Quality']); ?></p>
            <p>Type d'article : <?php echo htmlspecialchars($article['ItemType']); ?></p>
            <p>Dernier enchérisseur : <?php echo htmlspecialchars($last_bid_user_id); ?></p>
            <form action="enchere.php?article_id=<?php echo $article_id; ?>" method="post">
                <div class="bid-buttons">
                    <?php
                    for ($i = 10; $i <= 100; $i += 10) {
                        $increment = $i / 100;
                        echo '<button type="submit" name="increment" value="' . $increment . '">+' . $i . '%</button>';
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<footer id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="#mentions-legales">Mentions légales</a> |
        <a href="#politique-confidentialite">Politique de confidentialité</a> |
        <a href="#contact">Contact</a>
    </p>
</footer>
</body>
</html>
