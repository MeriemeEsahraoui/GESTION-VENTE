<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';

$isPrintMode = isset($_GET['print']) && $_GET['print'] == '1';
$devisId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$devisId) {
    header('Location: devis.php?error=ID de devis manquant');
    exit;
}

// Load devis data from database
try {
    // Get devis with client information
    $stmt = $db->prepare("
        SELECT d.*, 
               c.nom as client_nom, c.prenom as client_prenom, c.email as client_email, 
               c.telephone as client_telephone, c.adresse as client_adresse, 
               c.ville as client_ville, c.entreprise as client_entreprise
        FROM devis d 
        LEFT JOIN clients c ON d.client_id = c.id 
        WHERE d.id = ?
    ");
    $stmt->execute([$devisId]);
    $devis_data = $stmt->fetch();
    
    if (!$devis_data) {
        header('Location: devis.php?error=Devis non trouvé');
        exit;
    }
    
    // Get devis details (articles)
    $stmt = $db->prepare("
        SELECT dd.*, p.nom as produit_nom_db, p.description as produit_description_db
        FROM details_devis dd
        LEFT JOIN produits p ON dd.produit_id = p.id
        WHERE dd.devis_id = ?
        ORDER BY dd.id
    ");
    $stmt->execute([$devisId]);
    $devis_details = $stmt->fetchAll();
    
    // Format data for template compatibility
    $devis = [
        'id' => $devis_data['id'],
        'numero' => $devis_data['numero'],
        'date_devis' => $devis_data['date_devis'],
        'validite_jours' => $devis_data['validite_jours'],
        'date_expiration' => $devis_data['date_expiration'],
        'statut' => $devis_data['statut'],
        'montant_total' => $devis_data['montant_total'],
        'remarque' => $devis_data['remarques'],
        'client' => [
            'nom' => $devis_data['client_prenom'] . ' ' . $devis_data['client_nom'],
            'entreprise' => $devis_data['client_entreprise'],
            'email' => $devis_data['client_email'],
            'telephone' => $devis_data['client_telephone'],
            'adresse' => $devis_data['client_adresse'],
            'ville' => $devis_data['client_ville']
        ],
        'vendeur' => [
            'nom' => 'Système de Gestion',
            'email' => 'admin@gestion-vente.ma',
            'telephone' => '+212 5 22 00 00 00'
        ],
        'articles' => []
    ];
    
    // Format articles
    foreach ($devis_details as $detail) {
        $devis['articles'][] = [
            'nom' => $detail['nom_produit'],
            'description' => $detail['description_produit'],
            'quantite' => $detail['quantite'],
            'prix_unitaire' => $detail['prix_unitaire'],
            'total' => $detail['total_ligne']
        ];
    }
    
} catch(PDOException $e) {
    if (!$isPrintMode) {
        header('Location: devis.php?error=Erreur lors du chargement du devis');
        exit;
    } else {
        die('Erreur lors du chargement du devis');
    }
}

// Calculate totals
$totalHT = 0;
foreach ($devis['articles'] as $article) {
    $totalHT += $article['total'];
}
$totalTVA = $totalHT * 0.20;
$totalTTC = $totalHT + $totalTVA;

// Handle success message
$success_message = $_GET['success'] ?? '';

if (!$isPrintMode) {
    include_once '../includes/head.php';
    include_once '../includes/header.php';
}
?>

<?php if ($isPrintMode): ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis <?php echo $devis['numero']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "SF Pro Display", "Segoe UI", system-ui, -apple-system, sans-serif;
            color: #000000;
        }
        
        /* Apply black and white styling to both screen and print */
        .text-primary, .text-success, .text-danger, .text-warning, .text-info, .text-muted {
            color: #000000 !important;
        }
        .bg-success, .bg-danger, .bg-warning, .bg-info, .bg-secondary, .bg-light {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }
        .badge {
            background-color: #ffffff !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
        }
        .table-dark thead th {
            background-color: #000000 !important;
            color: #ffffff !important;
            border-color: #000000 !important;
        }
        .table-bordered {
            border-color: #000000 !important;
        }
        .table-bordered th, .table-bordered td {
            border-color: #000000 !important;
        }
        .border {
            border-color: #000000 !important;
        }
        .card {
            border-color: #000000 !important;
        }
        .alert-success {
            background-color: #ffffff !important;
            border-color: #000000 !important;
            color: #000000 !important;
        }
        .alert-danger {
            background-color: #ffffff !important;
            border-color: #000000 !important;
            color: #000000 !important;
        }
        .alert-warning {
            background-color: #ffffff !important;
            border-color: #000000 !important;
            color: #000000 !important;
        }
        .alert-info {
            background-color: #ffffff !important;
            border-color: #000000 !important;
            color: #000000 !important;
        }
        
        @media print {
            .no-print { display: none !important; }
            .print-break { page-break-after: always; }
            
            /* Hide all icons in print mode */
            i.fas, i.fa { display: none !important; }
            
            /* Single page optimization */
            @page {
                margin: 12mm 8mm;
                size: A4;
            }
            
            body { 
                font-size: 11px;
                line-height: 1.3;
                font-family: "Arial", "Helvetica", sans-serif;
                color: #000000;
                margin: 0;
                padding: 0;
                background: white;
            }
            
            .table { 
                font-size: 10px;
                line-height: 1.2;
                color: #000000;
                margin-bottom: 12px;
                border-collapse: collapse;
                width: 100%;
            }
            
            .table th, .table td {
                padding: 6px 8px;
                vertical-align: top;
                border: 1px solid #000000;
            }
            
            .table th {
                font-weight: bold;
                text-align: center;
                background-color: #f5f5f5 !important;
            }
            
            h1, h2, h3, h4, h5, h6 {
                margin-top: 12px;
                margin-bottom: 8px;
                line-height: 1.2;
                font-weight: bold;
            }
            
            h1 { font-size: 18px; }
            h2 { font-size: 16px; }
            h3 { font-size: 14px; }
            h4, h5, h6 { font-size: 12px; }
            
            .row {
                margin-bottom: 12px;
            }
            
            .mb-4, .mb-3, .mb-2 {
                margin-bottom: 10px !important;
            }
            
            .mt-5, .mt-4 {
                margin-top: 15px !important;
            }
            
            .p-4 {
                padding: 12px !important;
            }
            
            .border.p-3 {
                padding: 10px !important;
                border: 1.5px solid #000000 !important;
            }
            
            .card-body {
                padding: 10px !important;
            }
            
            small, .small {
                font-size: 9px;
                line-height: 1.2;
            }
            
            .alert {
                display: none !important;
            }
            
            .badge {
                padding: 4px 8px;
                border: 1px solid #000000;
                font-size: 10px;
                font-weight: bold;
            }
            
            .text-center { text-align: center; }
            .text-end { text-align: right; }
            .fw-bold { font-weight: bold; }
            
            /* Professional spacing */
            .border {
                border: 1px solid #000000 !important;
            }
            
            .rounded {
                border-radius: 0 !important;
            }
            
            /* Company header enhancement */
            .company-header {
                text-align: center;
                padding: 20px;
                margin-bottom: 20px;
                border: 2px solid #000000;
                background-color: white !important;
            }
            
            .company-header h2 {
                font-size: 20px;
                font-weight: bold;
                margin: 0;
                letter-spacing: 2px;
            }
            
            .company-header h3 {
                font-size: 16px;
                margin: 5px 0 0 0;
                font-weight: normal;
            }
            
            /* Signature area enhancement */
            .signature-area {
                border: 1px solid #000000;
                padding: 15px;
                text-align: center;
                min-height: 80px;
            }
            
            /* Remove shadows and effects */
            .shadow-sm { box-shadow: none !important; }
            .card { box-shadow: none !important; }
            
            * {
                color: #000000 !important;
                background-color: transparent !important;
            }
        }
        
        .company-header {
            background: #ffffff;
            color: #000000;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #000000;
            font-family: "SF Pro Display", "Segoe UI", system-ui, -apple-system, sans-serif;
        }
        
        @media print {
            .company-header {
                padding: 10px;
                margin-bottom: 8px;
                font-size: 11px;
            }
            
            .company-header h2 {
                font-size: 14px;
                margin: 0;
            }
            
            .company-header h3 {
                font-size: 12px;
                margin: 2px 0 0 0;
            }
        }
    </style>
</head>
<body>
<?php endif; ?>

<?php if (!$isPrintMode): ?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Détail du Devis <?php echo $devis['numero']; ?></h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container-fluid">
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Action Bar -->
            <div class="row mb-4 no-print">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">Devis <?php echo $devis['numero']; ?></h5>
                                    <small class="text-muted">Créé le <?php echo date('d/m/Y', strtotime($devis['date_devis'])); ?></small>
                                </div>
                                <div class="btn-group">
                                    <a href="devis.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Retour
                                    </a>
                                    <a href="_devis.php?id=<?php echo $devis['id']; ?>" class="btn btn-outline-warning">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    <a href="?id=<?php echo $devis['id']; ?>&print=1" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Version imprimable
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php endif; ?>

            <!-- Document Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            
                            <?php if ($isPrintMode): ?>
                            <div class="company-header text-center">
                                <h2 class="mb-0">DEVIS</h2>
                                <h3 class="mb-0"><?php echo $devis['numero']; ?></h3>
                            </div>
                            <?php endif; ?>

                            <div class="p-4">
                                <!-- Document Title and Info -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <h3 class="text-primary mb-3">
                                            DEVIS N° <?php echo $devis['numero']; ?>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="badge bg-<?php 
                                            echo $devis['statut'] == 'accepte' ? 'success' : 
                                                ($devis['statut'] == 'refuse' ? 'danger' : 
                                                ($devis['statut'] == 'expire' ? 'secondary' : 'warning')); 
                                        ?> fs-6 mb-2">
                                            <?php 
                                            $statusLabels = [
                                                'en_attente' => 'En attente',
                                                'accepte' => 'Accepté',
                                                'refuse' => 'Refusé',
                                                'expire' => 'Expiré'
                                            ];
                                            echo $statusLabels[$devis['statut']] ?? ucfirst($devis['statut']);
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client and Company Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-2">
                                                Client:
                                            </h6>
                                            <strong><?php echo $devis['client']['nom']; ?></strong><br>
                                            <?php if ($devis['client']['entreprise']): ?>
                                                <em><?php echo $devis['client']['entreprise']; ?></em><br>
                                            <?php endif; ?>
                                            <?php echo $devis['client']['adresse']; ?><br>
                                            <strong>Email:</strong> <?php echo $devis['client']['email']; ?><br>
                                            <strong>Tél:</strong> <?php echo $devis['client']['telephone']; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-light">
                                            <h6 class="text-primary mb-2">
                                                Informations du Devis:
                                            </h6>
                                            <div class="row g-2 small">
                                                <div class="col-6"><strong>Date d'émission:</strong></div>
                                                <div class="col-6"><?php echo date('d/m/Y', strtotime($devis['date_devis'])); ?></div>
                                                
                                                <div class="col-6"><strong>Validité:</strong></div>
                                                <div class="col-6"><?php echo $devis['validite_jours']; ?> jours</div>
                                                
                                                <div class="col-6"><strong>Expire le:</strong></div>
                                                <div class="col-6 <?php echo (strtotime($devis['date_expiration']) < time()) ? 'text-danger fw-bold' : 'text-success'; ?>">
                                                    <?php echo date('d/m/Y', strtotime($devis['date_expiration'])); ?>
                                                    <?php if (strtotime($devis['date_expiration']) < time()): ?>
                                                        <br><small>(Expiré)</small>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="col-6"><strong>Vendeur:</strong></div>
                                                <div class="col-6"><?php echo $devis['vendeur']['nom']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Articles Table -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="40%">Désignation</th>
                                                <th width="15%">Prix Unitaire</th>
                                                <th width="10%">Qté</th>
                                                <th width="15%">Total HT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($devis['articles'] as $index => $article): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo $article['nom']; ?></strong>
                                                    <?php if ($article['description']): ?>
                                                        <br><small class="text-muted"><?php echo $article['description']; ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end"><?php echo number_format($article['prix_unitaire'], 2, ',', ' '); ?> DH</td>
                                                <td class="text-center"><?php echo $article['quantite']; ?></td>
                                                <td class="text-end fw-bold"><?php echo number_format($article['total'], 2, ',', ' '); ?> DH</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Totals and Status -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php if ($devis['remarque']): ?>
                                        <div class="border p-3 rounded mb-3">
                                            <h6 class="text-primary mb-2">
                                                Remarques:
                                            </h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($devis['remarque'])); ?></p>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Status Alert -->
                                        <?php if ($devis['statut'] == 'accepte'): ?>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Devis accepté</strong> - Vous pouvez procéder à la création d'un bon de commande
                                        </div>
                                        <?php elseif ($devis['statut'] == 'refuse'): ?>
                                        <div class="alert alert-danger">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <strong>Devis refusé</strong> - Le client a décliné cette proposition
                                        </div>
                                        <?php elseif (strtotime($devis['date_expiration']) < time()): ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Devis expiré</strong> - Expiré depuis le <?php echo date('d/m/Y', strtotime($devis['date_expiration'])); ?>
                                        </div>
                                        <?php elseif ($devis['statut'] == 'en_attente'): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-clock me-2"></i>
                                            <strong>En attente de réponse</strong> - Expire le <?php echo date('d/m/Y', strtotime($devis['date_expiration'])); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total HT:</span>
                                                    <strong><?php echo number_format($totalHT, 2, ',', ' '); ?> DH</strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>TVA (20%):</span>
                                                    <strong><?php echo number_format($totalTVA, 2, ',', ' '); ?> DH</strong>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong class="text-primary">Total TTC:</strong>
                                                    <strong class="text-primary fs-5"><?php echo number_format($totalTTC, 2, ',', ' '); ?> DH</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="text-center border-top pt-3">
                                            <small class="text-muted">
                                                <strong>Conditions générales:</strong><br>
                                                - Ce devis est valable <?php echo $devis['validite_jours']; ?> jours à compter de la date d'émission<br>
                                                - Prix exprimés en Dirhams (DH), TVA incluse<br>
                                                - Règlement: 30% à la commande, solde à la livraison<br>
                                                - Devis sans engagement jusqu'à acceptation écrite
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature Area -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3 signature-area">
                                            <strong>Signature du Vendeur</strong><br><br><br>
                                            <small><?php echo $devis['vendeur']['nom']; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3 signature-area">
                                            <strong>Signature du Client</strong><br>
                                            <small>Bon pour accord</small><br><br>
                                            <small><?php echo $devis['client']['nom']; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php if (!$isPrintMode): ?>
        </div>
    </div>
</div>

<?php include_once '../includes/scripts.php'; ?>
<?php else: ?>

<script>
// Auto print for print mode
window.onload = function() {
    setTimeout(function() {
        window.print();
    }, 1000);
};
</script>

</body>
</html>
<?php endif; ?>