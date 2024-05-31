<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour accéder à cette page.";
    exit();
}

// Vérifier si la requête POST contient les données nécessaires
if (isset($_POST['negociation_id']) && isset($_POST['nouveau_prix'])) {
    $negociation_id = $_POST['negociation_id'];
    $nouveau_prix = $_POST['nouveau_prix'];
    $user_id = $_SESSION['user_id'];

    // Récupérer les informations actuelles de la négociation
    $sql = "SELECT N.*, A.UserID as SellerID, N.UserID as BuyerID FROM Negociations N
            INNER JOIN Articles A ON N.ArticleID = A.ArticleID
            WHERE N.NegociationID = $negociation_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $negociation = $result->fetch_assoc();

        // Déterminer le nouveau statut en fonction du rôle de l'utilisateur
        if ($negociation['BuyerID'] == $user_id) {
            // L'utilisateur est l'acheteur, passe à PendingSeller
            $nouveau_statut = 'PendingSeller';
        } else if ($negociation['SellerID'] == $user_id) {
            // L'utilisateur est le vendeur, passe à PendingBuyer
            $nouveau_statut = 'PendingBuyer';
        } else {
            echo "Vous n'êtes pas autorisé à effectuer cette action.";
            exit();
        }

        // Incrémenter le nombre d'étapes de négociation
        $etapes_nego = $negociation['EtapeNego'] + 1;

        // Mettre à jour la négociation avec le nouveau prix, le nouveau statut et le nombre d'étapes de négociation
        $sql_update = "UPDATE Negociations SET ProposedPrice = ?, Status = ?, EtapeNego = ? WHERE NegociationID = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("isii", $nouveau_prix, $nouveau_statut, $etapes_nego, $negociation_id);

        if ($stmt->execute()) {
            echo "La contre-offre a été envoyée avec succès.";
        } else {
            echo "Erreur lors de l'envoi de la contre-offre : " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Négociation non trouvée.";
    }
} else {
    echo "Données manquantes pour traiter la requête.";
}

$conn->close();

// Redirection vers la page des notifications
header("Location: notifications.php");
exit();
?>
