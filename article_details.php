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
<div class="content">
    <h2>Détails de l'article</h2>
    <?php
    // Inclure le fichier de configuration pour la connexion à la base de données
    include 'config.php'; 

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Récupérer l'identifiant de l'article depuis l'URL
    $article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;

    // Requête SQL pour récupérer les détails de l'article
    $sql = "SELECT ArticleName, Description, Price, ImageURL, Stock, TypeVente FROM Articles WHERE ArticleID = $article_id";
    $result = $conn->query($sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<div class="details-container">';
        echo '<div class="details">';
        echo '<img class="article-image" src="' . $row["ImageURL"] . '" alt="' . $row["ArticleName"] . '">';
        echo '<h3>' . $row["ArticleName"] . '</h3>';
        echo '<p class="description">' . $row["Description"] . '</p>';
        echo '<p>Prix : ' . $row["Price"] . '€</p>';
        echo '</div>';
        echo '<div class="additional-info">';
        echo '<p>Stock : ' . $row["Stock"] . '</p>';
        echo '<p>Type de vente : ' . $row["TypeVente"] . '</p>';
        
        // Affichage du bouton en fonction du type de vente
        switch ($row["TypeVente"]) {
            case "Immediat":
                echo '<form action="ajouter_au_panier.php" method="POST">';
                echo '<input type="hidden" name="article_id" value="' . $article_id . '">';
                echo '<label for="quantity">Quantité :</label>';
                echo '<input type="number" id="quantity" name="quantity" value="1" min="1" max="' . $row["Stock"] . '">';
                echo '<input type="submit" value="Ajouter au panier">';
                echo '</form>';
                break;
            case "Enchere":
                echo '<a href="enchere.php?article_id=' . $article_id . '">Participer à l\'enchère</a>';
                break;
            case "Negociation":
                echo '<a href="negociation.php?article_id=' . $article_id . '">Négocier le prix</a>';
                break;
            default:
                echo "Type de vente non reconnu.";
        }
        
        echo '</div>';
        echo '</div>';
    } else {
        echo "Article non trouvé.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
    ?>
</div>
<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialie.html">Politique de confidentialité</a> |
        <a href="mailto:agora78@gmail.com">Contact</a>
    </p>
</div>
</body>
</html>
