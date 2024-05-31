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
INSERT INTO agora.Articles (ArticleName, Description, TypeVente, Price, ImageURL, VideoURL, Quality, Stock, UserID, ItemType, CreatedAt, UpdatedAt) 
VALUES 
('Chaise Bois', 'Chaise en bois en jaune, artistique et ergonomique elle conviendra a des cuisines atypiques mais pas aux incomprehensifs face a l art.', 'Immediat', '1599.99', 'images/chaiseboisjaune.png', NULL, 'Neuf', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Table', 'Table en bois massif, avec pied central, ideal pour vos repas en famille.', 'Immediat', '799.90', 'images/tableboismassif.png', NULL, 'Neuf', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lampe Vintage', 'Lampe de chevet vintage avec abat-jour en tissu, parfait pour creer une ambiance retro.', 'Immediat', '120.50', 'images/lampevintage.png', NULL, 'Neuf', '10', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Canape Cuir', 'Canape en cuir noir, 3 places, design moderne et confortable.', 'Immediat', '2999.99', 'images/canapecuir.png', NULL, 'Neuf', '3', '4', 'Mobilier', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Bibliotheque', 'Grande bibliotheque en chene, 5 etageres, ideale pour ranger vos livres et objets de decoration.', 'Immediat', '450.75', 'images/bibliotheque.png', NULL, 'Neuf', '5', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Tapis Persan', 'Tapis persan authentique, fait main, motifs traditionnels, ideal pour un salon elegant.', 'Immediat', '1200.00', 'images/tapispersan.png', NULL, 'Neuf', '2', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lustre Cristal', 'Lustre en cristal, 8 branches, parfait pour une salle a manger luxueuse.', 'Immediat', '850.00', 'images/lustrecristal.png', NULL, 'Neuf', '4', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Chaise de Bureau', 'Chaise de bureau ergonomique, avec support lombaire et accoudoirs ajustables.', 'Immediat', '250.00', 'images/chaisebureau.png', NULL, 'Neuf', '15', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Table Basse', 'Table basse en verre trempe avec pieds en metal, design moderne.', 'Immediat', '299.99', 'images/tablebasse.png', NULL, 'Neuf', '8', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lit Double', 'Lit double en bois massif avec tete de lit capitonnee.', 'Immediat', '1899.99', 'images/litdouble.png', NULL, 'Neuf', '3', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Montre Rolex', 'Montre Rolex Submariner en acier inoxydable, etanche et elegante.', 'Immediat', '7500.00', 'images/montre_rolex.png', NULL, 'Neuf', '1', '5', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Console de Jeux', 'Console de jeux video derniere generation avec 2 manettes incluses.', 'Immediat', '499.99', 'images/console_jeux.png', NULL, 'Neuf', '20', '3', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Camera Reflex', 'Camera Reflex numerique avec objectif 24-70mm, parfait pour les photographes professionnels.', 'Immediat', '2500.00', 'images/camera_reflex.png', NULL, 'Neuf', '5', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Smartphone', 'Smartphone dernier modele avec 128GB de memoire interne.', 'Immediat', '999.99', 'images/smartphone.png', NULL, 'Neuf', '50', '4', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Bicyclette de Course', 'Bicyclette de course legere avec cadre en carbone, ideale pour les competitions.', 'Immediat', '1200.00', 'images/bicyclette_course.png', NULL, 'Neuf', '10', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Ecouteurs Sans Fil', 'Ecouteurs sans fil avec reduction de bruit active, autonomie de 20 heures.', 'Immediat', '199.99', 'images/ecouteurs_sansfil.png', NULL, 'Neuf', '30', '3', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Drone', 'Drone avec camera 4K et stabilisation d image, parfait pour les prises de vue aeriennes.', 'Immediat', '899.99', 'images/drone.png', NULL, 'Neuf', '15', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Ordinateur Portable', 'Ordinateur portable ultra-fin avec ecran Retina et 512GB de SSD.', 'Immediat', '1500.00', 'images/ordinateur_portable.png', NULL, 'Neuf', '25', '3', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Appareil Photo Instantane', 'Appareil photo instantane avec pellicule couleur, parfait pour les souvenirs instantanes.', 'Immediat', '120.00', 'images/appareil_photo_instantane.png', NULL, 'Neuf', '40', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Casque de Realite Virtuelle', 'Casque de realite virtuelle avec controleurs, immersion totale garantie.', 'Immediat', '399.99', 'images/casque_vr.png', NULL, 'Neuf', '10', '4', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Enceinte Bluetooth', 'Enceinte Bluetooth portable avec son stereo et autonomie de 12 heures.', 'Immediat', '150.00', 'images/enceinte_bluetooth.png', NULL, 'Neuf', '50', '3', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Guitare Electrique', 'Guitare electrique avec ampli inclus, ideale pour les musiciens.', 'Immediat', '800.00', 'images/guitare_electrique.png', NULL, 'Neuf', '8', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Lunettes de Soleil', 'Lunettes de soleil de marque avec protection UV 400.', 'Immediat', '120.00', 'images/lunettes_soleil.png', NULL, 'Neuf', '100', '4', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Cafetiere Expresso', 'Cafetiere expresso automatique avec mousseur a lait integre.', 'Immediat', '299.99', 'images/cafetiere_expresso.png', NULL, 'Neuf', '25', '3', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:29'),
('Voiture Electrique', 'Voiture electrique compacte avec une autonomie de 300 km.', 'Negociation', '19999.99', 'images/voiture_electrique.png', NULL, 'Neuf', '5', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Velo de Montagne', 'Velo de montagne tout-terrain avec suspension complete.', 'Negociation', '799.99', 'images/velo_montagne.png', NULL, 'Neuf', '10', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Ordinateur Gaming', 'Ordinateur gaming avec processeur Intel i9 et carte graphique RTX 3080.', 'Enchere', '2499.99', 'images/ordinateur_gaming.png', NULL, 'Occasion', '8', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Piano a Queue', 'Piano a queue Yamaha, ideal pour les musiciens professionnels.', 'Negociation', '8999.99', 'images/piano_queue.png', NULL, 'Occasion', '2', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Collection de Timbres', 'Collection de timbres rares du 19eme siecle.', 'Enchere', '1499.99', 'images/collection_timbres.png', NULL, 'Occasion', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Montre de Luxe', 'Montre de luxe Patek Philippe, en or rose.', 'Negociation', '25000.00', 'images/montre_luxe.png', NULL, 'Neuf', '3', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Yacht de Luxe', 'Yacht de luxe avec 5 cabines et une piscine.', 'Negociation', '1500000.00', 'images/yacht_luxe.png', NULL, 'Neuf', '1', '2', 'Enchere', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Bague en Diamant', 'Bague en diamant taille princesse de 2 carats.', 'Negociation', '7500.00', 'images/bague_diamant.png', NULL, 'Neuf', '5', '2', 'Articles reguliers', '2024-05-20 06:19:21', '2024-05-21 05:21:27'),
('Sculpture en Bronze', 'Sculpture en bronze representant une scene mythologique.', 'Enchere', '4500.00', 'images/sculpture_bronze.png', NULL, 'Occasion', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27');


 
('Tableau Le Pont Japonnais de Claude Monet', 'Tableau mythique de Claude Monet: Le Pont Japonnais', 'Enchere', '2 000 000', 'images/le_pont_japonnais.jpg', NULL, 'Occasion', '1', '2', 'Articles rares', '2024-05-20 06:19:21', '2024-05-21 05:21:27');
 
 CREATE TABLE Enchere (
    EnchereID INT AUTO_INCREMENT PRIMARY KEY,         -- Identifiant unique de l'enchère
    ArticleID INT NOT NULL,                           -- Référence à l'article
    UserID INT NOT NULL,                              -- Référence à l'utilisateur qui place une enchère
    BidAmount DECIMAL(10, 2) NOT NULL,                -- Montant de l'enchère
    BidTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,      -- Heure de l'enchère
    StartingPrice DECIMAL(10, 2) NOT NULL,            -- Prix de départ de l'enchère
    WinningBid DECIMAL(10, 2),                        -- Montant final gagnant (mis à jour lorsque l'enchère est terminée)
    WinnerID INT,                                     -- Utilisateur qui a remporté l'enchère (mis à jour lorsque l'enchère est terminée)
    Description TEXT,                                 -- Description de l'article
    ImageURL VARCHAR(255),                            -- URL de l'image de l'article
    VideoURL VARCHAR(255),                            -- URL de la vidéo de l'article
    Quality ENUM('Neuf', 'Occasion', 'Défaut mineur') DEFAULT 'Neuf',  -- Qualité de l'article
    ItemType ENUM('Articles rares', 'Articles hautes de gamme', 'Articles réguliers') NOT NULL, -- Type d'article
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    -- Date de création de l'enregistrement
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour de l'enregistrement
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (WinnerID) REFERENCES Users(UserID)
);


INSERT INTO Users (UserName, Email, UserPassword, UserType, UserImageURL) VALUES
('Alice', 'alice@example.com', 'password1', 'seller', 'images/user1.jpg'),
('Bob', 'bob@example.com', 'password2', 'buyer', 'images/user2.jpg'),
('Charlie', 'charlie@example.com', 'password3', 'seller', 'images/user3.jpg'),
('David', 'david@example.com', 'password4', 'buyer', 'images/user4.jpg'),
('Eve', 'eve@example.com', 'password5', 'seller', 'images/user5.jpg'),
('Frank', 'frank@example.com', 'password6', 'buyer', 'images/user6.jpg'),
('Grace', 'grace@example.com', 'password7', 'admin', 'images/user7.jpg'),
('Heidi', 'heidi@example.com', 'password8', 'seller', 'images/user8.jpg'),
('Ivan', 'ivan@example.com', 'password9', 'buyer', 'images/user9.jpg'),
('Judy', 'judy@example.com', 'password10', 'admin', 'images/user10.jpg');


-- Supprimer les tables existantes si elles existent
DROP TABLE IF EXISTS Panier;
DROP TABLE IF EXISTS PanierArticles;

-- Créer la table Panier
CREATE TABLE Panier (
    PanierID INT AUTO_INCREMENT PRIMARY KEY,        -- Identifiant unique du panier
    UserID INT,                                     -- Identifiant de l'utilisateur qui possède le panier
    DateCreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date et heure de création du panier
    Status ENUM('En cours', 'Validé', 'Annulé') DEFAULT 'En cours',  -- Statut du panier
    FOREIGN KEY (UserID) REFERENCES Users(UserID)   -- Clé étrangère vers la table Users
);

-- Créer la table PanierArticles pour lier les articles au panier
CREATE TABLE PanierArticles (
    PanierArticleID INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique de l'association panier-article
    PanierID INT,                                    -- Identifiant du panier
    ArticleID INT,                                   -- Identifiant de l'article
    Quantity INT NOT NULL DEFAULT 1,                 -- Quantité de l'article dans le panier
    FOREIGN KEY (PanierID) REFERENCES Panier(PanierID) ON DELETE CASCADE,  -- Clé étrangère vers la table Panier
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID) ON DELETE CASCADE  -- Clé étrangère vers la table Articles
);

CREATE TABLE Negociations (
    NegociationID INT AUTO_INCREMENT PRIMARY KEY,
    ArticleID INT,
    UserID INT,
    ProposedPrice DECIMAL(10, 2),
    Status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
