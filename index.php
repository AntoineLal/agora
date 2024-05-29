<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Agora Francia</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="container">
    <h1>Bienvenue sur Agora Francia</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Vous êtes connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>.</p>
        <p>Email : <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <a href="logout.php">Se déconnecter</a>
    <?php else: ?>
        <p>Vous n'êtes pas connecté.</p>
        <a href="login.html">Se connecter</a> ou <a href="register.php">Créer un compte</a>
    <?php endif; ?>
</div>
</body>
</html>
