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
    </style>
</head>
<body>
<header>
    <h1>Tout Parcourir - Agora Francia</h1>
</header>
<nav>
    <a href="accueil.html">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <!-- Ajoutez d'autres liens de navigation si nécessaire -->
    <a href="#notifications">Notifications</a>
    <a href="#panier">Panier</a>
    <a href="compte.html">Votre Compte</a>
</nav>
<div class="content">
    <h2>Tous les articles</h2>
    <div class="article-thumbnails">
        <!-- Utiliser du code PHP pour afficher les vignettes des articles -->
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

        // Requête SQL pour récupérer les informations sur les articles
        $sql = "SELECT ArticleName, Description, Price, ImageURL FROM Articles";
        $result = $conn->query($sql);

        // Si des articles sont trouvés, afficher leurs vignettes
        if ($result->num_rows > 0) {
            // Boucle sur chaque ligne de résultat
            while($row = $result->fetch_assoc()) {
                // Afficher la vignette de l'article
                echo '<div class="article-thumbnail">';
                echo '<img class="thumbnail-image" src="' . $row["ImageURL"] . '" alt="' . $row["ArticleName"] . '">';
                echo '<h3>' . $row["ArticleName"] . '</h3>';
                echo '<p>' . $row["Description"] . '</p>';
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
        <a href="#contact">Contact</a>
    </p>
</div>
</body>
</html>
