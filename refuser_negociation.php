<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour accéder à cette page.";
    exit();
}

// Vérifier si la requête POST contient les données nécessaires
if(isset($_POST['negociation_id'])) {
    $negociation_id = $_POST['negociation_id'];

    // Mettre à jour le statut de la négociation comme "Refusée" dans la base de données
    $sql = "UPDATE Negociations SET Status = 'Rejected' WHERE NegociationID = $negociation_id";
    if ($conn->query($sql) === TRUE) {
        echo "La négociation a été refusée avec succès.";
    } else {
        echo "Erreur lors du refus de la négociation : " . $conn->error;
    }
} else {
    echo "Données manquantes pour traiter la requête.";
}

$conn->close();

// Redirection vers la page des notifications
header("Location: notifications.php");
exit();
?>
