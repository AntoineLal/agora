<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Parcourir - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        .thumbnail-image {
            width: 250px; 
            height: 250px;
        }
        .description {
            max-width: 200px;
            word-wrap: break-word; 
        }
        .article-image {
            width: 400px;
            height: 400px;
        }
        .article-thumbnail {
            display: inline-block;
            margin: 10px;
            text-align: center;
            width: 250px;
            position: relative; /* Ajout d'une position relative pour permettre le positionnement du pseudo-élément */
        }
        .thumbnail-image {
            width: 100%; 
            height: auto;
            max-width: 250px;
            max-height: 250px;
        }
        .article-info {
            margin-top: 10px;
        }
        /* Appliquer le filtre de désaturation sur les images lorsque le stock est épuisé */
        .out-of-stock .thumbnail-image {
            filter: grayscale(100%);
        }
        /* Ajouter des hachures diagonales lorsque le stock est épuisé */
        .out-of-stock::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, #ccc 5px, #ccc 10px);
            opacity: 0.7;
        }
    </style>
</head>
<body>
<header>
    <h1>Tout Parcourir - Agora Francia</h1>
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
    <h2>Tous les articles</h2>
    <div class="article-thumbnails">
        <?php
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "agora";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Requête SQL pour récupérer les informations sur les articles avec le type spécifié
        $sql = "SELECT ArticleID, ArticleName, Description, Price, ImageURL, Stock FROM Articles WHERE TypeVente IN ('Immediat', 'Negociation', 'Enchere')";
        $result = $conn->query($sql);

        // Si des articles sont trouvés, afficher leurs vignettes
        if ($result->num_rows > 0) {
            // Boucle sur chaque ligne de résultat
            while($row = $result->fetch_assoc()) {
                // Vérifier si le stock est épuisé
                $stock = $row["Stock"];
                $out_of_stock_class = ($stock == 0) ? 'out-of-stock' : '';

                // Afficher la vignette de l'article avec un lien vers la page de détails
                echo '<div class="article-thumbnail ' . $out_of_stock_class . '">';
                echo '<a href="article_details.php?article_id=' . $row["ArticleID"] . '">';
                echo '<img class="thumbnail-image" src="' . $row["ImageURL"] . '" alt="' . $row["ArticleName"] . '">';
                echo '</a>';
                echo '<h3>' . $row["ArticleName"] . '</h3>';
                echo '<p>Prix : ' . $row["Price"] . '€</p>';
                echo '</div>';
            }
        } else {
            echo "Aucun article trouvé.";
        }

        // Fermer la connexion à la base de données
        $conn->close();
        ?>
    </div>
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
