<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
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
$username = $_POST['username'];
$email = $_POST['email'];
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Initialiser l'URL de l'image
$image_url = '';

// Vérifier si un fichier image a été téléchargé
if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = "images/";
    $file_name = basename($_FILES['user_image']['name']);
    $file_tmp = $_FILES['user_image']['tmp_name'];
    $file_path = $upload_dir . $file_name;

    // Déplacer le fichier téléchargé vers le dossier de destination
    if (move_uploaded_file($file_tmp, $file_path)) {
        $image_url = $file_path;
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}

// Mettre à jour les informations de l'utilisateur
if (!empty($password)) {
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE Users SET UserName=?, Email=?, UserPassword=?, UserImageURL=? WHERE UserID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $password_hashed, $image_url, $user_id);
} else {
    $sql = "UPDATE Users SET UserName=?, Email=?, UserImageURL=? WHERE UserID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $image_url, $user_id);
}

if ($stmt->execute()) {
    header("Location: moncompte.php");
    exit();
} else {
    echo "Erreur lors de la mise à jour des informations.";
}

$stmt->close();
$conn->close();
?>
