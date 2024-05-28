<?php
session_start(); // Démarrer la session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
    // Gestion de la connexion
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $sql = "SELECT * FROM Users WHERE Email='$email' AND UserPassword='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_name'] = $user['UserName'];
        $_SESSION['user_email'] = $user['Email'];
        echo "Connecté avec succès";
    } else {
        echo "Erreur de mot de passe ou d'email";
    }
} elseif (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Gestion de la création de compte
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO Users (UserName, Email, UserPassword, UserType) VALUES ('$name', '$email', '$password', 'Buyer')";
    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        echo "Compte créé avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
