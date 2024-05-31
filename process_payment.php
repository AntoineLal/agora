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

    // Récupérer l'ID du panier en cours de l'utilisateur
    $panier_query = "SELECT PanierID FROM Panier WHERE UserID = $user_id AND Status = 'En cours'";
    $panier_result = $conn->query($panier_query);

    if ($panier_result->num_rows > 0) {
        $panier_row = $panier_result->fetch_assoc();
        $panier_id = $panier_row['PanierID'];

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
