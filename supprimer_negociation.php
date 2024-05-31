<?php
session_start();
include 'config.php';

if (isset($_POST['negociation_id'])) {
    $negociation_id = $_POST['negociation_id'];

    // Supprimer la négociation de la base de données
    $sql = "DELETE FROM Negociations WHERE NegociationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $negociation_id);

    if ($stmt->execute()) {
        // Rediriger vers la page de notifications avec un message de succès
        header("Location: notifications.php?message=Negociation supprimée avec succès");
    } else {
        // Rediriger vers la page de notifications avec un message d'erreur
        header("Location: notifications.php?message=Erreur lors de la suppression de la négociation");
    }

    $stmt->close();
}

$conn->close();
?>
