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

// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST['article_id'];
    $article_name = $_POST['article_name'];
    $description = $_POST['description'];
    $type_vente = $_POST['type_vente'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url']; // Garder l'URL d'image actuelle par défaut

    // Vérifier si un fichier a été envoyé
    if(isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == UPLOAD_ERR_OK) {
        // Chemin de stockage du fichier téléchargé
        $upload_dir = "images/";
        $file_name = $_FILES['image_upload']['name'];
        $file_tmp = $_FILES['image_upload']['tmp_name'];
        $file_path = $upload_dir . $file_name;

        // Déplacer le fichier téléchargé vers le dossier de destination
        if(move_uploaded_file($file_tmp, $file_path)) {
            // Mettre à jour l'URL de l'image dans la base de données
            $image_url = $file_path;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }

    $video_url = $_POST['video_url'];
    $quality = $_POST['quality'];
    $stock = $_POST['stock'];
    $item_type = $_POST['item_type'];

    // Mettre à jour les informations de l'article dans la base de données
    $sql = "UPDATE Articles SET ArticleName='$article_name', Description='$description', TypeVente='$type_vente', Price='$price', ImageURL='$image_url', VideoURL='$video_url', Quality='$quality', Stock='$stock', ItemType='$item_type' WHERE ArticleID='$article_id'";

    if ($conn->query($sql) === TRUE) {
        // Rediriger vers la page des offres de l'utilisateur
        header("Location: offres.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour de l'article: " . $conn->error;
    }
}

$conn->close();
?>
