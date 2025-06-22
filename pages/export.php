<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';

// Get export type from URL parameter
$export_type = $_GET['type'] ?? '';
$format = $_GET['format'] ?? 'csv';

if (empty($export_type)) {
    header('Location: ../index.php?error=Type d\'export manquant');
    exit;
}

// Set headers for CSV download
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $export_type . '_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// Output UTF-8 BOM for proper Excel compatibility
echo "\xEF\xBB\xBF";

// Create output handle
$output = fopen('php://output', 'w');

try {
    switch ($export_type) {
        case 'factures':
            exportFactures($db, $output);
            break;
        case 'bons_commande':
            exportBonsCommande($db, $output);
            break;
        case 'devis':
            exportDevis($db, $output);
            break;
        case 'produits':
            exportProduits($db, $output);
            break;
        case 'clients':
            exportClients($db, $output);
            break;
        default:
            throw new Exception('Type d\'export non supporté');
    }
} catch (Exception $e) {
    // Handle errors
    header('Location: ../pages/' . $export_type . '.php?error=' . urlencode($e->getMessage()));
    exit;
}

fclose($output);
exit;

function exportFactures($db, $output) {
    // CSV headers
    $headers = [
        'Numéro Facture',
        'Client',
        'Entreprise',
        'Email',
        'Téléphone',
        'Date Émission',
        'Date Échéance',
        'Date Paiement',
        'Montant HT',
        'TVA',
        'Montant TTC',
        'Statut',
        'Mode Paiement',
        'Conditions Paiement',
        'Bon de Commande',
        'Remarques'
    ];
    
    fputcsv($output, $headers, ';');
    
    // Get data
    $stmt = $db->prepare("
        SELECT f.numero, 
               CONCAT(c.prenom, ' ', c.nom) as client_nom,
               c.entreprise, c.email, c.telephone,
               f.date_facture, f.date_echeance, f.date_paiement,
               f.montant_total, f.statut, f.mode_paiement, f.conditions_paiement,
               bc.numero as bon_commande_numero, f.remarques
        FROM factures f
        LEFT JOIN clients c ON f.client_id = c.id
        LEFT JOIN bons_commande bc ON f.bon_commande_id = bc.id
        ORDER BY f.date_facture DESC
    ");
    $stmt->execute();
    
    while ($row = $stmt->fetch()) {
        $montant_ht = $row['montant_total'] / 1.20;
        $tva = $row['montant_total'] - $montant_ht;
        
        $data = [
            $row['numero'],
            $row['client_nom'],
            $row['entreprise'] ?? '',
            $row['email'],
            $row['telephone'],
            date('d/m/Y', strtotime($row['date_facture'])),
            date('d/m/Y', strtotime($row['date_echeance'])),
            $row['date_paiement'] ? date('d/m/Y', strtotime($row['date_paiement'])) : '',
            number_format($montant_ht, 2, ',', ' ') . ' DH',
            number_format($tva, 2, ',', ' ') . ' DH',
            number_format($row['montant_total'], 2, ',', ' ') . ' DH',
            ucfirst($row['statut']),
            ucfirst($row['mode_paiement']),
            ucfirst($row['conditions_paiement']),
            $row['bon_commande_numero'] ?? '',
            $row['remarques'] ?? ''
        ];
        
        fputcsv($output, $data, ';');
    }
}

function exportBonsCommande($db, $output) {
    // CSV headers
    $headers = [
        'Numéro Commande',
        'Client',
        'Entreprise',
        'Email',
        'Téléphone',
        'Date Commande',
        'Date Livraison Prévue',
        'Date Livraison Réelle',
        'Montant HT',
        'TVA',
        'Montant TTC',
        'Statut',
        'Mode Paiement',
        'Adresse Livraison',
        'Remarques'
    ];
    
    fputcsv($output, $headers, ';');
    
    // Get data
    $stmt = $db->prepare("
        SELECT bc.numero,
               CONCAT(c.prenom, ' ', c.nom) as client_nom,
               c.entreprise, c.email, c.telephone,
               bc.date_commande, bc.date_livraison_prevue, bc.date_livraison_reelle,
               bc.montant_total, bc.statut, bc.mode_paiement,
               bc.adresse_livraison, bc.remarques
        FROM bons_commande bc
        LEFT JOIN clients c ON bc.client_id = c.id
        ORDER BY bc.date_commande DESC
    ");
    $stmt->execute();
    
    while ($row = $stmt->fetch()) {
        $montant_ht = $row['montant_total'] / 1.20;
        $tva = $row['montant_total'] - $montant_ht;
        
        $data = [
            $row['numero'],
            $row['client_nom'],
            $row['entreprise'] ?? '',
            $row['email'],
            $row['telephone'],
            date('d/m/Y', strtotime($row['date_commande'])),
            date('d/m/Y', strtotime($row['date_livraison_prevue'])),
            $row['date_livraison_reelle'] ? date('d/m/Y', strtotime($row['date_livraison_reelle'])) : '',
            number_format($montant_ht, 2, ',', ' ') . ' DH',
            number_format($tva, 2, ',', ' ') . ' DH',
            number_format($row['montant_total'], 2, ',', ' ') . ' DH',
            ucfirst($row['statut']),
            ucfirst($row['mode_paiement']),
            $row['adresse_livraison'] ?? '',
            $row['remarques'] ?? ''
        ];
        
        fputcsv($output, $data, ';');
    }
}

function exportDevis($db, $output) {
    // CSV headers
    $headers = [
        'Numéro Devis',
        'Client',
        'Entreprise',
        'Email',
        'Téléphone',
        'Date Devis',
        'Validité (jours)',
        'Date Expiration',
        'Montant HT',
        'TVA',
        'Montant TTC',
        'Statut',
        'Remarques'
    ];
    
    fputcsv($output, $headers, ';');
    
    // Get data
    $stmt = $db->prepare("
        SELECT d.numero,
               CONCAT(c.prenom, ' ', c.nom) as client_nom,
               c.entreprise, c.email, c.telephone,
               d.date_devis, d.validite_jours, d.date_expiration,
               d.montant_total, d.statut, d.remarques
        FROM devis d
        LEFT JOIN clients c ON d.client_id = c.id
        ORDER BY d.date_devis DESC
    ");
    $stmt->execute();
    
    while ($row = $stmt->fetch()) {
        $montant_ht = $row['montant_total'] / 1.20;
        $tva = $row['montant_total'] - $montant_ht;
        
        $data = [
            $row['numero'],
            $row['client_nom'],
            $row['entreprise'] ?? '',
            $row['email'],
            $row['telephone'],
            date('d/m/Y', strtotime($row['date_devis'])),
            $row['validite_jours'],
            date('d/m/Y', strtotime($row['date_expiration'])),
            number_format($montant_ht, 2, ',', ' ') . ' DH',
            number_format($tva, 2, ',', ' ') . ' DH',
            number_format($row['montant_total'], 2, ',', ' ') . ' DH',
            ucfirst($row['statut']),
            $row['remarques'] ?? ''
        ];
        
        fputcsv($output, $data, ';');
    }
}

function exportProduits($db, $output) {
    // CSV headers
    $headers = [
        'Nom Produit',
        'Description',
        'Prix Unitaire',
        'Stock Actuel',
        'Stock Minimum',
        'Statut Stock',
        'Catégorie',
        'Date Création',
        'Dernière Modification'
    ];
    
    fputcsv($output, $headers, ';');
    
    // Get data
    $stmt = $db->prepare("
        SELECT nom, description, prix_unitaire, stock_actuel, stock_minimum,
               CASE 
                   WHEN stock_actuel <= 0 THEN 'Rupture'
                   WHEN stock_actuel <= stock_minimum THEN 'Stock faible'
                   ELSE 'En stock'
               END as statut_stock,
               categorie, date_creation, date_modification
        FROM produits
        ORDER BY nom ASC
    ");
    $stmt->execute();
    
    while ($row = $stmt->fetch()) {
        $data = [
            $row['nom'],
            $row['description'] ?? '',
            number_format($row['prix_unitaire'], 2, ',', ' ') . ' DH',
            $row['stock_actuel'],
            $row['stock_minimum'],
            $row['statut_stock'],
            $row['categorie'] ?? '',
            date('d/m/Y', strtotime($row['date_creation'])),
            $row['date_modification'] ? date('d/m/Y', strtotime($row['date_modification'])) : ''
        ];
        
        fputcsv($output, $data, ';');
    }
}

function exportClients($db, $output) {
    // CSV headers
    $headers = [
        'Prénom',
        'Nom',
        'Entreprise',
        'Email',
        'Téléphone',
        'Adresse',
        'Ville',
        'Code Postal',
        'Statut',
        'Date Création',
        'Dernière Commande',
        'Nombre Total Commandes',
        'Montant Total Facturé'
    ];
    
    fputcsv($output, $headers, ';');
    
    // Get data with statistics
    $stmt = $db->prepare("
        SELECT c.*,
               COUNT(bc.id) as nb_commandes,
               COALESCE(SUM(f.montant_total), 0) as montant_total_facture,
               MAX(bc.date_commande) as derniere_commande
        FROM clients c
        LEFT JOIN bons_commande bc ON c.id = bc.client_id
        LEFT JOIN factures f ON bc.id = f.bon_commande_id
        GROUP BY c.id
        ORDER BY c.nom, c.prenom
    ");
    $stmt->execute();
    
    while ($row = $stmt->fetch()) {
        $data = [
            $row['prenom'],
            $row['nom'],
            $row['entreprise'] ?? '',
            $row['email'],
            $row['telephone'],
            $row['adresse'] ?? '',
            $row['ville'] ?? '',
            $row['code_postal'] ?? '',
            ucfirst($row['statut']),
            date('d/m/Y', strtotime($row['date_creation'])),
            $row['derniere_commande'] ? date('d/m/Y', strtotime($row['derniere_commande'])) : 'Aucune',
            $row['nb_commandes'],
            number_format($row['montant_total_facture'], 2, ',', ' ') . ' DH'
        ];
        
        fputcsv($output, $data, ';');
    }
}
?>