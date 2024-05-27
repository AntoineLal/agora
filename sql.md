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

INSERT INTO `agora`.`Articles` (`ArticleName`, `Description`, `TypeVente`, `Price`, `ImageURL`, `VideoURL`, `Quality`, `Stock`, `UserID`, `ItemType`, `CreatedAt`, `UpdatedAt`)
VALUES
('Chaise Bois', 'Chaise en bois en jaune, artistique et ergonomique elle conviendra a des cuisines atypiques mais pas aux incomprehensifs face a l''art.', 'Immediat', '1599.99', 'images/chaiseboisjaune.png', NULL, 'Neuf', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27');
