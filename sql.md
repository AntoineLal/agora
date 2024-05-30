DROP TABLE IF EXISTS Articles;
DROP TABLE IF EXISTS Users;

-- Créer la table Users
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    UserPassword VARCHAR(255) NOT NULL,
    UserType VARCHAR(50) NOT NULL, -- Admin Seller Buyer
    UserImageURL VARCHAR(255) -- Ajout de la colonne pour l'image de l'utilisateur
);

-- Créer la table Articles
CREATE TABLE Articles (
    ArticleID INT AUTO_INCREMENT PRIMARY KEY,        -- Identifiant unique de l'article
    ArticleName VARCHAR(100) NOT NULL,               -- Nom de l'article
    Description TEXT,                                -- Description de l'article
    TypeVente VARCHAR(20) NOT NULL,                  -- Immediat   Enchere   Negotiation 
    Price DECIMAL(10, 2) NOT NULL,                   -- Prix de l'article
    ImageURL VARCHAR(255),                           -- Image correspondante
    VideoURL VARCHAR(255),
    Quality ENUM('Neuf', 'Occasion', 'Défaut mineur') DEFAULT 'Neuf',
    Stock INT DEFAULT 1,                             -- Stock 
    UserID INT,
    ItemType ENUM('Articles rares', 'Articles hautes de gamme', 'Articles réguliers') NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

INSERT INTO agora.Articles (ArticleName, Description, TypeVente, Price, ImageURL, VideoURL, Quality, Stock, UserID, ItemType, CreatedAt, UpdatedAt) 
VALUES 
('Chaise Bois', 'Chaise en bois en jaune, artistique et ergonomique elle conviendra a des cuisines atypiques mais pas aux incomprehensifs face a l''art.', 'Immediat', '1599.99', 'images/chaiseboisjaune.png', NULL, 'Neuf', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Table', 'Table en bois massif, avec pied central, idéal pour vos repas en famille.', 'Immediat', '799.90', 'images/tableboismassif.png', NULL, 'Neuf', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lampe Vintage', 'Lampe de chevet vintage avec abat-jour en tissu, parfait pour créer une ambiance rétro.', 'Immediat', '120.50', 'images/lampevintage.png', NULL, 'Neuf', '10', '2', 'Articles réguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Canapé Cuir', 'Canapé en cuir noir, 3 places, design moderne et confortable.', 'Immediat', '2999.99', 'images/canapecuir.png', NULL, 'Neuf', '3', '4', 'Mobilier', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Bibliothèque', 'Grande bibliothèque en chêne, 5 étagères, idéale pour ranger vos livres et objets de décoration.', 'Immediat', '450.75', 'images/bibliotheque.png', NULL, 'Neuf', '5', '2', 'Articles réguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Tapis Persan', 'Tapis persan authentique, fait main, motifs traditionnels, idéal pour un salon élégant.', 'Immediat', '1200.00', 'images/tapispersan.png', NULL, 'Neuf', '2', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lustre Cristal', 'Lustre en cristal, 8 branches, parfait pour une salle à manger luxueuse.', 'Immediat', '850.00', 'images/lustrecristal.png', NULL, 'Neuf', '4', '2', 'Articles réguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Chaise de Bureau', 'Chaise de bureau ergonomique, avec support lombaire et accoudoirs ajustables.', 'Immediat', '250.00', 'images/chaisebureau.png', NULL, 'Neuf', '15', '2', 'Articles réguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Table Basse', 'Table basse en verre trempé avec pieds en métal, design moderne.', 'Immediat', '299.99', 'images/tablebasse.png', NULL, 'Neuf', '8', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lit Double', 'Lit double en bois massif avec tête de lit capitonnée.', 'Immediat', '1899.99', 'images/litdouble.png', NULL, 'Neuf', '3', '2', 'Articles réguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Tableau Le Pont Japonnais de Claude Monet', 'Tableau mythique de Claude Monet: Le Pont Japonnais', 'Enchere', '1599.99', 'images/le_pont_japonnais.jpg', NULL, 'Occasion', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27');
 
 -- Insérer 10 utilisateurs dans la table Users
INSERT INTO Users (UserName, Email, UserPassword, UserType, UserImageURL) VALUES
('Alice', 'alice@example.com', 'password1', 'Seller', 'images/user1.jpg'),
('Bob', 'bob@example.com', 'password2', 'Buyer', 'images/user2.jpg'),
('Charlie', 'charlie@example.com', 'password3', 'Seller', 'images/user3.jpg'),
('David', 'david@example.com', 'password4', 'Buyer', 'images/user4.jpg'),
('Eve', 'eve@example.com', 'password5', 'Seller', 'images/user5.jpg'),
('Frank', 'frank@example.com', 'password6', 'Buyer', 'images/user6.jpg'),
('Grace', 'grace@example.com', 'password7', 'Admin', 'images/user7.jpg'),
('Heidi', 'heidi@example.com', 'password8', 'Seller', 'images/user8.jpg'),
('Ivan', 'ivan@example.com', 'password9', 'Buyer', 'images/user9.jpg'),
('Judy', 'judy@example.com', 'password10', 'Admin', 'images/user10.jpg');
