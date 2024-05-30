<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['user_id'];
    $proposed_price = $_POST['proposed_price'];

    // Vérifier si l'utilisateur a déjà soumis une proposition pour cet article
    $check_query = "SELECT * FROM Negociations WHERE ArticleID = $article_id AND UserID = $user_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo "Vous avez déjà soumis une proposition de négociation pour cet article.";
        exit();
    }

    // Insérer la proposition de négociation dans la table Negociations
    $insert_query = "INSERT INTO Negociations (ArticleID, UserID, ProposedPrice) VALUES ($article_id, $user_id, $proposed_price)";
    
    if ($conn->query($insert_query) === TRUE) {
        echo "Votre proposition de négociation a été soumise avec succès.";
    } else {
        echo "Erreur lors de la soumission de la proposition de négociation : " . $conn->error;
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Négociation - Agora Francia</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <header>
        <h1>Négociation - Agora Francia</h1>
    </header>
    <nav>
        <a href="accueil.php">Accueil</a>
        <a href="toutAfficher.php">Tout Parcourir</a>
        <a href="#notifications">Notifications</a>
        <a href="panier.php">Panier</a>
        <a href="compte.html">Votre Compte</a>
    </nav>
    <div class="content">
        <h2>Négociation pour l'article</h2>
        <?php
        // Récupérer l'article depuis la base de données
        $article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;
        $sql = "SELECT ArticleName, Price FROM Articles WHERE ArticleID = $article_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<p>Nom de l'article : " . $row["ArticleName"] . "</p>";
            echo "<p>Prix de l'article : " . $row["Price"] . "€</p>";
        } else {
            echo "Article non trouvé.";
        }
        ?>
        <form action="negociation.php" method="POST">
            <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
            <label for="proposed_price">Proposition de prix :</label>
            <input type="number" id="proposed_price" name="proposed_price" step="0.01" min="0" required>
            <button type="submit">Soumettre proposition</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    </footer>
</body>
</html>
