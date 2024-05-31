<?php
session_start();
include 'config.php';

// Vérifie si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: accueil.php"); // Redirige les utilisateurs non connectés ou non administrateurs vers la page d'accueil
    exit;
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Conserve le mot de passe tel quel

    // Prépare la requête d'insertion
    $sql = "INSERT INTO Users (UserName, Email, UserPassword, UserType) VALUES (?, ?, ?, ?)";
    
    // Prépare la déclaration
    if ($stmt = $conn->prepare($sql)) {
        // Lie les paramètres à la déclaration
        $stmt->bind_param("ssss", $username, $email, $password, $usertype);

        // Paramètres
        $usertype = "seller"; // Par défaut, l'utilisateur ajouté sera un vendeur

        // Exécute la déclaration
        if ($stmt->execute()) {
            // Redirige vers la page de gestion avec un message de succès
            header("Location: gestion.php?success=1");
            exit();
        } else {
            // En cas d'erreur, affiche un message d'erreur
            echo "Erreur lors de l'ajout du vendeur : " . $stmt->error;
        }

        // Ferme la déclaration
        $stmt->close();
    } else {
        // En cas d'erreur de préparation, affiche un message d'erreur
        echo "Erreur de préparation de la requête : " . $conn->error;
    }

    // Ferme la connexion à la base de données
    $conn->close();
}
?>
