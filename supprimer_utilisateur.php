<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si l'utilisateur est connecté et s'il est administrateur
    if (isset($_SESSION['user_id']) && $_SESSION['usertype'] === 'admin') {
        // Récupérer l'ID de l'utilisateur à supprimer
        $user_id = $_POST['user_id'];

        // Supprimer l'utilisateur de la base de données
        $query = "DELETE FROM Users WHERE UserID = $user_id";
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Utilisateur supprimé avec succès.');</script>";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur: " . $conn->error;
        }
    } else {
        echo "Vous n'avez pas les autorisations nécessaires pour effectuer cette action.";
    }
}
?>
<form action="gestion.php" method="get">
    <input type="submit" value="Retour à la page de gestion">
</form>
x   