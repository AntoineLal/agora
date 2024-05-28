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
    <a href="accueil.html">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="#panier">Panier</a>
    <a href="compte.html">Votre Compte</a>
</nav>
<div class="content">
    <h2>Détails de l'article</h2>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agora";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;
    $sql = "SELECT ArticleName, Description, Price, ImageURL, Stock, TypeVente FROM Articles WHERE ArticleID = $article_id";
    $result = $conn->query($sql);

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
        echo '<form action="ajouter_au_panier.php" method="POST">';
        echo '<input type="hidden" name="article_id" value="' . $article_id . '">';
        echo '<label for="quantity">Quantité :</label>';
        echo '<input type="number" id="quantity" name="quantity" value="1" min="1" max="' . $row["Stock"] . '">';
        echo '<input type="submit" value="Ajouter au panier">';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    } else {
        echo "Article non trouvé.";
    }

    $conn->close();
    ?>
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
