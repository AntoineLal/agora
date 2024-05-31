<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

// Vérifie si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

// Fonction pour afficher les utilisateurs
function afficherUtilisateurs($conn) {
    $query = "SELECT * FROM Users";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Utilisateurs</h2>";
        echo "<table>";
        echo "<tr><th>User ID</th><th>User Name</th><th>Email</th><th>User Type</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['UserType'] !== 'admin') {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserType']) . "</td>";
                echo "<td><form action='supprimer_utilisateur.php' method='post'>";
                echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['UserID']) . "'>";
                echo "<input type='submit' value='Supprimer'>";
                echo "</form></td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserType']) . "</td>";
                echo "<td>Admin</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    } else {
        echo "Aucun utilisateur trouvé.";
    }
}

// Fonction pour afficher les articles
function afficherArticles($conn) {
    $query = "SELECT * FROM Articles";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Articles</h2>";
        echo "<table>";
        echo "<tr><th>Article ID</th><th>Article Name</th><th>Description</th><th>Type Vente</th><th>Price</th><th>Stock</th><th>User ID</th><th>Item Type</th><th>Quality</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ArticleID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ArticleName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['TypeVente']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Stock']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ItemType']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Quality']) . "</td>";
            echo "<td><form action='supprimer_article.php' method='post'>";
            echo "<input type='hidden' name='article_id' value='" . htmlspecialchars($row['ArticleID']) . "'>";
            echo "<input type='submit' value='Supprimer'>";
            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Aucun article trouvé.";
    }
}

// Gestion de l'ajout d'un nouvel article
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_article'])) {
    $article_name = $_POST['article_name'];
    $description = $_POST['description'];
    $type_vente = $_POST['type_vente'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $user_id = $_POST['user_id'];
    $item_type = $_POST['item_type'];
    $quality = $_POST['quality'];
    $image_url = $_POST['image_url'];
    $video_url = $_POST['video_url'];

    $query = "INSERT INTO Articles (ArticleName, Description, TypeVente, Price, Stock, UserID, ItemType, Quality, ImageURL, VideoURL)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdiissss", $article_name, $description, $type_vente, $price, $stock, $user_id, $item_type, $quality, $image_url, $video_url);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Nouvel article ajouté avec succès.</p>";
    } else {
        echo "<p style='color: red;'>Erreur : " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Gestion de l'ajout d'un nouveau vendeur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_vendeur'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Cryptage du mot de passe

    $query = "INSERT INTO Users (UserName, Email, Password, UserType) VALUES (?, ?, ?, 'seller')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Nouveau vendeur ajouté avec succès.</p>";
    } else {
        echo "<p style='color: red;'>Erreur : " . $stmt->error . "</p>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin: 10px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .forms-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
    </style>
</head>
<body>
<header>
    <h1>GESTION</h1>
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
    <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;"></a>
        <a href="logout.php">déconnexion</a>

    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
</header>

<div class="content">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="submit" name="afficher_users" value="Afficher la liste des utilisateurs">
        <input type="submit" name="afficher_articles" value="Afficher la liste des articles">
    </form>

    <?php
    if (isset($_POST['afficher_users'])) {
        afficherUtilisateurs($conn);
    }

    if (isset($_POST['afficher_articles'])) {
        afficherArticles($conn);
    }
    ?>

    <div class="forms-container">
        <div class="form-container">
            <h2>Ajouter un article</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="ajouter_article" value="1">
                <label for="article_name">Nom de l'article :</label>
                <input type="text" id="article_name" name="article_name" required>
                
                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>
                
                <label for="type_vente">Type de Vente :</label>
                <select id="type_vente" name="type_vente" required>
                    <option value="Immediat">Immediat</option>
                    <option value="Enchere">Enchere</option>
                    <option value="Negotiation">Negotiation</option>
                </select>
                
                <label for="price">Prix :</label>
                <input type="number" step="0.01" id="price" name="price" required>
                
                <label for="stock">Stock :</label>
                <input type="number" id="stock" name="stock" required>
                
                <label for="user_id">ID Utilisateur :</label>
                <input type="number" id="user_id" name="user_id" required>
                
                <label for="item_type">Type d'Article :</label>
                <select id="item_type" name="item_type" required>
                    <option value="Articles rares">Articles rares</option>
                    <option value="Articles hautes de gamme">Articles hautes de gamme</option>
                    <option value="Articles réguliers">Articles réguliers</option>
                </select>
                
                <label for="quality">Qualité :</label>
                <select id="quality" name="quality" required>
                    <option value="Neuf">Neuf</option>
                    <option value="Occasion">Occasion</option>
                    <option value="Défaut mineur">Défaut mineur</option>
                </select>
                
                <label for="image_url">URL de l'image :</label>
                <input type="text" id="image_url" name="image_url">
                
                <label for="video_url">URL de la vidéo :</label>
                <input type="text" id="video_url" name="video_url">
                
                <input type="submit" value="Ajouter l'article">
            </form>
        </div>

        <div class="form-container">
            <h2>Ajouter un vendeur</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="ajouter_vendeur" value="1">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <input type="submit" value="Ajouter le vendeur">
            </form>
        </div>
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

<?php
$conn->close();
?>
