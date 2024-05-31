<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications de Négociation - Agora Francia</title>
    <link rel="stylesheet" href="style1.css"> 
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<header>
    <h1>Notifications de Négociation - Agora Francia</h1>
</header>
<nav>
    <a href="accueil.php">Accueil</a>
    <a href="toutAfficher.php">Tout Parcourir</a>
    <a href="#notifications">Notifications</a>

    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'buyer'): ?>
        <a href="panier.php">Panier</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'seller'): ?>
        <a href="offres.php">Mes Offres</a>
    <?php elseif (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
        <a href="gestion.php">Gestion</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="moncompte.php" style="display: inline-block; margin: 0; padding: 0;">
    <img src="<?php echo htmlspecialchars($_SESSION['UserImageURL']); ?>" alt="Image de profil" style="max-width: 120px; max-height: 60px; margin: 0; padding: 0; border: none;"></a>
        <a href="logout.php">déconnexion</a>

    <?php else: ?>
        <a href="login.html">Se connecter</a>
    <?php endif; ?>
</nav>
<div class="content">
    <?php
    // Récupérer les notifications de négociation pour l'utilisateur
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['usertype'];

    $sql = "SELECT N.NegociationID, N.ArticleID, A.ArticleName, N.ProposedPrice, N.Status
            FROM Negociations N
            INNER JOIN Articles A ON N.ArticleID = A.ArticleID
            WHERE A.UserID = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Notifications de Négociation</h2>";
        echo "<table>";
        echo "<tr><th>Article</th><th>Prix Proposé</th><th>État</th>";
        if ($user_type === 'seller') {
            echo "<th>Action</th>";
        }
        echo "<th>Supprimer</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ArticleName"] . "</td>";
            echo "<td>" . $row["ProposedPrice"] . "€</td>";
            echo "<td>" . $row["Status"] . "</td>";
            if ($user_type === 'seller') {
                echo "<td>";
                if ($row["Status"] === 'Pending') {
                    echo '<form action="accepter_negociation.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="negociation_id" value="' . $row["NegociationID"] . '">';
                    echo '<input type="submit" name="accepter" value="Accepter">';
                    echo '</form>';
                    echo '<form action="refuser_negociation.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="negociation_id" value="' . $row["NegociationID"] . '">';
                    echo '<input type="submit" name="refuser" value="Refuser">';
                    echo '</form>';
                }
                echo "</td>";
            }
            echo '<td>';
            if ($row["Status"] !== 'Pending') {
                echo '<form action="supprimer_negociation.php" method="POST">';
                echo '<input type="hidden" name="negociation_id" value="' . $row["NegociationID"] . '">';
                echo '<input type="submit" name="supprimer" value="Supprimer">';
                echo '</form>';
            }
            echo '</td>';
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucune notification de négociation.";
    }
    ?>
</div>
<div id="footer">
  <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
  <p>
      <a href="mentions-legales.html">Mentions légales</a> |
      <a href="confidentialie.html">Politique de confidentialité</a> |
      <a href="mailto:agora78@gmail.com">Contact</a>
  </p>
</div>
</body>
</html>
