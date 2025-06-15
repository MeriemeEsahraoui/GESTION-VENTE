<?php 
$isPrintMode = isset($_GET['print']) && $_GET['print'] == '1';
$devisId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Sample data (would come from database)
$devis = [
    'id' => $devisId,
    'numero' => 'DEV-2024-00' . $devisId,
    'date_devis' => '2024-05-15',
    'validite_jours' => 30,
    'date_expiration' => '2024-06-14',
    'statut' => 'en_attente',
    'montant_total' => 12500.00,
    'remarque' => 'Conditions de paiement: 30% à la commande, solde à la livraison.',
    'client' => [
        'nom' => 'Ahmed Hassan',
        'entreprise' => 'Tech Solutions SARL',
        'email' => 'ahmed.hassan@techsol.ma',
        'telephone' => '+212 6 12 34 56 78',
        'adresse' => '123 Rue Mohammed V, Casablanca 20000',
        'ville' => 'Casablanca'
    ],
    'vendeur' => [
        'nom' => 'Omar Benjelloun',
        'email' => 'omar@gestion-vente.ma',
        'telephone' => '+212 5 22 34 56 78'
    ],
    'articles' => [
        [
            'nom' => 'Ordinateur Portable HP',
            'description' => 'Laptop HP 15-dw3000 Intel Core i5, 8GB RAM, 256GB SSD',
            'quantite' => 2,
            'prix_unitaire' => 4500.00,
            'total' => 9000.00
        ],
        [
            'nom' => 'Souris Logitech',
            'description' => 'Souris optique sans fil Logitech M705',
            'quantite' => 2,
            'prix_unitaire' => 350.00,
            'total' => 700.00
        ],
        [
            'nom' => 'Clavier Mécanique',
            'description' => 'Clavier Gaming RGB mécanique',
            'quantite' => 2,
            'prix_unitaire' => 400.00,
            'total' => 800.00
        ]
    ]
];

$totalHT = array_sum(array_column($devis['articles'], 'total'));
$totalTVA = $totalHT * 0.20;
$totalTTC = $totalHT + $totalTVA;

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
        @media print {
            .no-print { display: none !important; }
            .print-break { page-break-after: always; }
            body { font-size: 12px; }
            .table { font-size: 11px; }
        }
        .company-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 30px;
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
                                    <button onclick="window.print()" class="btn btn-outline-info">
                                        <i class="fas fa-print me-1"></i>Imprimer
                                    </button>
                                    <a href="?id=<?php echo $devis['id']; ?>&print=1" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Version imprimable
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
                            
                            <!-- Company Header -->
                            <div class="company-header text-center">
                                <h2 class="mb-1">GESTION VENTE</h2>
                                <p class="mb-0">Système de Gestion Commercial</p>
                                <small>123 Avenue Mohammed VI, Casablanca 20000 - Tél: +212 5 22 XX XX XX</small>
                            </div>

                            <div class="p-4">
                                <!-- Document Title and Info -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <h3 class="text-primary mb-3">
                                            <i class="fas fa-file-invoice me-2"></i>
                                            DEVIS N° <?php echo $devis['numero']; ?>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="badge bg-<?php 
                                            echo $devis['statut'] == 'accepté' ? 'success' : 
                                                ($devis['statut'] == 'refusé' ? 'danger' : 
                                                ($devis['statut'] == 'expiré' ? 'secondary' : 'warning')); 
                                        ?> fs-6 mb-2">
                                            <?php echo ucfirst($devis['statut']); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client and Company Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-building me-1"></i>Facturé à:
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
                                                <i class="fas fa-info-circle me-1"></i>Informations du Devis:
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6"><strong>Date d'émission:</strong></div>
                                                <div class="col-6"><?php echo date('d/m/Y', strtotime($devis['date_devis'])); ?></div>
                                                
                                                <div class="col-6"><strong>Validité:</strong></div>
                                                <div class="col-6"><?php echo $devis['validite_jours']; ?> jours</div>
                                                
                                                <div class="col-6"><strong>Expire le:</strong></div>
                                                <div class="col-6 text-<?php echo (strtotime($devis['date_expiration']) < time()) ? 'danger' : 'success'; ?>">
                                                    <?php echo date('d/m/Y', strtotime($devis['date_expiration'])); ?>
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

                                <!-- Totals -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php if ($devis['remarque']): ?>
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-comment me-1"></i>Remarques:
                                            </h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($devis['remarque'])); ?></p>
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
                                                - Prix exprimés en Dirhams (DH), TVA comprise<br>
                                                - Règlement: 30% à la commande, solde à la livraison
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature Area -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
                                            <strong>Signature du Vendeur</strong><br><br><br>
                                            <small><?php echo $devis['vendeur']['nom']; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
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