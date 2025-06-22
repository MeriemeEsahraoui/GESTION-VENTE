-- Table des clients
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    telephone VARCHAR(20),
    adresse TEXT,
    ville VARCHAR(100),
    entreprise VARCHAR(100),
    status INT DEFAULT 1,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    description TEXT,
    prix_unitaire DECIMAL(10,2),
    quantite_en_stock INT,
    fournisseur VARCHAR(100),
    seuil_stock INT DEFAULT 0,
    status INT DEFAULT 1,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des devis
CREATE TABLE devis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE,
    client_id INT,
    date_devis DATE,
    validite_jours INT,
    date_expiration DATE,
    statut ENUM('en_attente', 'accepte', 'refuse', 'expire') DEFAULT 'en_attente',
    montant_total DECIMAL(10,2),
    remarques TEXT,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Table des détails du devis
CREATE TABLE details_devis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    devis_id INT,
    produit_id INT,
    nom_produit VARCHAR(255),
    description_produit TEXT,
    quantite INT,
    prix_unitaire DECIMAL(10,2),
    total_ligne DECIMAL(10,2),
    FOREIGN KEY (devis_id) REFERENCES devis(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Table des bons de commande
CREATE TABLE bons_commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE,
    client_id INT,
    devis_id INT NULL,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_livraison_prevue DATE,
    date_livraison_reelle DATE NULL,
    statut ENUM('en_attente', 'confirme', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    mode_paiement ENUM('especes', 'cheque', 'virement', 'carte', 'credit') DEFAULT 'especes',
    adresse_livraison TEXT,
    remarques TEXT,
    montant_total DECIMAL(10,2),
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (devis_id) REFERENCES devis(id)
);

-- Table des détails du bon de commande
CREATE TABLE details_bon_commande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bon_commande_id INT,
    produit_id INT,
    nom_produit VARCHAR(255),
    description_produit TEXT,
    quantite INT,
    prix_unitaire DECIMAL(10,2),
    total_ligne DECIMAL(10,2),
    FOREIGN KEY (bon_commande_id) REFERENCES bons_commande(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Table des factures
CREATE TABLE factures (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE,
    client_id INT,
    bon_commande_id INT NULL,
    date_facture DATE,
    date_echeance DATE,
    date_paiement DATE NULL,
    statut ENUM('brouillon', 'envoyee', 'payee', 'en_retard', 'annulee') DEFAULT 'brouillon',
    conditions_paiement ENUM('comptant', '15_jours', '30_jours', '60_jours', 'fin_mois') DEFAULT '30_jours',
    mode_paiement ENUM('especes', 'cheque', 'virement', 'carte', 'prelevement') DEFAULT 'virement',
    remarques TEXT,
    montant_total DECIMAL(10,2),
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (bon_commande_id) REFERENCES bons_commande(id)
);

-- Table des détails des factures
CREATE TABLE details_facture (
    id INT PRIMARY KEY AUTO_INCREMENT,
    facture_id INT,
    produit_id INT,
    nom_produit VARCHAR(255),
    description_produit TEXT,
    quantite INT,
    prix_unitaire DECIMAL(10,2),
    total_ligne DECIMAL(10,2),
    FOREIGN KEY (facture_id) REFERENCES factures(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);