<?php 
$isPrintMode = isset($_GET['print']) && $_GET['print'] == '1';
$commandeId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Sample data (would come from database)
$commande = [
    'id' => $commandeId,
    'numero' => 'CMD-2024-00' . $commandeId,
    'date_commande' => '2024-05-16',
    'date_livraison_prevue' => '2024-05-20',
    'date_livraison_reelle' => $commandeId == 2 ? '2024-05-18' : null,
    'statut' => $commandeId == 1 ? 'en_attente' : ($commandeId == 2 ? 'livree' : 'expediee'),
    'mode_paiement' => 'virement',
    'montant_total' => $commandeId == 1 ? 15200.00 : ($commandeId == 2 ? 8750.00 : 22400.00),
    'adresse_livraison' => '123 Rue Mohammed V, Casablanca 20000',
    'remarques' => 'Livraison entre 9h et 17h. Sonner à l\'interphone.',
    'client' => [
        'nom' => $commandeId == 1 ? 'Ahmed Hassan' : ($commandeId == 2 ? 'Fatima El Amrani' : 'Youssef Benali'),
        'entreprise' => $commandeId == 1 ? 'Tech Solutions SARL' : ($commandeId == 2 ? 'Boutique Moderne' : 'Import Export Co.'),
        'email' => $commandeId == 1 ? 'ahmed.hassan@techsol.ma' : ($commandeId == 2 ? 'fatima@boutique-moderne.com' : 'y.benali@importexport.ma'),
        'telephone' => $commandeId == 1 ? '+212 6 12 34 56 78' : ($commandeId == 2 ? '+212 6 87 65 43 21' : '+212 6 99 88 77 66'),
        'adresse' => $commandeId == 1 ? '123 Rue Mohammed V, Casablanca 20000' : ($commandeId == 2 ? '456 Avenue Hassan II, Rabat 10000' : '789 Boulevard Zerktouni, Marrakech 40000'),
        'ville' => $commandeId == 1 ? 'Casablanca' : ($commandeId == 2 ? 'Rabat' : 'Marrakech')
    ],
    'vendeur' => [
        'nom' => 'Omar Benjelloun',
        'email' => 'omar@gestion-vente.ma',
        'telephone' => '+212 5 22 34 56 78'
    ],
    'articles' => $commandeId == 1 ? [
        [
            'nom' => 'Ordinateur Portable HP',
            'description' => 'Laptop HP 15-dw3000 Intel Core i5, 8GB RAM, 256GB SSD',
            'quantite' => 2,
            'prix_unitaire' => 4500.00,
            'total' => 9000.00
        ],
        [
            'nom' => 'Imprimante Canon',
            'description' => 'PIXMA TS3450 Multifonction WiFi',
            'quantite' => 1,
            'prix_unitaire' => 899.00,
            'total' => 899.00
        ],
        [
            'nom' => 'Pack Souris + Clavier',
            'description' => 'Ensemble sans fil Logitech MK545',
            'quantite' => 2,
            'prix_unitaire' => 450.00,
            'total' => 900.00
        ]
    ] : ($commandeId == 2 ? [
        [
            'nom' => 'Smartphone Samsung',
            'description' => 'Galaxy A54 5G 128GB',
            'quantite' => 2,
            'prix_unitaire' => 3200.00,
            'total' => 6400.00
        ],
        [
            'nom' => 'Étui de protection',
            'description' => 'Coque silicone transparente',
            'quantite' => 2,
            'prix_unitaire' => 150.00,
            'total' => 300.00
        ]
    ] : [
        [
            'nom' => 'Serveur Dell',
            'description' => 'PowerEdge T140 Xeon E-2224G 8GB 1TB',
            'quantite' => 1,
            'prix_unitaire' => 18000.00,
            'total' => 18000.00
        ],
        [
            'nom' => 'Switch Cisco',
            'description' => '24 ports Gigabit managed',
            'quantite' => 1,
            'prix_unitaire' => 2500.00,
            'total' => 2500.00
        ]
    ])
];

$totalHT = array_sum(array_column($commande['articles'], 'total'));
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
    <title>Bon de Commande <?php echo $commande['numero']; ?></title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        <h1 class="page-title">Détail du Bon de Commande <?php echo $commande['numero']; ?></h1>
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
                                    <h5 class="mb-0 fw-bold">Bon de Commande <?php echo $commande['numero']; ?></h5>
                                    <small class="text-muted">Créé le <?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></small>
                                </div>
                                <div class="btn-group">
                                    <a href="bons_commande.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Retour
                                    </a>
                                    <a href="bon_commande.php?id=<?php echo $commande['id']; ?>" class="btn btn-outline-warning">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    <button onclick="window.print()" class="btn btn-outline-info">
                                        <i class="fas fa-print me-1"></i>Imprimer
                                    </button>
                                    <a href="?id=<?php echo $commande['id']; ?>&print=1" target="_blank" class="btn btn-primary">
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
                        
                            <?php if ($isPrintMode): ?>
                            <div class="company-header text-center">
                                <h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>BON DE COMMANDE</h2>
                                <h3 class="mb-0"><?php echo $commande['numero']; ?></h3>
                            </div>
                            <?php endif; ?>

                            <div class="p-4">
                                <!-- Document Title and Info -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <?php if (!$isPrintMode): ?>
                                        <h3 class="text-primary mb-3">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            BON DE COMMANDE N° <?php echo $commande['numero']; ?>
                                        </h3>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="badge bg-<?php 
                                            echo $commande['statut'] == 'livree' ? 'success' : 
                                                ($commande['statut'] == 'annulee' ? 'danger' : 
                                                ($commande['statut'] == 'expediee' ? 'info' : 
                                                ($commande['statut'] == 'confirme' ? 'primary' : 'warning'))); 
                                        ?> fs-6 mb-2">
                                            <?php 
                                            $statusLabels = [
                                                'en_attente' => 'En attente',
                                                'confirme' => 'Confirmé',
                                                'expediee' => 'Expédiée',
                                                'livree' => 'Livrée',
                                                'annulee' => 'Annulée'
                                            ];
                                            echo $statusLabels[$commande['statut']];
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client and Company Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-building me-1"></i>Client:
                                            </h6>
                                            <strong><?php echo $commande['client']['nom']; ?></strong><br>
                                            <?php if ($commande['client']['entreprise']): ?>
                                                <em><?php echo $commande['client']['entreprise']; ?></em><br>
                                            <?php endif; ?>
                                            <?php echo $commande['client']['adresse']; ?><br>
                                            <strong>Email:</strong> <?php echo $commande['client']['email']; ?><br>
                                            <strong>Tél:</strong> <?php echo $commande['client']['telephone']; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-light">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-info-circle me-1"></i>Informations de la Commande:
                                            </h6>
                                            <div class="row g-2 small">
                                                <div class="col-6"><strong>Date de commande:</strong></div>
                                                <div class="col-6"><?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></div>
                                                
                                                <div class="col-6"><strong>Livraison prévue:</strong></div>
                                                <div class="col-6"><?php echo date('d/m/Y', strtotime($commande['date_livraison_prevue'])); ?></div>
                                                
                                                <?php if ($commande['date_livraison_reelle']): ?>
                                                <div class="col-6"><strong>Livraison réelle:</strong></div>
                                                <div class="col-6 text-success"><?php echo date('d/m/Y', strtotime($commande['date_livraison_reelle'])); ?></div>
                                                <?php endif; ?>
                                                
                                                <div class="col-6"><strong>Mode de paiement:</strong></div>
                                                <div class="col-6"><?php 
                                                    $paiementLabels = [
                                                        'especes' => 'Espèces',
                                                        'cheque' => 'Chèque',
                                                        'virement' => 'Virement',
                                                        'carte' => 'Carte bancaire',
                                                        'credit' => 'À crédit'
                                                    ];
                                                    echo $paiementLabels[$commande['mode_paiement']];
                                                ?></div>
                                                
                                                <div class="col-6"><strong>Vendeur:</strong></div>
                                                <div class="col-6"><?php echo $commande['vendeur']['nom']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Address -->
                                <?php if ($commande['adresse_livraison']): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="border p-3 rounded bg-warning bg-opacity-10">
                                            <h6 class="text-warning mb-2">
                                                <i class="fas fa-truck me-1"></i>Adresse de Livraison:
                                            </h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($commande['adresse_livraison'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

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
                                            <?php foreach ($commande['articles'] as $index => $article): ?>
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

                                <!-- Totals and Remarks -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php if ($commande['remarques']): ?>
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-comment me-1"></i>Remarques:
                                            </h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($commande['remarques'])); ?></p>
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
                                                <strong>Conditions de livraison:</strong><br>
                                                - Livraison sous 3 à 5 jours ouvrables<br>
                                                - Frais de livraison inclus pour les commandes > 500 DH<br>
                                                - Vérification obligatoire à la réception
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature Area -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
                                            <strong>Signature du Vendeur</strong><br><br><br>
                                            <small><?php echo $commande['vendeur']['nom']; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
                                            <strong>Signature du Client</strong><br>
                                            <small>Bon pour accord</small><br><br>
                                            <small><?php echo $commande['client']['nom']; ?></small>
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