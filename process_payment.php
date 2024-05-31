<?php
session_start();
include 'config.php'; // Inclure le fichier contenant les informations de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: login.html");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifier si le formulaire de paiement a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données soumises par le formulaire
    $nom = $_POST['nom'];
    $adresse1 = $_POST['adresse1'];
    $adresse2 = $_POST['adresse2'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $telephone = $_POST['telephone'];
    $type_carte = $_POST['type_carte'];
    $numero_carte = $_POST['numero_carte'];
    $nom_carte = $_POST['nom_carte'];
    $date_expiration = $_POST['date_expiration'];
    $code_secu = $_POST['code_secu'];

    // Récupérer l'ID du panier en cours de l'utilisateur
    $panier_query = "SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours'";
    $panier_result = $conn->query($panier_query);

    if ($panier_result->num_rows > 0) {
        $panier_row = $panier_result->fetch_assoc();
        $panier_id = $panier_row['PanierID'];

        // Récupérer les articles du panier et leurs quantités
        $articles_query = "SELECT PA.ArticleID, PA.Quantity, A.Price
                           FROM PanierArticles PA
                           INNER JOIN Articles A ON PA.ArticleID = A.ArticleID
                           WHERE PA.PanierID = $panier_id";
        $articles_result = $conn->query($articles_query);

        if ($articles_result->num_rows > 0) {
            while ($article_row = $articles_result->fetch_assoc()) {
                $article_id = $article_row['ArticleID'];
                $quantity = $article_row['Quantity'];
                $price = $article_row['Price'];
                $total_price = $price * $quantity;

                // Insérer les données dans la table Negociations
                $sql_insert_negociation = "INSERT INTO Negociations (ArticleID, UserID, ProposedPrice, Status) VALUES ($article_id, $user_id, $total_price, 'Vendu')";
                if ($conn->query($sql_insert_negociation) !== TRUE) {
                    echo "Erreur lors de la création de la négociation: " . $conn->error;
                    exit();
                }
            }
        }

        // Supprimer les remises associées au panier
        $sql_delete_remises = "DELETE FROM Remises WHERE PanierID = $panier_id";
        if ($conn->query($sql_delete_remises) !== TRUE) {
            echo "Erreur lors de la suppression des remises: " . $conn->error;
            exit();
        }

        // Supprimer les articles du panier
        $sql_delete_panier_articles = "DELETE FROM PanierArticles WHERE PanierID = $panier_id";
        if ($conn->query($sql_delete_panier_articles) !== TRUE) {
            echo "Erreur lors de la suppression des articles du panier: " . $conn->error;
            exit();
        }

        // Supprimer le panier
        $sql_delete_panier = "DELETE FROM Panier WHERE PanierID = $panier_id";
        if ($conn->query($sql_delete_panier) !== TRUE) {
            echo "Erreur lors de la suppression du panier: " . $conn->error;
            exit();
        }
    }

    // Rediriger l'utilisateur vers la page de confirmation
    header("Location: confirmation.php");
    exit();
} else {
    // Si le formulaire n'a pas été soumis correctement, rediriger l'utilisateur vers une page d'erreur ou une autre page appropriée
    header("Location: error.php");
    exit();
}
?>
