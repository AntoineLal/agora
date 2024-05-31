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
        $_SESSION['usertype'] = $user['UserType'];
        $_SESSION['UserImageURL'] = $user['UserImageURL'];
        // Redirection vers la page accueil.php
        header("Location: accueil.php");
        exit();
    } else {
        echo "Erreur de mot de passe ou d'email";
    }
} elseif (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['usertype'])) {
    // Gestion de la création de compte
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    $admincode = isset($_POST['admincode']) ? $_POST['admincode'] : '';

    // Vérification du type de compte et du code admin
    if ($usertype === 'admin') {
        if ($admincode !== '0000') {
            echo "Code admin incorrect.";
            exit;
        }
    }

    $sql = "INSERT INTO Users (UserName, Email, UserPassword, UserType) VALUES ('$username', '$email', '$password', '$usertype')";
    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $username;
        $_SESSION['user_email'] = $email;
        $_SESSION['UserImageURL'] = $_POST['UserImageURL'];

        // Redirection vers la page accueil.php
        header("Location: accueil.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
