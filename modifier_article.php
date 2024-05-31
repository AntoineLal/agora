<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un vendeur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'seller') {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'ID de l'article est passé en paramètre dans l'URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
} else {
    echo "ID de l'article non spécifié.";
    exit();
}

// Vérifier si l'article appartient à l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Articles WHERE ArticleID='$article_id' AND UserID='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // L'utilisateur est autorisé à modifier l'article
    $article = $result->fetch_assoc();
} else {
    echo "Vous n'êtes pas autorisé à modifier cet article.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'article - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Modifier l'article</h1>
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
    <form action="traitement_modifier_article.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="article_id" value="<?php echo $article['ArticleID']; ?>">
        <label for="article_name">Nom de l'article:</label><br>
        <input type="text" id="article_name" name="article_name" value="<?php echo $article['ArticleName']; ?>" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required><?php echo $article['Description']; ?></textarea><br><br>
        
        <label for="type_vente">Type de vente:</label><br>
        <select id="type_vente" name="type_vente" required>
            <option value="Immediat" <?php if ($article['TypeVente'] == 'Immediat') echo 'selected'; ?>>Vente immédiate</option>
            <option value="Enchere" <?php if ($article['TypeVente'] == 'Enchere') echo 'selected'; ?>>Enchère</option>
            <option value="Negociation" <?php if ($article['TypeVente'] == 'Negociation') echo 'selected'; ?>>Négociation</option>
        </select><br><br>
        
        <label for="price">Prix:</label><br>
        <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $article['Price']; ?>" required><br><br>
        
        <label for="image_upload">Sélectionner une image :</label><br>
        <input type="file" id="image_upload" name="image_upload" accept="image/*"><br><br>
        
        <label for="video_url">URL de la vidéo:</label><br>
        <input type="text" id="video_url" name="video_url" value="<?php echo $article['VideoURL']; ?>"><br><br>
        
        <label for="quality">Qualité:</label><br>
        <select id="quality" name="quality" required>
            <option value="Neuf" <?php if ($article['Quality'] == 'Neuf') echo 'selected'; ?>>Neuf</option>
            <option value="Occasion" <?php if ($article['Quality'] == 'Occasion') echo 'selected'; ?>>Occasion</option>
            <option value="Défaut mineur" <?php if ($article['Quality'] == 'Défaut mineur') echo 'selected'; ?>>Défaut mineur</option>
        </select><br><br>
        
        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" min="0" value="<?php echo $article['Stock']; ?>" required><br><br>
        
        <label for="item_type">Type d'article:</label><br>
        <select id="item_type" name="item_type" required>
            <option value="Articles rares" <?php if ($article['ItemType'] == 'Articles rares') echo 'selected'; ?>>Articles rares</option>
            <option value="Articles hautes de gamme" <?php if ($article['ItemType'] == 'Articles hautes de gamme') echo 'selected'; ?>>Articles hautes de gamme</option>
            <option value="Articles reguliers" <?php if ($article['ItemType'] == 'Articles réguliers') echo 'selected'; ?>>Articles réguliers</option>
        </select><br><br>
        
        <input type="submit" value="Enregistrer les modifications">
    </form>
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
