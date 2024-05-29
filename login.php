<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $sql = "SELECT * FROM Users WHERE Email='$email' AND UserPassword='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_name'] = $user['UserName'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['usertype'] = $user['UserType']; // Stocker le type d'utilisateur dans la session
        echo "Connecté avec succès";
        header("Location: accueil.php");
    } else {
        echo "Erreur de mot de passe ou d'email";
    }
}

$conn->close();
?>
