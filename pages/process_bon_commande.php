<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $is_edit = isset($_POST['commande_id']) && !empty($_POST['commande_id']);
        
        // Validate required fields
        if (empty($_POST['client_id'])) {
            throw new Exception('Client requis');
        }
        
        if (empty($_POST['products']) || !is_array($_POST['products'])) {
            throw new Exception('Au moins un produit requis');
        }
        
        // Sanitize input data
        $client_id = intval($_POST['client_id']);
        $devis_id = !empty($_POST['devis_id']) ? intval($_POST['devis_id']) : null;
        $date_livraison_prevue = $_POST['date_livraison_prevue'];
        $date_livraison_reelle = !empty($_POST['date_livraison_reelle']) ? $_POST['date_livraison_reelle'] : null;
        $statut = $_POST['statut'] ?? 'en_attente';
        $mode_paiement = $_POST['mode_paiement'] ?? 'especes';
        $adresse_livraison = $_POST['adresse_livraison'] ?? '';
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
            // Update existing bon de commande
            $commande_id = intval($_POST['commande_id']);
            
            $stmt = $db->prepare("UPDATE bons_commande SET 
                client_id = ?, 
                devis_id = ?,
                date_livraison_prevue = ?, 
                date_livraison_reelle = ?, 
                statut = ?, 
                mode_paiement = ?,
                adresse_livraison = ?,
                remarques = ?,
                montant_total = ?
                WHERE id = ?");
            
            $stmt->execute([
                $client_id, 
                $devis_id,
                $date_livraison_prevue, 
                $date_livraison_reelle, 
                $statut, 
                $mode_paiement,
                $adresse_livraison,
                $remarques,
                $montant_total, 
                $commande_id
            ]);
            
            // Delete existing details
            $stmt = $db->prepare("DELETE FROM details_bon_commande WHERE bon_commande_id = ?");
            $stmt->execute([$commande_id]);
            
            $response['message'] = 'Bon de commande modifié avec succès';
            
        } else {
            // Create new bon de commande
            // Generate unique numero
            $current_year = date('Y');
            $stmt = $db->prepare("SELECT COUNT(*) + 1 as next_number FROM bons_commande WHERE YEAR(cree_le) = ?");
            $stmt->execute([$current_year]);
            $next_number = $stmt->fetch()['next_number'];
            $numero = 'CMD-' . $current_year . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
            
            $stmt = $db->prepare("INSERT INTO bons_commande (numero, client_id, devis_id, date_livraison_prevue, date_livraison_reelle, statut, mode_paiement, adresse_livraison, remarques, montant_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $numero, 
                $client_id, 
                $devis_id,
                $date_livraison_prevue, 
                $date_livraison_reelle, 
                $statut, 
                $mode_paiement,
                $adresse_livraison,
                $remarques,
                $montant_total
            ]);
            
            $commande_id = $db->lastInsertId();
            $response['message'] = 'Bon de commande créé avec succès';
        }
        
        // Insert/Update product details
        $stmt = $db->prepare("INSERT INTO details_bon_commande (bon_commande_id, produit_id, nom_produit, description_produit, quantite, prix_unitaire, total_ligne) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
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
                    $commande_id, 
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
        $response['commande_id'] = $commande_id;
        
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
    header('Location: bon_commande_detail.php?id=' . $response['commande_id'] . '&success=' . urlencode($response['message']));
} else {
    header('Location: bon_commande.php?error=' . urlencode($response['message']));
}
exit;
?>