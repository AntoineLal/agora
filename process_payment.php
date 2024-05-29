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

    // Insérer les données de paiement dans la base de données ou les traiter selon vos besoins
    // Par exemple, vous pouvez enregistrer les informations de paiement dans une table de commandes
    // et effectuer d'autres opérations telles que la validation du paiement avec un service de paiement tiers.

    // Soustraire les quantités commandées des articles dans la table des articles
    $sql_update_articles = "UPDATE Articles AS a
                            INNER JOIN PanierArticles AS pa ON a.ArticleID = pa.ArticleID
                            SET a.Stock = a.Stock - pa.Quantity
                            WHERE pa.PanierID IN (SELECT PanierID FROM Panier WHERE UserID = $user_id)";
    if ($conn->query($sql_update_articles) === TRUE) {
        // Vider le panier du client
        $sql_delete_panier = "DELETE FROM Panier WHERE UserID = $user_id";
        if ($conn->query($sql_delete_panier) === TRUE) {
            // Rediriger l'utilisateur vers la page de confirmation
            header("Location: confirmation.php");
            exit();
        } else {
            // Gérer l'échec de la suppression du panier
            echo "Erreur lors de la suppression du panier: " . $conn->error;
        }
    } else {
        // Gérer l'échec de la mise à jour des quantités d'articles
        echo "Erreur lors de la mise à jour des quantités d'articles: " . $conn->error;
    }
} else {
    // Si le formulaire n'a pas été soumis correctement, rediriger l'utilisateur vers une page d'erreur ou une autre page appropriée
    header("Location: error.php");
    exit();
}
?>
