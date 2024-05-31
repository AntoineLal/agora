<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si le nouveau mot de passe est présent
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        // Récupérer l'ID de l'utilisateur connecté
        $user_id = $_SESSION['user_id'];

        // Nouveau mot de passe
        $new_password = $_POST['password'];

        // Hasher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "agora";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }   

        // Préparer la requête SQL pour mettre à jour le mot de passe
        $sql = "UPDATE Users SET UserPassword='$hashed_password' WHERE UserID='$user_id'";

        if ($conn->query($sql) === TRUE) {
            // Mot de passe mis à jour avec succès
            echo "Mot de passe mis à jour avec succès." . $conn->error;
        } else {
            echo "Erreur lors de la mise à jour du mot de passe : " . $conn->error;
        }

        $conn->close();
    } else {
        // Le champ du nouveau mot de passe est vide
        echo "Veuillez fournir un nouveau mot de passe.";
    }
}
?>
