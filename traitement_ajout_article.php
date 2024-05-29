<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est un vendeur
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'seller') {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_name = $_POST['article_name'];
    $description = $_POST['description'];
    $type_vente = $_POST['type_vente'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $video_url = $_POST['video_url'];
    $quality = $_POST['quality'];
    $stock = $_POST['stock'];
    $item_type = $_POST['item_type'];

    // Préparer et exécuter la requête SQL
    $sql = "INSERT INTO Articles (UserID, ArticleName, Description, TypeVente, Price, ImageURL, VideoURL, Quality, Stock, ItemType)
            VALUES ('$user_id', '$article_name', '$description', '$type_vente', '$price', '$image_url', '$video_url', '$quality', '$stock', '$item_type')";

    if ($conn->query($sql) === TRUE) {
        header("Location: offres.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
