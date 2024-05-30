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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['usertype'])) {
        // Gestion de la création de compte
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $usertype = $_POST['usertype'];
        $admincode = isset($_POST['admincode']) ? $_POST['admincode'] : '';

        // Vérification du type de compte et du code admin
        if ($usertype === 'admin') {
            if ($admincode !== '0000') {
                $error_message = "Code admin incorrect.";
            } else {
                $sql = "INSERT INTO Users (UserName, Email, UserPassword, UserType) VALUES ('$username', '$email', '$password', '$usertype')";
                if ($conn->query($sql) === TRUE) {
                    $user_id = $conn->insert_id;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $username;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['usertype'] = $usertype;
                    $success_message = "Compte créé avec succès";
                } else {
                    $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
                }
            }
        } else {
            $sql = "INSERT INTO Users (UserName, Email, UserPassword, UserType) VALUES ('$username', '$email', '$password', '$usertype')";
            if ($conn->query($sql) === TRUE) {
                $user_id = $conn->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $username;
                $_SESSION['user_email'] = $email;
                $_SESSION['usertype'] = $usertype;

                $success_message = "Compte créé avec succès";
            } else {
                $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Agora Francia</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="container">
        <h2>Créer un compte</h2>
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <form id="registerForm" action="register.php" method="post">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <label for="usertype">Je suis :</label>
            <select id="usertype" name="usertype" onchange="showAdminCodeField()">
                <option value="buyer">Acheteur</option>
                <option value="seller">Vendeur</option>
                <option value="admin">Admin</option>
            </select>
            <div id="adminCodeField">
                <label for="admincode">Code Admin :</label>
                <input type="password" id="admincode" name="admincode">
            </div>
            <button type="submit">Créer un compte</button>
        </form>
        <div class="toggle-link">
            <a href="login.html">Déjà un compte ? Se connecter</a>
        </div>
    </div>
    <script>
        function showAdminCodeField() {
            var userType = document.getElementById('usertype').value;
            var adminCodeField = document.getElementById('adminCodeField');
            if (userType === 'admin') {
                adminCodeField.style.display = 'block';
            } else {
                adminCodeField.style.display = 'none';
            }
        }

        // Initialiser l'état du champ de code admin au chargement de la page
        showAdminCodeField();
    </script>
</body>
</html>