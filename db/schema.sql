-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    rôle ENUM('admin', 'vendeur') NOT NULL,
    créé_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des clients
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    email VARCHAR(100),
    téléphone VARCHAR(20),
    adresse TEXT,
    créé_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    description TEXT,
    prix_unitaire DECIMAL(10,2),
    quantité_en_stock INT,
    créé_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des devis
CREATE TABLE devis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    utilisateur_id INT,
    date_devis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    validité_jours INT,
    statut ENUM('en_attente', 'accepté', 'refusé', 'expiré'),
    montant_total DECIMAL(10,2),
    remarque TEXT,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table des articles du devis
CREATE TABLE articles_devis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    devis_id INT,
    produit_id INT,
    quantité INT,
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (devis_id) REFERENCES devis(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Table des bons de commande
CREATE TABLE bons_commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    devis_id INT NULL,
    utilisateur_id INT,
    date_bon TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'validé', 'livré', 'annulé'),
    montant_total DECIMAL(10,2),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (devis_id) REFERENCES devis(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table des articles du bon de commande
CREATE TABLE articles_bon_commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bon_id INT,
    produit_id INT,
    quantité INT,
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (bon_id) REFERENCES bons_commande(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Table des commandes (représente la vente finale validée)
CREATE TABLE commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bon_id INT,
    client_id INT,
    utilisateur_id INT,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    montant_total DECIMAL(10,2),
    statut ENUM('en_attente', 'payée', 'annulée'),
    FOREIGN KEY (bon_id) REFERENCES bons_commande(id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table des détails de la commande
CREATE TABLE articles_commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commande_id INT,
    produit_id INT,
    quantité INT,
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);
