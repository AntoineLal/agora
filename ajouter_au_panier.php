<?php
session_start(); // Démarrer la session
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agora";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = intval($_POST["article_id"]);
    $quantity = intval($_POST["quantity"]);
    $user_id = $_SESSION['user_id'];

    // Récupérer la quantité en stock de l'article sélectionné
    $sql_stock = "SELECT Stock FROM Articles WHERE ArticleID = $article_id";
    $result_stock = $conn->query($sql_stock);

    if ($result_stock->num_rows > 0) {
        $row_stock = $result_stock->fetch_assoc();
        $stock_disponible = $row_stock["Stock"];

        // Vérifier si la quantité à ajouter au panier ne dépasse pas le stock disponible
        if ($quantity <= $stock_disponible) {
            // Soustraire la quantité ajoutée au panier des stocks disponibles des articles
            $nouveau_stock = $stock_disponible - $quantity;
            $sql_update_stock = "UPDATE Articles SET Stock = $nouveau_stock WHERE ArticleID = $article_id";
            if ($conn->query($sql_update_stock) === TRUE) {
                // Ajouter l'article au panier
                $sql_check = "SELECT * FROM Panier WHERE UserID = $user_id AND Status = 'En cours'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    $row = $result_check->fetch_assoc();
                    $panier_id = $row["PanierID"];
                } else {
                    $sql_create = "INSERT INTO Panier (UserID) VALUES ($user_id)";
                    if ($conn->query($sql_create) === TRUE) {
                        $panier_id = $conn->insert_id;
                    } else {
                        die("Erreur lors de la création du panier : " . $conn->error);
                    }
                }

                $sql_add = "INSERT INTO PanierArticles (PanierID, ArticleID, Quantity) VALUES ($panier_id, $article_id, $quantity)
                            ON DUPLICATE KEY UPDATE Quantity = Quantity + VALUES(Quantity)";
                if ($conn->query($sql_add) === TRUE) {
                    echo "Article ajouté au panier avec succès.";
                } else {
                    die("Erreur lors de l'ajout de l'article au panier : " . $conn->error);
                }
            } else {
                die("Erreur lors de la mise à jour des stocks d'articles : " . $conn->error);
            }
        } else {
            echo "La quantité demandée dépasse le stock disponible.";
        }
    } else {
        echo "Article non trouvé.";
    }
}

$conn->close();
header("Location: panier.php");
exit();
?>
