    <?php
    // Démarrer la session
    session_start();
    include 'config.php'; // Fichier contenant les informations de connexion à la base de données

    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        header("Location: login.html");
        exit();
    }

    // Récupérer l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupérer les articles du panier de l'utilisateur à partir de la base de données
    $sql = "SELECT Articles.*, PanierArticles.Quantity FROM Articles 
            INNER JOIN PanierArticles ON Articles.ArticleID = PanierArticles.ArticleID 
            INNER JOIN Panier ON Panier.PanierID = PanierArticles.PanierID
            WHERE Panier.UserID = $user_id";

    $result = $conn->query($sql);

    // Tableau pour stocker les articles du panier
    $panier_items = [];
    $total = 0; // Initialiser le total à zéro

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $panier_items[] = $row;
            // Ajouter le prix total de chaque article au total
            $total += $row['Price'] * $row['Quantity'];
        }
    }

    // Récupérer la remise associée au panier de l'utilisateur
    $remise_query = "SELECT MontantRemise FROM Remises WHERE PanierID = (SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours')";
    $remise_result = $conn->query($remise_query);
    if ($remise_result->num_rows > 0) {
        $remise_row = $remise_result->fetch_assoc();
        $remise_amount = $remise_row['MontantRemise'];
        // Soustraire le montant de la remise du total
        $total -= $remise_amount;
    }
    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panier - Agora Francia</title>
        <link rel="stylesheet" href="style1.css"> 
        
    </head>
    <body>
        <header>
            <h1>Panier</h1>
        </header>
        <nav>
            <a href="accueil.php">Accueil</a>
            <a href="toutAfficher.php">Tout Parcourir</a>
            <a href="#notifications">Notifications</a>
            <a href="#panier">Panier</a>
            <a href="compte.html">Votre Compte</a>
        </nav>
        <div class="content">
            <h2>Votre Panier</h2>
            <div class="panier-items">
                <?php foreach ($panier_items as $item): ?>
                    <div class="item">
                        <h3><?php echo htmlspecialchars($item['ArticleName']); ?></h3>
                        <p>Quantité : <?php echo $item['Quantity']; ?></p>
                        <p>Prix unitaire : <?php echo $item['Price']; ?> €</p>
                        <form action="retirerdupanier.php" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $item['ArticleID']; ?>">
                        <input type="submit" value="Supprimer">
                </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="total">
                <h3>Total :</h3>
                <p>Total avant remise : <?php echo $total + $remise_amount; ?> €</p>
                <p>Remise : <?php echo $remise_amount; ?> €</p>
                <p>Total après remise : <?php echo $total; ?> €</p>
            </div>
            <div class="checkout">
                <form action="payment.php" method="post">
                    <input type="submit" value="Passer la commande">
                </form>
            </div>
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
