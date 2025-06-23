
<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
try {
    $stats_clients = $db->query("SELECT COUNT(*) as total FROM clients WHERE status = 1")->fetch();
    $stats_produits = $db->query("SELECT COUNT(*) as total FROM produits WHERE status = 1")->fetch();
    $stats_devis = $db->query("SELECT COUNT(*) as total FROM devis")->fetch();
    $stats_bons_commande = $db->query("SELECT COUNT(*) as total FROM bons_commande")->fetch();
    $stats_factures = $db->query("SELECT COUNT(*) as total FROM factures")->fetch();
    
    $ca_mois = $db->query("SELECT COALESCE(SUM(montant_total), 0) as total FROM factures WHERE statut = 'payee' AND MONTH(date_facture) = MONTH(CURDATE()) AND YEAR(date_facture) = YEAR(CURDATE())")->fetch();
    
    // Additional stats
    $devis_en_attente = $db->query("SELECT COUNT(*) as total FROM devis WHERE statut = 'en_attente'")->fetch();
    $factures_impayees = $db->query("SELECT COUNT(*) as total FROM factures WHERE statut IN ('envoyee', 'en_retard')")->fetch();
    $stock_faible = $db->query("SELECT COUNT(*) as total FROM produits WHERE quantite_en_stock <= seuil_stock AND quantite_en_stock > 0")->fetch();
    $ca_total = $db->query("SELECT COALESCE(SUM(montant_total), 0) as total FROM factures WHERE statut = 'payee'")->fetch();
    
    $recent_factures = $db->query("SELECT f.numero, f.date_facture, f.montant_total, f.statut, c.nom, c.prenom, c.entreprise, f.id FROM factures f LEFT JOIN clients c ON f.client_id = c.id ORDER BY f.cree_le DESC LIMIT 5")->fetchAll();
    
} catch(PDOException $e) {
    error_log("Database error in acceuil.php: " . $e->getMessage());
    $stats_clients = ['total' => 0];
    $stats_produits = ['total' => 0]; 
    $stats_devis = ['total' => 0];
    $stats_bons_commande = ['total' => 0];
    $stats_factures = ['total' => 0];
    $ca_mois = ['total' => 0];
    $devis_en_attente = ['total' => 0];
    $factures_impayees = ['total' => 0];
    $stock_faible = ['total' => 0];
    $ca_total = ['total' => 0];
    $recent_factures = [];
}
?>

<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>


<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Acceuil</h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card sales">
                <div class="stat-header">
                    <div class="stat-icon sales">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($stats_factures['total']); ?></div>
                <div class="stat-label">Factures totales</div>
            </div>

            <div class="stat-card revenue">
                <div class="stat-header">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($ca_mois['total'], 2); ?> DH</div>
                <div class="stat-label">CA ce mois</div>
            </div>

            <div class="stat-card clients">
                <div class="stat-header">
                    <div class="stat-icon clients">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($stats_clients['total']); ?></div>
                <div class="stat-label">Clients actifs</div>
            </div>

            <div class="stat-card orders">
                <div class="stat-header">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($stats_bons_commande['total']); ?></div>
                <div class="stat-label">Bons de commande</div>
            </div>

            <div class="stat-card pending">
                <div class="stat-header">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($devis_en_attente['total']); ?></div>
                <div class="stat-label">Devis en attente</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($factures_impayees['total']); ?></div>
                <div class="stat-label">Factures impayées</div>
            </div>

            <div class="stat-card stock">
                <div class="stat-header">
                    <div class="stat-icon stock">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($stock_faible['total']); ?></div>
                <div class="stat-label">Stock faible</div>
            </div>

            <div class="stat-card total-revenue">
                <div class="stat-header">
                    <div class="stat-icon total-revenue">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($ca_total['total'], 0); ?> DH</div>
                <div class="stat-label">CA total</div>
            </div>
        </div>

        <!-- Latest Factures Section -->
        <div class="recent-activity mt-4">
            <div class="activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Dernières Factures
                    </h3>
                    <a href="factures.php" class="btn btn-sm btn-outline-primary">
                        Voir toutes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_factures)): ?>
                                <?php foreach ($recent_factures as $facture): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($facture['numero']); ?></strong></td>
                                        <td>
                                            <?php 
                                            if ($facture['entreprise']) {
                                                echo htmlspecialchars($facture['entreprise']);
                                            } else {
                                                echo htmlspecialchars($facture['prenom'] . ' ' . $facture['nom']);
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></td>
                                        <td><strong><?php echo number_format($facture['montant_total'], 2); ?> DH</strong></td>
                                        <td>
                                            <?php
                                            $status_classes = [
                                                'brouillon' => 'bg-secondary',
                                                'envoyee' => 'bg-primary', 
                                                'payee' => 'bg-success',
                                                'en_retard' => 'bg-danger',
                                                'annulee' => 'bg-dark'
                                            ];
                                            $status_labels = [
                                                'brouillon' => 'Brouillon',
                                                'envoyee' => 'Envoyée',
                                                'payee' => 'Payée', 
                                                'en_retard' => 'En retard',
                                                'annulee' => 'Annulée'
                                            ];
                                            $class = $status_classes[$facture['statut']] ?? 'bg-secondary';
                                            $label = $status_labels[$facture['statut']] ?? 'Inconnu';
                                            ?>
                                            <span class="badge <?php echo $class; ?>"><?php echo $label; ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="viewFacture('<?php echo $facture['id']; ?>')" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-file-invoice me-2"></i>
                                        Aucune facture trouvée
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.recent-activity {
    margin-top: 2rem;
}

.activity-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.table {
    margin: 0;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    color: #6c757d;
    font-weight: 600;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

.btn-action {
    padding: 0.25rem 0.5rem;
    margin: 0 0.125rem;
    border-radius: 6px;
}
</style>

<script>

function viewFacture(numero) {
    window.location.href = `facture_detail.php?id=${numero}`;
}
</script>

<?php include_once '../includes/scripts.php'; ?>
