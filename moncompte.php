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

// Récupérer les informations de l'utilisateur connecté
$sql = "SELECT * FROM Users WHERE UserID='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Utilisateur non trouvé.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Agora Francia</title>
    <link rel="stylesheet" href="style5.css">
    <link rel="stylesheet" href="style1.css">

</head>
<body>
<header>
    <h1>Mon Compte</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>
    <a href="offres.php">Mes Offres</a>
    <a href="logout.php">Se déconnecter</a>
</nav>
<div class="content">
    <form action="traitement_modifier_compte.php" method="post" enctype="multipart/form-data">
        <label for="username">Nom d'utilisateur:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['UserName']); ?>" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br><br>
        
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password"><br><br>
        
        <label for="user_image">Image de profil:</label><br>
        <input type="file" id="user_image" name="user_image"><br><br>
        <img src="<?php echo htmlspecialchars($user['UserImageURL']); ?>" alt="Image de profil" style="max-width: 150px; max-height: 150px;"><br><br>
        
        <input type="submit" value="Enregistrer les modifications">
    </form>
</div>
<div id="footer">
    <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    <p>
        <a href="mentions-legales.html">Mentions légales</a> |
        <a href="confidentialite.html">Politique de confidentialité</a> |
        <a href="#contact">Contact</a>
    </p>
</div>
</body>
</html>
