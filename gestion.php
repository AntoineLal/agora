<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

// Vérifie si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: accueil.php"); // Redirige les utilisateurs non connectés ou non administrateurs vers la page d'accueil
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
            // Vérifier si l'utilisateur est un administrateur
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
                echo "<td>Admin</td>"; // Afficher simplement "Admin" au lieu du bouton Supprimer
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
        echo "<tr><th>Article ID</th><th>Article Name</th><th>Description</th><th>Type Vente</th><th>Price</th><th>Stock</th><th>User ID</th><th>Item Type</th><th>Actions</th></tr>";
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


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion - Agora Francia</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <link rel="stylesheet" href="style1.css">
    <style>
        img {
            max-width: 150px;
            max-height: 150px;
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

    <h2>Ajouter un vendeur</h2>
    <form action="ajouter_vendeur.php" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Ajouter le vendeur">
    </form>
</div>

<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="mailto:agora78@gmail.com">Contact</a>
    </p>
</div>

</body>
</html>
