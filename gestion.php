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
    <link rel="stylesheet" href="style1.css">
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
</head>
<body>
<header>
    <h1>Gestion - Agora Francia</h1>
    <nav>
        <a href="accueil.php">Accueil</a>
        <a href="toutAfficher.php">Tout Parcourir</a>
        <a href="logout.php">Se déconnecter</a>
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
