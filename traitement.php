<?php
// Connexion à la base de données (à adapter selon votre configuration)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

// Récupération des données du formulaire
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$usertype = $_POST['usertype'];

// Génération d'un ID unique
$userid = uniqid();

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête d'insertion des données dans la table Users
$sql = "INSERT INTO Users (UserID, UserName, Email, UserPassword, UserType) VALUES ('$userid', '$username', '$email', '$password', '$usertype')";

if ($conn->query($sql) === TRUE) {
    echo "Compte créé avec succès !";
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
