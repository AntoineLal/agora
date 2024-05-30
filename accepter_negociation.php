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

        // Mettre à jour l'état de la négociation en "Accepted"
        $update_query = "UPDATE Negociations SET Status = 'Accepted' WHERE NegociationID = $negociation_id";
        if ($conn->query($update_query) !== TRUE) {
            echo "Erreur lors de l'acceptation de la négociation : " . $conn->error;
            exit();
        }

        // Vérifier si l'utilisateur a déjà un panier en cours
        $panier_id_query = "SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours'";
        $panier_id_result = $conn->query($panier_id_query);

        if ($panier_id_result->num_rows > 0) {
            // Utilisateur a déjà un panier en cours, récupérer le PanierID
            $panier_row = $panier_id_result->fetch_assoc();
            $panier_id = $panier_row['PanierID'];
        } else {
            // Créer un nouveau panier pour l'utilisateur
            $insert_panier_query = "INSERT INTO Panier (UserID, Status) VALUES ($user_id, 'En cours')";
            if ($conn->query($insert_panier_query) !== TRUE) {
                echo "Erreur lors de la création du panier : " . $conn->error;
                exit();
            }
            // Récupérer l'ID du nouveau panier
            $panier_id = $conn->insert_id;
        }

        // Calculer la différence entre le prix original et le prix négocié
        $article_price_query = "SELECT Price FROM Articles WHERE ArticleID = $article_id";
        $article_price_result = $conn->query($article_price_query);
        if ($article_price_result->num_rows > 0) {
            $article_price_row = $article_price_result->fetch_assoc();
            $original_price = $article_price_row['Price'];

            // Calculer la remise
            $remise = $original_price - $proposed_price;

            // Insérer la remise dans la table Remises associée au panier
            $insert_remise_query = "INSERT INTO Remises (PanierID, MontantRemise) VALUES ($panier_id, $remise)";
            if ($conn->query($insert_remise_query) !== TRUE) {
                echo "Erreur lors de l'insertion de la remise : " . $conn->error;
                exit();
            }
        } else {
            echo "Erreur: Article non trouvé.";
            exit();
        }

        $insert_panier_article_query = "INSERT INTO PanierArticles (PanierID, ArticleID, Quantity) VALUES ($panier_id, $article_id, 1)";
        if ($conn->query($insert_panier_article_query) !== TRUE) {
            echo "Erreur lors de l'ajout de l'article dans le panier : " . $conn->error;
            exit();
        }

        // Mettre à jour le stock de l'article en retirant 1
        $update_stock_query = "UPDATE Articles SET Stock = Stock - 1 WHERE ArticleID = $article_id";
        if ($conn->query($update_stock_query) !== TRUE) {
            echo "Erreur lors de la mise à jour du stock de l'article : " . $conn->error;
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
