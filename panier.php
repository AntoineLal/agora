<?php
// Démarrer la session
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: login.html");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérer les articles du panier de l'utilisateur à partir de la base de données
$sql = "SELECT Articles.*, PanierArticles.Quantity, Enchere.BidAmount FROM Articles 
        INNER JOIN PanierArticles ON Articles.ArticleID = PanierArticles.ArticleID 
        INNER JOIN Panier ON Panier.PanierID = PanierArticles.PanierID
        LEFT JOIN Enchere ON Articles.ArticleID = Enchere.ArticleID
        WHERE Panier.UserID = $user_id";

$result = $conn->query($sql);

// Tableau pour stocker les articles du panier
$panier_items = [];
$total = 0; // Initialiser le total à zéro

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $panier_items[] = $row;
        // Si BidAmount est disponible, utilisez-le pour calculer le total, sinon utilisez le prix normal de l'article
        $total += isset($row['BidAmount']) ? $row['BidAmount'] * $row['Quantity'] : $row['Price'] * $row['Quantity'];
    }
}

// Récupérer la remise associée au panier de l'utilisateur
$remise_query = "SELECT MontantRemise FROM Remises WHERE PanierID = (SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours')";
$remise_result = $conn->query($remise_query);
$remise_amount = 0; // Initialiser la remise à zéro
if ($remise_result->num_rows > 0) {
    $remise_row = $remise_result->fetch_assoc();
    $remise_amount = $remise_row['MontantRemise'];
    // Soustraire le montant de la remise du total
    $total -= $remise_amount;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Agora Francia</title>
    <link rel="stylesheet" href="style1.css"> 
</head>
<body>
    <header>
        <h1>Panier</h1>
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
                <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;">
            </a>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.html">Se connecter</a>
        <?php endif; ?>
    </nav>
    <div class="content">
        <h2>Votre Panier</h2>
        <div class="panier-items">
        <?php foreach ($panier_items as $item): ?>
                    <div class="item">
                        <h3><a href="article_details.php?article_id=<?php echo $item['ArticleID']; ?>"><?php echo htmlspecialchars($item['ArticleName']); ?></a></h3>
                <img src="<?php echo htmlspecialchars($item['ImageURL']); ?>" alt="<?php echo htmlspecialchars($item['ArticleName']); ?>" style="max-width: 150px; max-height: 150px;">
                <p><?php echo htmlspecialchars($item['Description']); ?></p>
                        <p>Quantité : <?php echo $item['Quantity']; ?></p>
                        <p>Prix unitaire : <?php echo isset($item['BidAmount']) ? $item['BidAmount'] : $item['Price']; ?> €</p>
                   <?php if ($item['TypeVente'] === 'Immediat'): ?>
                        <form action="retirerdupanier.php" method="post">
                            <input type="hidden" name="article_id" value="<?php echo $item['ArticleID']; ?>">
                            <input type="submit" value="Supprimer">
                       </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
        <div class="total">
            <h3>Total :</h3>
            <p>Total avant remise : <?php echo $total + $remise_amount; ?> €</p>
            <p>Remise : <?php echo $remise_amount; ?> €</p>
            <p>Total après remise : <?php echo $total; ?> €</p>
        </div>
        <div class="checkout">
            <form action="payment.php" method="post">
                <input type="submit" value="Passer la commande">
            </form>
        </div>
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
