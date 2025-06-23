<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $is_edit = isset($_POST['facture_id']) && !empty($_POST['facture_id']);
        
        // Validate required fields
        if (empty($_POST['client_id'])) {
            throw new Exception('Client requis');
        }
        
        if (empty($_POST['products']) || !is_array($_POST['products'])) {
            throw new Exception('Au moins un produit requis');
        }
        
        // Sanitize input data
        $client_id = intval($_POST['client_id']);
        $bon_commande_id = !empty($_POST['bon_commande_id']) ? intval($_POST['bon_commande_id']) : null;
        $date_facture = $_POST['date_facture'];
        $date_echeance = $_POST['date_echeance'];
        $date_paiement = !empty($_POST['date_paiement']) ? $_POST['date_paiement'] : null;
        $statut = $_POST['statut'] ?? 'brouillon';
        $conditions_paiement = $_POST['conditions_paiement'] ?? '30_jours';
        $mode_paiement = $_POST['mode_paiement'] ?? 'virement';
        $remarques = $_POST['remarques'] ?? '';
        
        // Calculate total amount
        $montant_total = 0;
        foreach ($_POST['products'] as $product) {
            if (isset($product['prix_unitaire']) && isset($product['quantite'])) {
                $montant_total += floatval($product['prix_unitaire']) * intval($product['quantite']);
            }
        }
        
        $db->beginTransaction();
        
        if ($is_edit) {
            // Update existing facture
            $facture_id = intval($_POST['facture_id']);
            
            $stmt = $db->prepare("UPDATE factures SET 
                client_id = ?, 
                bon_commande_id = ?,
                date_facture = ?, 
                date_echeance = ?, 
                date_paiement = ?, 
                statut = ?, 
                conditions_paiement = ?,
                mode_paiement = ?,
                remarques = ?,
                montant_total = ?
                WHERE id = ?");
            
            $stmt->execute([
                $client_id, 
                $bon_commande_id,
                $date_facture, 
                $date_echeance, 
                $date_paiement, 
                $statut, 
                $conditions_paiement,
                $mode_paiement,
                $remarques,
                $montant_total, 
                $facture_id
            ]);
            
            // Delete existing details
            $stmt = $db->prepare("DELETE FROM details_facture WHERE facture_id = ?");
            $stmt->execute([$facture_id]);
            
            $response['message'] = 'Facture modifiée avec succès';
            
        } else {
            // Create new facture
            // Generate unique numero
            $current_year = date('Y');
            $stmt = $db->prepare("SELECT COUNT(*) + 1 as next_number FROM factures WHERE YEAR(cree_le) = ?");
            $stmt->execute([$current_year]);
            $next_number = $stmt->fetch()['next_number'];
            $numero = 'FAC-' . $current_year . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
            
            $stmt = $db->prepare("INSERT INTO factures (numero, client_id, bon_commande_id, date_facture, date_echeance, date_paiement, statut, conditions_paiement, mode_paiement, remarques, montant_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $numero, 
                $client_id, 
                $bon_commande_id,
                $date_facture, 
                $date_echeance, 
                $date_paiement, 
                $statut, 
                $conditions_paiement,
                $mode_paiement,
                $remarques,
                $montant_total
            ]);
            
            $facture_id = $db->lastInsertId();
            $response['message'] = 'Facture créée avec succès';
        }
        
        // Insert/Update product details
        $stmt = $db->prepare("INSERT INTO details_facture (facture_id, produit_id, nom_produit, description_produit, quantite, prix_unitaire, total_ligne) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($_POST['products'] as $product) {
            if (isset($product['product_id']) && isset($product['prix_unitaire']) && isset($product['quantite'])) {
                $produit_id = intval($product['product_id']);
                $prix_unitaire = floatval($product['prix_unitaire']);
                $quantite = intval($product['quantite']);
                $total_ligne = $prix_unitaire * $quantite;
                
                // Get product details
                $prod_stmt = $db->prepare("SELECT nom, description FROM produits WHERE id = ?");
                $prod_stmt->execute([$produit_id]);
                $prod_data = $prod_stmt->fetch();
                
                $nom_produit = $prod_data ? $prod_data['nom'] : 'Produit inconnu';
                $description_produit = $prod_data ? $prod_data['description'] : '';
                
                $stmt->execute([
                    $facture_id, 
                    $produit_id, 
                    $nom_produit, 
                    $description_produit, 
                    $quantite, 
                    $prix_unitaire, 
                    $total_ligne
                ]);
            }
        }
        
        $db->commit();
        $response['success'] = true;
        $response['facture_id'] = $facture_id;
        
    } else {
        throw new Exception('Méthode non autorisée');
    }
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollback();
    }
    $response['message'] = $e->getMessage();
}

// Handle AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle regular form submissions
if ($response['success']) {
    header('Location: facture_detail.php?id=' . $response['facture_id'] . '&success=' . urlencode($response['message']));
} else {
    header('Location: facture.php?error=' . urlencode($response['message']));
}
exit;
?>