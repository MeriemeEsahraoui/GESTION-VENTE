<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $is_edit = isset($_POST['devis_id']) && !empty($_POST['devis_id']);
        
        // Validate required fields
        if (empty($_POST['client_id'])) {
            throw new Exception('Client requis');
        }
        
        if (empty($_POST['products']) || !is_array($_POST['products'])) {
            throw new Exception('Au moins un produit requis');
        }
        
        // Sanitize input data
        $client_id = intval($_POST['client_id']);
        $date_devis = $_POST['date_devis'];
        $validite_jours = intval($_POST['validite_jours']);
        $statut = $_POST['statut'] ?? 'en_attente';
        $remarques = $_POST['remarque'] ?? '';
        
        // Calculate expiration date
        $date_expiration = date('Y-m-d', strtotime($date_devis . ' + ' . $validite_jours . ' days'));
        
        // Calculate total amount
        $montant_total = 0;
        foreach ($_POST['products'] as $product) {
            if (isset($product['prix_unitaire']) && isset($product['quantite'])) {
                $montant_total += floatval($product['prix_unitaire']) * intval($product['quantite']);
            }
        }
        
        $db->beginTransaction();
        
        if ($is_edit) {
            // Update existing devis
            $devis_id = intval($_POST['devis_id']);
            
            $stmt = $db->prepare("UPDATE devis SET 
                client_id = ?, 
                date_devis = ?, 
                validite_jours = ?, 
                date_expiration = ?, 
                statut = ?, 
                montant_total = ?, 
                remarques = ? 
                WHERE id = ?");
            
            $stmt->execute([
                $client_id, 
                $date_devis, 
                $validite_jours, 
                $date_expiration, 
                $statut, 
                $montant_total, 
                $remarques, 
                $devis_id
            ]);
            
            // Delete existing details
            $stmt = $db->prepare("DELETE FROM details_devis WHERE devis_id = ?");
            $stmt->execute([$devis_id]);
            
            $response['message'] = 'Devis modifié avec succès';
            
        } else {
            // Create new devis
            // Generate unique numero
            $current_year = date('Y');
            $stmt = $db->prepare("SELECT COUNT(*) + 1 as next_number FROM devis WHERE YEAR(cree_le) = ?");
            $stmt->execute([$current_year]);
            $next_number = $stmt->fetch()['next_number'];
            $numero = 'DEV-' . $current_year . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
            
            $stmt = $db->prepare("INSERT INTO devis (numero, client_id, date_devis, validite_jours, date_expiration, statut, montant_total, remarques) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $numero, 
                $client_id, 
                $date_devis, 
                $validite_jours, 
                $date_expiration, 
                $statut, 
                $montant_total, 
                $remarques
            ]);
            
            $devis_id = $db->lastInsertId();
            $response['message'] = 'Devis créé avec succès';
        }
        
        // Insert/Update product details
        $stmt = $db->prepare("INSERT INTO details_devis (devis_id, produit_id, nom_produit, description_produit, quantite, prix_unitaire, total_ligne) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
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
                    $devis_id, 
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
        $response['devis_id'] = $devis_id;
        
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
    header('Location: devis_detail.php?id=' . $response['devis_id'] . '&success=' . urlencode($response['message']));
} else {
    header('Location: _devis.php?error=' . urlencode($response['message']));
}
exit;
?>