<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications de Négociation - Agora Francia</title>
    <link rel="stylesheet" href="style1.css"> 
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<header>
    <h1>Notifications de Négociation - Agora Francia</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="notifications.php">Notifications</a>

    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'buyer'): ?>
        <a href="panier.php">Panier</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'seller'): ?>
        <a href="offres.php">Mes Offres</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
        <a href="gestion.php">Gestion</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="moncompte.php" style="display: inline-block; margin: 0; padding: 0;">
            <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;"></a>
        <a href="logout.php">Déconnexion</a>
    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
<div class="content">
    <?php
    // Récupérer les notifications de négociation pour l'utilisateur
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['usertype'];

    // Modifier la requête pour inclure à la fois les négociations où l'utilisateur est l'acheteur ou le vendeur
    $sql = "SELECT N.NotificationID, N.TypeVente, N.TypeAchat, N.Rarete, N.Quantity, N.CreatedAt, 
            N.Quantity <> IFNULL(NEWARTICLES.ArticleCount, 0) AS QuantityChanged
            FROM notifications N
            LEFT JOIN (
                SELECT TypeVente, Quality, ItemType, COUNT(*) AS ArticleCount
                FROM Articles
                GROUP BY TypeVente, Quality, ItemType
            ) AS NEWARTICLES ON N.TypeVente = NEWARTICLES.TypeVente AND N.TypeAchat = NEWARTICLES.Quality AND N.Rarete = NEWARTICLES.ItemType
            WHERE N.UserID = $user_id";

    $result = $conn->query($sql);

    if (!$result) {
        echo "Erreur MySQL: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        echo "<h2>Notifications de Négociation</h2>";
        echo "<table>";
        echo "<tr><th>Type de Vente</th><th>Type d'Achat</th><th>Rareté</th><th>Quantité</th><th>Date de Création</th><th>Changement de Quantité</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["TypeVente"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["TypeAchat"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Rarete"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Quantity"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["CreatedAt"]) . "</td>";
            echo "<td>" . ($row["QuantityChanged"] ? "Oui" : "Non") . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucune notification de négociation.";
    }
    ?>
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
