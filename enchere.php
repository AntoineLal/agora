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
$starting_price = $article['Price'];
$max_price = $starting_price * 10;

// Insérer les détails de l'article dans la table Enchere si ce n'est pas déjà fait
$sql = "SELECT * FROM Enchere WHERE ArticleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $sql_insert = "INSERT INTO Enchere (ArticleID, UserID, StartingPrice, Description, ImageURL, VideoURL, Quality, ItemType, BidAmount, BidTime)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iissssssd", $article_id, $_SESSION['user_id'], $starting_price, $article['Description'], $article['ImageURL'], $article['VideoURL'], $article['Quality'], $article['ItemType'], $starting_price);
    $stmt_insert->execute();
}

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

// Vérifier si l'enchère est terminée
$end_time = strtotime($bid_time) + 30 * 60; // 30 minutes après le début de l'enchère
$current_time = time();

if ($current_time > $end_time) {
    echo "<h2>L'enchère est terminée.</h2>";
    echo "<p>Gagnant : " . htmlspecialchars($last_bid_user_name) . "</p>";
    echo "<p>Prix final : " . number_format($current_bid, 2) . " €</p>";

    // Ajouter l'article au panier du gagnant
    $sql_get_panier = "SELECT PanierID FROM Panier WHERE UserID = ? AND Status = 'En cours'";
    $stmt_get_panier = $conn->prepare($sql_get_panier);
    $stmt_get_panier->bind_param("i", $last_bid_user_id);
    $stmt_get_panier->execute();
    $result_get_panier = $stmt_get_panier->get_result();

    if ($result_get_panier->num_rows == 0) {
        // Créer un nouveau panier si aucun n'existe
        $sql_create_panier = "INSERT INTO Panier (UserID, Status) VALUES (?, 'En cours')";
        $stmt_create_panier = $conn->prepare($sql_create_panier);
        $stmt_create_panier->bind_param("i", $last_bid_user_id);
        $stmt_create_panier->execute();
        $panier_id = $stmt_create_panier->insert_id;
    } else {
        // Utiliser le panier existant
        $panier = $result_get_panier->fetch_assoc();
        $panier_id = $panier['PanierID'];
    }

    // Ajouter l'article au panier
    $sql_add_to_panier = "INSERT INTO PanierArticles (PanierID, ArticleID, Quantity) VALUES (?, ?, 1)
                          ON DUPLICATE KEY UPDATE Quantity = Quantity + 1";
    $stmt_add_to_panier = $conn->prepare($sql_add_to_panier);
    $stmt_add_to_panier->bind_param("ii", $panier_id, $article_id);
    $stmt_add_to_panier->execute();

    exit();
}

// Traitement du formulaire d'enchère
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $increment = floatval($_POST['increment']);
    $new_bid_amount = $current_bid * (1 + $increment);
    $user_id = $_SESSION['user_id'];

    if ($new_bid_amount > $max_price) {
        // Si l'offre dépasse le prix maximum, attribuer automatiquement l'article à cet acheteur au prix maximum
        $new_bid_amount = $max_price;
        $user_id = $_SESSION['user_id'];

        $sql = "UPDATE Enchere SET BidAmount = ?, UserID = ?, BidTime = NOW(), UpdatedAt = NOW() WHERE ArticleID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $new_bid_amount, $user_id, $article_id);
        if ($stmt->execute()) {
            echo "Vous avez remporté l'enchère avec succès au prix maximum de " . number_format($new_bid_amount, 2) . " €.";
            $current_bid = $new_bid_amount;
            $last_bid_user_id = $user_id;

            // Récupérer le nom du dernier enchérisseur
            $sql_user = "SELECT UserName FROM Users WHERE UserID = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user = $result_user->fetch_assoc();
            $last_bid_user_name = $user['UserName'];

            // Ajouter l'article au panier du gagnant
            $sql_get_panier = "SELECT PanierID FROM Panier WHERE UserID = ? AND Status = 'En cours'";
            $stmt_get_panier = $conn->prepare($sql_get_panier);
            $stmt_get_panier->bind_param("i", $last_bid_user_id);
            $stmt_get_panier->execute();
            $result_get_panier = $stmt_get_panier->get_result();

            if ($result_get_panier->num_rows == 0) {
                // Créer un nouveau panier si aucun n'existe
                $sql_create_panier = "INSERT INTO Panier (UserID, Status) VALUES (?, 'En cours')";
                $stmt_create_panier = $conn->prepare($sql_create_panier);
                $stmt_create_panier->bind_param("i", $last_bid_user_id);
                $stmt_create_panier->execute();
                $panier_id = $stmt_create_panier->insert_id;
            } else {
                // Utiliser le panier existant
                $panier = $result_get_panier->fetch_assoc();
                $panier_id = $panier['PanierID'];
            }

            // Ajouter l'article au panier
            $sql_add_to_panier = "INSERT INTO PanierArticles (PanierID, ArticleID, Quantity) VALUES (?, ?, 1)
                                  ON DUPLICATE KEY UPDATE Quantity = Quantity + 1";
            $stmt_add_to_panier = $conn->prepare($sql_add_to_panier);
            $stmt_add_to_panier->bind_param("ii", $panier_id, $article_id);
            $stmt_add_to_panier->execute();

            
        } 
        else {
            echo "Erreur : " . $stmt->error;
        }
    } elseif ($new_bid_amount > $current_bid) {
        $sql = "UPDATE Enchere SET BidAmount = ?, UserID = ?, BidTime = NOW(), UpdatedAt = NOW() WHERE ArticleID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $new_bid_amount, $user_id, $article_id);
        if ($stmt->execute()) {
            echo "Enchère placée avec succès.";
            $current_bid = $new_bid_amount;
            $last_bid_user_id = $user_id;

            // Récupérer le nom du dernier enchérisseur
            $sql_user = "SELECT UserName FROM Users WHERE UserID = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user = $result_user->fetch_assoc();
            $last_bid_user_name = $user['UserName'];
        } else {
            echo "Erreur : " . $stmt->error;
        }
    } else {
        echo "L'enchère doit être supérieure à l'enchère actuelle.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchère - <?php echo htmlspecialchars($article['ArticleName']); ?></title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Enchère</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>

    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'buyer'): ?>
        <a href="panier.php">Panier</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'seller'): ?>
        <a href="offres.php">Mes Offres</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
        <a href="gestion.php">Gestion</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="moncompte.php" style="display: inline-block; margin: 0; padding: 0;">
            <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;">
        </a>
        <a href="logout.php">Déconnexion</a>
    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
<div class="content">
    <h2><?php echo htmlspecialchars($article['ArticleName']); ?></h2>
    <p>Description : <?php echo htmlspecialchars($article['Description']); ?></p>
    <p>Prix de départ : <?php echo number_format($starting_price, 2); ?> €</p>
    <p>Prix actuel : <?php echo number_format($current_bid, 2); ?> €</p>
    <p>Dernière enchère par : <?php echo htmlspecialchars($last_bid_user_name); ?></p>

    <div class="encherir-form">
        <form action="enchere.php?article_id=<?php echo $article_id; ?>" method="post">
            <label for="increment">Choisissez le pourcentage d'augmentation :</label>
            <select name="increment" id="increment">
                <option value="0.1">+10%</option>
                <option value="0.2">+20%</option>
                <option value="0.3">+30%</option>
                <option value="0.4">+40%</option>
                <option value="0.5">+50%</option>
                <option value="0.6">+60%</option>
                <option value="0.7">+70%</option>
                <option value="0.8">+80%</option>
                <option value="0.9">+90%</option>
                <option value="1.0">+100%</option>
            </select>
            <button type="submit">Enchérir</button>
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
