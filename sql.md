DROP TABLE IF EXISTS Articles;
DROP TABLE IF EXISTS Users;

-- Créer la table Sellers
CREATE TABLE Users (
UserID INT AUTO_INCREMENT PRIMARY KEY,
UserName VARCHAR(255) NOT NULL,
Email VARCHAR(255) NOT NULL UNIQUE,
UserPassword VARCHAR(255) NOT NULL,
UserType VARCHAR(255) NOT NULL -- Admin Seller Buyer

);

-- Créer la table Articles
CREATE TABLE Articles (
ArticleID INT AUTO_INCREMENT PRIMARY KEY,        -- Identifiant unique de l'article
ArticleName VARCHAR(255) NOT NULL,               -- Nom de l'article
Description TEXT,                                -- Description de l'article
TypeVente VARCHAR(255) NOT NULL,		     -- Immediat   Enchere   Negotiation
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
INSERT INTO `agora`.`Users` (`UserName`, `Email`, `UserPassword`, `UserType`)
VALUES
('antoine', 'antoine.lallement@edu.ece.fr', 'chaville', 'Admin'),
('jules', 'jules.kounde@edu.ece.fr', 'foot', 'Seller');

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
 