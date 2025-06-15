<?php 
$isPrintMode = isset($_GET['print']) && $_GET['print'] == '1';
$factureId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Sample data (would come from database)
$facture = [
    'id' => $factureId,
    'numero' => 'FAC-2024-00' . $factureId,
    'date_facture' => $factureId == 1 ? '2024-05-18' : ($factureId == 2 ? '2024-05-16' : ($factureId == 3 ? '2024-05-12' : '2024-05-20')),
    'date_echeance' => $factureId == 1 ? '2024-06-17' : ($factureId == 2 ? '2024-06-15' : ($factureId == 3 ? '2024-05-11' : '2024-06-19')),
    'date_paiement' => $factureId == 2 ? '2024-05-20' : null,
    'statut' => $factureId == 1 ? 'envoyee' : ($factureId == 2 ? 'payee' : ($factureId == 3 ? 'en_retard' : 'brouillon')),
    'conditions_paiement' => '30_jours',
    'mode_paiement' => 'virement',
    'bon_commande_numero' => $factureId == 1 ? 'CMD-2024-001' : null,
    'montant_total' => $factureId == 1 ? 18240.00 : ($factureId == 2 ? 10500.00 : ($factureId == 3 ? 26880.00 : 15600.00)),
    'remarques' => 'Paiement par virement bancaire. Merci d\'indiquer le numéro de facture en référence.',
    'client' => [
        'nom' => $factureId == 1 ? 'Ahmed Hassan' : ($factureId == 2 ? 'Fatima El Amrani' : ($factureId == 3 ? 'Youssef Benali' : 'Karim Cherkaoui')),
        'entreprise' => $factureId == 1 ? 'Tech Solutions SARL' : ($factureId == 2 ? 'Boutique Moderne' : ($factureId == 3 ? 'Import Export Co.' : 'Digital Agency')),
        'email' => $factureId == 1 ? 'ahmed.hassan@techsol.ma' : ($factureId == 2 ? 'fatima@boutique-moderne.com' : ($factureId == 3 ? 'y.benali@importexport.ma' : 'k.cherkaoui@digital-agency.ma')),
        'telephone' => $factureId == 1 ? '+212 6 12 34 56 78' : ($factureId == 2 ? '+212 6 87 65 43 21' : ($factureId == 3 ? '+212 6 99 88 77 66' : '+212 6 55 44 33 22')),
        'adresse' => $factureId == 1 ? '123 Rue Mohammed V, Casablanca 20000' : ($factureId == 2 ? '456 Avenue Hassan II, Rabat 10000' : ($factureId == 3 ? '789 Boulevard Zerktouni, Marrakech 40000' : '101 Avenue Allal Ben Abdellah, Fès 30000')),
        'ville' => $factureId == 1 ? 'Casablanca' : ($factureId == 2 ? 'Rabat' : ($factureId == 3 ? 'Marrakech' : 'Fès'))
    ],
    'vendeur' => [
        'nom' => 'Omar Benjelloun',
        'email' => 'omar@gestion-vente.ma',
        'telephone' => '+212 5 22 34 56 78'
    ],
    'articles' => $factureId == 1 ? [
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
        ],
        [
            'nom' => 'Écran Dell 24"',
            'description' => 'Moniteur LED Full HD',
            'quantite' => 2,
            'prix_unitaire' => 1200.00,
            'total' => 2400.00
        ]
    ] : ($factureId == 2 ? [
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
        ],
        [
            'nom' => 'Écouteurs sans fil',
            'description' => 'Samsung Galaxy Buds',
            'quantite' => 1,
            'prix_unitaire' => 550.00,
            'total' => 550.00
        ]
    ] : ($factureId == 3 ? [
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
        ],
        [
            'nom' => 'Câbles Réseau',
            'description' => 'Pack 20 câbles Cat6 2m',
            'quantite' => 1,
            'prix_unitaire' => 400.00,
            'total' => 400.00
        ]
    ] : [
        [
            'nom' => 'MacBook Pro 14"',
            'description' => 'M3 Pro 18GB 512GB Space Black',
            'quantite' => 1,
            'prix_unitaire' => 12000.00,
            'total' => 12000.00
        ],
        [
            'nom' => 'Adobe Creative Suite',
            'description' => 'Licence annuelle Creative Cloud',
            'quantite' => 1,
            'prix_unitaire' => 1000.00,
            'total' => 1000.00
        ]
    ]))
];

$totalHT = array_sum(array_column($facture['articles'], 'total'));
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
    <title>Facture <?php echo $facture['numero']; ?></title>
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
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
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
        <h1 class="page-title">Détail de la Facture <?php echo $facture['numero']; ?></h1>
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
                                    <h5 class="mb-0 fw-bold">Facture <?php echo $facture['numero']; ?></h5>
                                    <small class="text-muted">Créée le <?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></small>
                                </div>
                                <div class="btn-group">
                                    <a href="factures.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Retour
                                    </a>
                                    <a href="facture.php?id=<?php echo $facture['id']; ?>" class="btn btn-outline-warning">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    <button onclick="window.print()" class="btn btn-outline-info">
                                        <i class="fas fa-print me-1"></i>Imprimer
                                    </button>
                                    <button onclick="sendByEmail()" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-1"></i>Envoyer
                                    </button>
                                    <a href="?id=<?php echo $facture['id']; ?>&print=1" target="_blank" class="btn btn-primary">
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
                                <h2 class="mb-0"><i class="fas fa-file-invoice me-2"></i>FACTURE</h2>
                                <h3 class="mb-0"><?php echo $facture['numero']; ?></h3>
                            </div>
                            <?php endif; ?>

                            <div class="p-4">
                                <!-- Document Title and Status -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <?php if (!$isPrintMode): ?>
                                        <h3 class="text-primary mb-3">
                                            <i class="fas fa-file-invoice me-2"></i>
                                            FACTURE N° <?php echo $facture['numero']; ?>
                                        </h3>
                                        <?php endif; ?>
                                        <?php if ($facture['bon_commande_numero']): ?>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-link me-1"></i>
                                            Référence BC: <strong><?php echo $facture['bon_commande_numero']; ?></strong>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="badge bg-<?php 
                                            echo $facture['statut'] == 'payee' ? 'success' : 
                                                ($facture['statut'] == 'en_retard' ? 'danger' : 
                                                ($facture['statut'] == 'envoyee' ? 'warning' : 
                                                ($facture['statut'] == 'annulee' ? 'secondary' : 'primary'))); 
                                        ?> fs-6 mb-2">
                                            <?php 
                                            $statusLabels = [
                                                'brouillon' => 'Brouillon',
                                                'envoyee' => 'Envoyée',
                                                'payee' => 'Payée',
                                                'en_retard' => 'En retard',
                                                'annulee' => 'Annulée'
                                            ];
                                            echo $statusLabels[$facture['statut']];
                                            ?>
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
                                            <strong><?php echo $facture['client']['nom']; ?></strong><br>
                                            <?php if ($facture['client']['entreprise']): ?>
                                                <em><?php echo $facture['client']['entreprise']; ?></em><br>
                                            <?php endif; ?>
                                            <?php echo $facture['client']['adresse']; ?><br>
                                            <strong>Email:</strong> <?php echo $facture['client']['email']; ?><br>
                                            <strong>Tél:</strong> <?php echo $facture['client']['telephone']; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-light">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-info-circle me-1"></i>Informations de la Facture:
                                            </h6>
                                            <div class="row g-2 small">
                                                <div class="col-6"><strong>Date de facture:</strong></div>
                                                <div class="col-6"><?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></div>
                                                
                                                <div class="col-6"><strong>Date d'échéance:</strong></div>
                                                <div class="col-6 <?php echo (strtotime($facture['date_echeance']) < time() && $facture['statut'] != 'payee') ? 'text-danger fw-bold' : 'text-success'; ?>">
                                                    <?php echo date('d/m/Y', strtotime($facture['date_echeance'])); ?>
                                                    <?php if (strtotime($facture['date_echeance']) < time() && $facture['statut'] != 'payee'): ?>
                                                        <br><small>(Échue)</small>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if ($facture['date_paiement']): ?>
                                                <div class="col-6"><strong>Date de paiement:</strong></div>
                                                <div class="col-6 text-success"><?php echo date('d/m/Y', strtotime($facture['date_paiement'])); ?></div>
                                                <?php endif; ?>
                                                
                                                <div class="col-6"><strong>Conditions paiement:</strong></div>
                                                <div class="col-6"><?php 
                                                    $conditionsLabels = [
                                                        '30_jours' => '30 jours',
                                                        'comptant' => 'Comptant',
                                                        '15_jours' => '15 jours',
                                                        '60_jours' => '60 jours',
                                                        'fin_mois' => 'Fin de mois'
                                                    ];
                                                    echo $conditionsLabels[$facture['conditions_paiement']];
                                                ?></div>
                                                
                                                <div class="col-6"><strong>Mode de paiement:</strong></div>
                                                <div class="col-6"><?php 
                                                    $paiementLabels = [
                                                        'virement' => 'Virement',
                                                        'cheque' => 'Chèque',
                                                        'especes' => 'Espèces',
                                                        'carte' => 'Carte bancaire',
                                                        'prelevement' => 'Prélèvement'
                                                    ];
                                                    echo $paiementLabels[$facture['mode_paiement']];
                                                ?></div>
                                                
                                                <div class="col-6"><strong>Vendeur:</strong></div>
                                                <div class="col-6"><?php echo $facture['vendeur']['nom']; ?></div>
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
                                            <?php foreach ($facture['articles'] as $index => $article): ?>
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

                                <!-- Totals and Payment Status -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php if ($facture['remarques']): ?>
                                        <div class="border p-3 rounded mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-comment me-1"></i>Remarques:
                                            </h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($facture['remarques'])); ?></p>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Payment Status -->
                                        <?php if ($facture['statut'] == 'payee'): ?>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Facture payée</strong> le <?php echo date('d/m/Y', strtotime($facture['date_paiement'])); ?>
                                        </div>
                                        <?php elseif ($facture['statut'] == 'en_retard'): ?>
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Facture en retard</strong> - Échue depuis le <?php echo date('d/m/Y', strtotime($facture['date_echeance'])); ?>
                                        </div>
                                        <?php elseif ($facture['statut'] == 'envoyee'): ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock me-2"></i>
                                            <strong>En attente de paiement</strong> - Échéance le <?php echo date('d/m/Y', strtotime($facture['date_echeance'])); ?>
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
                                                <?php if ($facture['statut'] == 'payee'): ?>
                                                <div class="mt-2 text-center">
                                                    <span class="badge bg-success">PAYÉ</span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="text-center border-top pt-3">
                                            <small class="text-muted">
                                                <strong>Informations bancaires:</strong><br>
                                                IBAN: MA64 011 780 0000012100001234567<br>
                                                BIC: BMCEMAMC - Bank of Morocco<br>
                                                Merci d'indiquer le numéro de facture en référence de paiement
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature Area -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
                                            <strong>Signature du Vendeur</strong><br><br><br>
                                            <small><?php echo $facture['vendeur']['nom']; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center border rounded p-3">
                                            <strong>Cachet de l'Entreprise</strong><br><br><br>
                                            <small>Gestion Vente SARL</small>
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

<script>
function sendByEmail() {
    alert('Fonctionnalité d\'envoi par email à implémenter');
}
</script>

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