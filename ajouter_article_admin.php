<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

// Vérifie si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article_name = $_POST['article_name'];
    $description = $_POST['description'];
    $type_vente = $_POST['type_vente'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $user_id = $_POST['user_id'];
    $item_type = $_POST['item_type'];

    $query = "INSERT INTO Articles (ArticleName, Description, TypeVente, Price, Stock, UserID, ItemType)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdiis", $article_name, $description, $type_vente, $price, $stock, $user_id, $item_type);

    if ($stmt->execute()) {
        echo "Nouvel article ajouté avec succès.";
        header("Location: gestion.php");
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
