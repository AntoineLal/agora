<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Récupérer les filtres depuis l'URL
$TypeVente = isset($_GET['TypeVente']) ? $_GET['TypeVente'] : 'Tous';
$TypeAchat = isset($_GET['TypeAchat']) ? $_GET['TypeAchat'] : 'Tous';
$Rarete = isset($_GET['Rarete']) ? $_GET['Rarete'] : 'Tous';

// Construire la condition de filtre SQL
$filter_conditions = [];
if ($TypeVente != 'Tous') {
    $filter_conditions[] = "TypeVente = '$TypeVente'";
}
if ($TypeAchat != 'Tous') {
    $filter_conditions[] = "Quality = '$TypeAchat'";
}
if ($Rarete != 'Tous') {
    $filter_conditions[] = "ItemType = '$Rarete'";
}

// Requête SQL pour compter les articles correspondant aux filtres
$sql = "SELECT COUNT(*) AS Quantity FROM Articles";
if (!empty($filter_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $filter_conditions);
}

$result = $conn->query($sql);
$quantity = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $quantity = $row['Quantity'];
}

// Enregistrer la notification dans la base de données
$user_id = $_SESSION['user_id'];
$insert_sql = "INSERT INTO Notifications (UserID, TypeVente, TypeAchat, Rarete, Quantity) VALUES ('$user_id', '$TypeVente', '$TypeAchat', '$Rarete', '$quantity')";

if ($conn->query($insert_sql) === TRUE) {
    echo "Notification créée avec succès.";
} else {
    echo "Erreur : " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification créée</title>
</head>
<body>
    <h1>Notification créée avec succès</h1>
    <p><a href="toutAfficher.php">Retour à la page précédente</a></p>
</body>
</html>
