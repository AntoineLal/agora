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

$user_id = $_SESSION['user_id'];

// Récupérer les articles de l'utilisateur connecté
$sql = "SELECT * FROM Articles WHERE UserID='$user_id'";
$result = $conn->query($sql);
$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Offres - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td img {
            max-width: 100px;
            max-height: 100px;
        }

        .btn {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .create-offer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Mes Offres</h1>
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
    <h2>Articles en vente</h2>
    <?php if (count($articles) > 0): ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Nom de l'article</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Qualité</th>
                <th>Stock</th>
                <th>Type de vente</th>
                <th>Type d'article</th>
                <th>Action</th>
            </tr>
            <?php foreach ($articles as $article): ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($article['ImageURL']); ?>" alt="<?php echo htmlspecialchars($article['ArticleName']); ?>"></td>
                <td><?php echo htmlspecialchars($article['ArticleName']); ?></td>
                <td><?php echo htmlspecialchars($article['Description']); ?></td>
                <td><?php echo htmlspecialchars($article['Price']); ?> €</td>
                <td><?php echo htmlspecialchars($article['Quality']); ?></td>
                <td><?php echo htmlspecialchars($article['Stock']); ?></td>
                <td><?php echo htmlspecialchars($article['TypeVente']); ?></td>
                <td><?php echo htmlspecialchars($article['ItemType']); ?></td>
                <td><a href="modifier_article.php?id=<?php echo $article['ArticleID']; ?>" class="btn">Modifier</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Vous n'avez aucun article en vente actuellement.</p>
    <?php endif; ?>
    <div class="create-offer">
        <a href="ajouter_article.php" class="btn">Créer une nouvelle offre</a>
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
