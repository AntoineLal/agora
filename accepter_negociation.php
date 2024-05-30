<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $negociation_id = $_POST['negociation_id'];
    $user_id = $_SESSION['user_id'];

    // Récupérer les détails de la négociation, y compris le prix négocié
    $negociation_query = "SELECT ArticleID, ProposedPrice FROM Negociations WHERE NegociationID = $negociation_id";
    $negociation_result = $conn->query($negociation_query);
    if ($negociation_result->num_rows > 0) {
        $negociation_row = $negociation_result->fetch_assoc();
        $article_id = $negociation_row['ArticleID'];
        $proposed_price = $negociation_row['ProposedPrice'];

        // Insérer un nouvel article avec le prix négocié et le type de vente "remise"
        $insert_article_query = "INSERT INTO Articles (ArticleName, Description, TypeVente, Price, Stock, UserID, ItemType, CreatedAt, UpdatedAt) 
                                 SELECT ArticleName, Description, 'remise', $proposed_price, Stock, UserID, ItemType, NOW(), NOW() 
                                 FROM Articles WHERE ArticleID = $article_id";
        if ($conn->query($insert_article_query) !== TRUE) {
            echo "Erreur lors de la création de l'article avec le prix négocié : " . $conn->error;
            exit();
        }

        // Récupérer l'ID du nouvel article créé
        $new_article_id = $conn->insert_id;

        // Mettre à jour l'état de la négociation en "Accepted"
        $update_query = "UPDATE Negociations SET Status = 'Accepted' WHERE NegociationID = $negociation_id";
        if ($conn->query($update_query) !== TRUE) {
            echo "Erreur lors de l'acceptation de la négociation : " . $conn->error;
            exit();
        }

        // Ajouter l'article dans le panier de l'utilisateur
        $insert_panier_query = "INSERT INTO PanierArticles (PanierID, ArticleID, Quantity) VALUES ((SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours'), $new_article_id, 1)";
        if ($conn->query($insert_panier_query) !== TRUE) {
            echo "Erreur lors de l'ajout de l'article dans le panier : " . $conn->error;
            exit();
        }

        // Mettre à jour le stock de l'article original en retirant 1
        $update_stock_query = "UPDATE Articles SET Stock = Stock - 1 WHERE ArticleID = $article_id";
        if ($conn->query($update_stock_query) !== TRUE) {
            echo "Erreur lors de la mise à jour du stock de l'article original : " . $conn->error;
            exit();
        }
    } else {
        echo "Erreur: Négociation non trouvée.";
        exit();
    }

    // Redirection vers la page de notifications
    header("Location: notifications.php");
    exit();
}
?>
