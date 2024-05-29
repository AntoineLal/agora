<?php
session_start();
include 'config.php'; // Inclure le fichier contenant les informations de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["article_id"])) {
    $article_id = $_POST["article_id"];
    $user_id = $_SESSION['user_id'];

    // Récupérer la quantité de l'article dans le panier de l'utilisateur
    $sql_get_quantity = "SELECT Quantity FROM PanierArticles WHERE PanierID IN (SELECT PanierID FROM Panier WHERE UserID = $user_id) AND ArticleID = $article_id";
    $result_get_quantity = $conn->query($sql_get_quantity);

    if ($result_get_quantity->num_rows > 0) {
        $row = $result_get_quantity->fetch_assoc();
        $quantity_in_cart = $row["Quantity"];

        // Supprimer l'article du panier de l'utilisateur
        $sql_delete_from_cart = "DELETE FROM PanierArticles WHERE PanierID IN (SELECT PanierID FROM Panier WHERE UserID = $user_id) AND ArticleID = $article_id";
        if ($conn->query($sql_delete_from_cart) === TRUE) {
            // Mettre à jour le stock dans la table des articles
            $sql_update_stock = "UPDATE Articles SET Stock = Stock + $quantity_in_cart WHERE ArticleID = $article_id";
            if ($conn->query($sql_update_stock) === TRUE) {
                // Rediriger l'utilisateur vers la page du panier
                header("Location: panier.php");
                exit();
            } else {
                echo "Erreur lors de la mise à jour du stock : " . $conn->error;
            }
        } else {
            echo "Erreur lors de la suppression de l'article du panier : " . $conn->error;
        }
    } else {
        echo "Erreur : l'article n'est pas dans le panier.";
    }
} else {
    // Rediriger l'utilisateur vers une page d'erreur si la requête n'est pas valide
    header("Location: error.php");
    exit();
}
?>
