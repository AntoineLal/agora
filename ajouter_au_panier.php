<?php
session_start();

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
    $user_id = $_SESSION['UserID']; // Assurez-vous que l'utilisateur est connecté et que l'ID utilisateur est stocké dans la session

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
}

$conn->close();
header("Location: panier.php");
exit();
?>
