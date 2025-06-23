<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Handle delete request
if (isset($_POST['delete_commande'])) {
    $commande_id = $_POST['commande_id'];
    try {
        $stmt = $db->prepare("DELETE FROM bons_commande WHERE id = ?");
        $stmt->execute([$commande_id]);
        $success_message = "Bon de commande supprimé avec succès";
    } catch(PDOException $e) {
        $error_message = "Erreur lors de la suppression du bon de commande";
    }
}

// Fetch bons de commande data with client information
try {
    $commandes_query = "SELECT bc.*, c.nom as client_nom, c.prenom as client_prenom, c.entreprise as client_entreprise 
                       FROM bons_commande bc 
                       LEFT JOIN clients c ON bc.client_id = c.id 
                       ORDER BY bc.cree_le DESC";
    $commandes_list = $db->query($commandes_query)->fetchAll();
    
    // Statistics
    $stats_total = $db->query("SELECT COUNT(*) as total FROM bons_commande")->fetch();
    $stats_en_attente = $db->query("SELECT COUNT(*) as total FROM bons_commande WHERE statut = 'en_attente'")->fetch();
    $stats_livree = $db->query("SELECT COUNT(*) as total FROM bons_commande WHERE statut = 'livree'")->fetch();
    $stats_montant_total = $db->query("SELECT COALESCE(SUM(montant_total), 0) as total FROM bons_commande")->fetch();
    
} catch(PDOException $e) {
    $commandes_list = [];
    $stats_total = ['total' => 0];
    $stats_en_attente = ['total' => 0];
    $stats_livree = ['total' => 0];
    $stats_montant_total = ['total' => 0];
    $error_message = "Erreur lors du chargement des bons de commande";
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
        <h1 class="page-title">Liste des Bons de Commande</h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container-fluid">
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Page Header with Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Rechercher un bon de commande..." id="searchInput">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" id="statusFilter">
                                                <option value="">Tous les statuts</option>
                                                <option value="en_attente">En attente</option>
                                                <option value="confirme">Confirmé</option>
                                                <option value="expediee">Expédiée</option>
                                                <option value="livree">Livrée</option>
                                                <option value="annulee">Annulée</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="export.php?type=bons_commande&format=csv" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter CSV
                                        </a>
                                        <a href="bon_commande.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouveau BC
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-shopping-cart fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_total['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Total Commandes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_en_attente['total']); ?></h4>
                                    <p class="text-muted mb-0 small">En attente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-truck fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_livree['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Livrées</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-money-bill-wave fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_montant_total['total'], 2); ?></h4>
                                    <p class="text-muted mb-0 small">Valeur Total (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bons de Commande Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                                    Liste des Bons de Commande
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Commande</th>
                                            <th>Client</th>
                                            <th>Date</th>
                                            <th>Date Livraison</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="commandesTableBody">
                                        <?php if (!empty($commandes_list)): ?>
                                            <?php foreach ($commandes_list as $commande): ?>
                                                <?php
                                                $status_classes = [
                                                    'en_attente' => 'bg-warning',
                                                    'confirme' => 'bg-info',
                                                    'expediee' => 'bg-primary',
                                                    'livree' => 'bg-success',
                                                    'annulee' => 'bg-danger'
                                                ];
                                                $status_labels = [
                                                    'en_attente' => 'En attente',
                                                    'confirme' => 'Confirmé',
                                                    'expediee' => 'Expédiée',
                                                    'livree' => 'Livrée',
                                                    'annulee' => 'Annulée'
                                                ];
                                                
                                                $status_class = $status_classes[$commande['statut']] ?? 'bg-secondary';
                                                $status_label = $status_labels[$commande['statut']] ?? 'Inconnu';
                                                
                                                // Client name
                                                $client_name = '';
                                                if ($commande['client_entreprise']) {
                                                    $client_name = $commande['client_entreprise'];
                                                } elseif ($commande['client_nom'] && $commande['client_prenom']) {
                                                    $client_name = $commande['client_prenom'] . ' ' . $commande['client_nom'];
                                                } else {
                                                    $client_name = 'Client supprimé';
                                                }
                                                
                                                // Avatar initials
                                                $initials = '';
                                                if ($commande['client_entreprise']) {
                                                    $initials = strtoupper(substr($commande['client_entreprise'], 0, 2));
                                                } elseif ($commande['client_nom'] && $commande['client_prenom']) {
                                                    $initials = strtoupper(substr($commande['client_prenom'], 0, 1) . substr($commande['client_nom'], 0, 1));
                                                } else {
                                                    $initials = '??';
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($commande['numero']); ?></div>
                                                        <small class="text-muted">ID: <?php echo $commande['id']; ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                                <?php echo $initials; ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold small"><?php echo htmlspecialchars($client_name); ?></div>
                                                                <?php if ($commande['client_entreprise'] && ($commande['client_nom'] || $commande['client_prenom'])): ?>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($commande['client_prenom'] . ' ' . $commande['client_nom']); ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small"><?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></div>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($commande['date_commande'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <i class="fas fa-calendar text-info me-1"></i>
                                                            <?php echo $commande['date_livraison_prevue'] ? date('d/m/Y', strtotime($commande['date_livraison_prevue'])) : 'Non définie'; ?>
                                                        </div>
                                                        <?php if ($commande['date_livraison_reelle']): ?>
                                                            <small class="text-success">Livrée le <?php echo date('d/m/Y', strtotime($commande['date_livraison_reelle'])); ?></small>
                                                        <?php else: ?>
                                                            <small class="text-muted">Prévue</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success"><?php echo number_format($commande['montant_total'], 2); ?> DH</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="bon_commande_detail.php?id=<?php echo $commande['id']; ?>" class="btn btn-outline-primary" title="Voir détail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="bon_commande.php?id=<?php echo $commande['id']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="bon_commande_detail.php?id=<?php echo $commande['id']; ?>&print=1" class="btn btn-outline-info" title="Version imprimable" target="_blank">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteCommande(<?php echo $commande['id']; ?>)" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-shopping-cart fa-2x mb-3"></i><br>
                                                    Aucun bon de commande trouvé
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php if (!empty($commandes_list)): ?>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de <?php echo count($commandes_list); ?> sur <?php echo $stats_total['total']; ?> bons de commande
                                </div>
                                <?php if ($stats_total['total'] > 10): ?>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce bon de commande ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="commande_id" id="deleteCommandeId">
                    <button type="submit" name="delete_commande" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCommande(commandeId) {
    document.getElementById('deleteCommandeId').value = commandeId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            const statusBadge = row.querySelector('.badge');
            if (statusBadge) {
                const rowStatus = statusBadge.textContent.toLowerCase();
                const shouldShow = 
                    (status === 'en_attente' && rowStatus.includes('attente')) ||
                    (status === 'confirme' && rowStatus.includes('confirmé')) ||
                    (status === 'expediee' && rowStatus.includes('expédiée')) ||
                    (status === 'livree' && rowStatus.includes('livrée')) ||
                    (status === 'annulee' && rowStatus.includes('annulée'));
                row.style.display = shouldShow ? '' : 'none';
            }
        }
    });
});
</script>

<?php include_once '../includes/scripts.php'; ?>