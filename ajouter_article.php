<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article - Agora Francia</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
</head>
<body>
    <h1>Ajouter un article</h1>
    <form action="traitement_ajout_article.php" method="post">
        <label for="article_name">Nom de l'article:</label><br>
        <input type="text" id="article_name" name="article_name" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>
        
        <label for="type_vente">Type de vente:</label><br>
        <select id="type_vente" name="type_vente" required>
            <option value="Immediat">Vente immédiate</option>
            <option value="Enchere">Enchère</option>
            <option value="Negociation">Négociation</option>
        </select><br><br>
        
        <label for="price">Prix:</label><br>
        <input type="number" id="price" name="price" min="0" step="0.01" required><br><br>
        
        <label for="image_url">URL de l'image:</label><br>
        <input type="text" id="image_url" name="image_url"><br><br>
        
        <label for="video_url">URL de la vidéo:</label><br>
        <input type="text" id="video_url" name="video_url"><br><br>
        
        <label for="quality">Qualité:</label><br>
        <select id="quality" name="quality" required>
            <option value="Neuf">Neuf</option>
            <option value="Occasion">Occasion</option>
            <option value="Défaut mineur">Défaut mineur</option>
        </select><br><br>
        
        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" min="0" required><br><br>
        
        <label for="item_type">Type d'article:</label><br>
        <select id="item_type" name="item_type" required>
            <option value="Articles rares">Articles rares</option>
            <option value="Articles hautes de gamme">Articles hautes de gamme</option>
            <option value="Articles réguliers">Articles réguliers</option>
        </select><br><br>
        
        <input type="submit" value="Ajouter l'article">
    </form>
</body>
</html>
