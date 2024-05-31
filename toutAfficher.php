<?php
session_start();
include 'config.php';
?>
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
        .filters {
            margin-bottom: 20px;
        }
        .filter-btn {
            margin-left: 10px;
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
        <a href="logout.php">déconnexion</a>

    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
<div class="content">
    <h2>Tous les articles</h2>
    <div class="filters">
        <label for="TypeVente">Type de vente:</label>
        <select name="TypeVente" id="TypeVente">
            <option value="Tous">Tous</option>
            <option value="Immediat">Immédiat</option>
            <option value="Negociation">Négociation</option>
            <option value="Enchere">Enchère</option>
        </select>
        <label for="TypeAchat">Type d'achat:</label>
        <select name="TypeAchat" id="TypeAchat">
            <option value="Tous">Tous</option>
            <option value="Neuf">Neuf</option>
            <option value="Occasion">Occasion</option>
        </select>
        <label for="Rarete">Rareté:</label>
        <select name="Rarete" id="Rarete">
            <option value="Tous">Tous</option>
            <option value="Articles rares">Articles rares</option>
            <option value="Articles reguliers">Articles reguliers</option>
            <option value="Articles hautes de gamme">Articles hautes de gamme</option>
        </select>
        <button class="filter-btn" onclick="applyFilters()">Appliquer les filtres</button>
    </div>
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

        // Initialiser les conditions du filtre
        $filter_conditions = [];

        // Vérifier si le type de vente est défini dans l'URL
        if (isset($_GET['TypeVente'])) {
            $TypeVente = $_GET['TypeVente'];

            // Ajouter le type de vente à la condition du filtre
            if ($TypeVente != 'Tous') {
                $filter_conditions[] = "TypeVente = '$TypeVente'";
            }
        }

        // Vérifier si le type d'achat est défini dans l'URL
        if (isset($_GET['TypeAchat'])) {
            $TypeAchat = $_GET['TypeAchat'];

            // Ajouter le type d'achat à la condition du filtre
            if ($TypeAchat != 'Tous') {
                $filter_conditions[] = "Quality = '$TypeAchat'";
            }
        }

        // Vérifier si la rareté est définie dans l'URL
        if (isset($_GET['Rarete'])) {
            $Rarete = $_GET['Rarete'];

            // Ajouter la rareté à la condition du filtre
            if ($Rarete != 'Tous') {
                $filter_conditions[] = "ItemType = '$Rarete'";
            }
        }

        // Requête SQL pour récupérer les informations sur les articles avec les conditions spécifiées
        $sql = "SELECT ArticleID, ArticleName, Description, Price, ImageURL, Stock FROM Articles";

        // Ajouter les conditions au filtre
        if (!empty($filter_conditions)) {
            $sql .= " WHERE " . implode(" AND ", $filter_conditions);
        }

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
<footer id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialie.html">Politique de confidentialité</a> |
        <a href="#contact">Contact</a>
    </p>
</footer>
<script>
    function applyFilters() {
        var TypeVente = document.getElementById("TypeVente").value;
        var TypeAchat = document.getElementById("TypeAchat").value;
        var Rarete = document.getElementById("Rarete").value;
        window.location.href = "toutAfficher.php?TypeVente=" + TypeVente + "&TypeAchat=" + TypeAchat + "&Rarete=" + Rarete;
    }
</script>
</body>
</html>
