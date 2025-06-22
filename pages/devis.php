<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Handle delete request
if (isset($_POST['delete_devis'])) {
    $devis_id = $_POST['devis_id'];
    try {
        // Delete devis details first (cascaded automatically due to ON DELETE CASCADE)
        $stmt = $db->prepare("DELETE FROM devis WHERE id = ?");
        $stmt->execute([$devis_id]);
        $success_message = "Devis supprimé avec succès";
    } catch(PDOException $e) {
        $error_message = "Erreur lors de la suppression du devis";
    }
}

// Fetch devis data with client information
try {
    $devis_query = "SELECT d.*, c.nom as client_nom, c.prenom as client_prenom, c.entreprise as client_entreprise 
                    FROM devis d 
                    LEFT JOIN clients c ON d.client_id = c.id 
                    ORDER BY d.cree_le DESC";
    $devis_list = $db->query($devis_query)->fetchAll();
    
    // Statistics
    $stats_total = $db->query("SELECT COUNT(*) as total FROM devis")->fetch();
    $stats_en_attente = $db->query("SELECT COUNT(*) as total FROM devis WHERE statut = 'en_attente'")->fetch();
    $stats_accepte = $db->query("SELECT COUNT(*) as total FROM devis WHERE statut = 'accepte'")->fetch();
    $stats_expire = $db->query("SELECT COUNT(*) as total FROM devis WHERE statut = 'expire' OR date_expiration < CURDATE()")->fetch();
    $stats_montant_total = $db->query("SELECT COALESCE(SUM(montant_total), 0) as total FROM devis WHERE statut = 'accepte'")->fetch();
    
} catch(PDOException $e) {
    $devis_list = [];
    $stats_total = ['total' => 0];
    $stats_en_attente = ['total' => 0];
    $stats_accepte = ['total' => 0];
    $stats_expire = ['total' => 0];
    $stats_montant_total = ['total' => 0];
    $error_message = "Erreur lors du chargement des devis";
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
        <h1 class="page-title">Liste des Devis</h1>
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
                                                <input type="text" class="form-control" placeholder="Rechercher un devis..." id="searchInput">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" id="statusFilter">
                                                <option value="">Tous les statuts</option>
                                                <option value="en_attente">En attente</option>
                                                <option value="accepte">Accepte</option>
                                                <option value="refuse">Refuse</option>
                                                <option value="expire">Expire</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="export.php?type=devis&format=csv" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter CSV
                                        </a>
                                        <a href="_devis.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouveau Devis
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
                                    <div class="bg-info bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-file-alt fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_total['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Total Devis</p>
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
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_accepte['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Acceptés</p>
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
                                    <div class="bg-primary bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-money-bill-wave fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_montant_total['total'], 2); ?></h4>
                                    <p class="text-muted mb-0 small">Valeur Acceptés (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Devis Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-file-invoice text-primary me-2"></i>
                                    Liste des Devis
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ne Devis</th>
                                            <th>Client</th>
                                            <th>Date</th>
                                            <th>Validite</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($devis_list)): ?>
                                            <?php foreach ($devis_list as $devis): ?>
                                                <?php
                                                $status_classes = [
                                                    'en_attente' => 'bg-warning',
                                                    'accepte' => 'bg-success',
                                                    'refuse' => 'bg-danger',
                                                    'expire' => 'bg-secondary'
                                                ];
                                                $status_labels = [
                                                    'en_attente' => 'En attente',
                                                    'accepte' => 'Accepté',
                                                    'refuse' => 'Refusé',
                                                    'expire' => 'Expiré'
                                                ];
                                                
                                                // Check if expired
                                                $is_expired = strtotime($devis['date_expiration']) < time();
                                                $actual_status = $is_expired ? 'expire' : $devis['statut'];
                                                
                                                $status_class = $status_classes[$actual_status] ?? 'bg-secondary';
                                                $status_label = $status_labels[$actual_status] ?? 'Inconnu';
                                                
                                                // Client name
                                                $client_name = '';
                                                if ($devis['client_entreprise']) {
                                                    $client_name = $devis['client_entreprise'];
                                                } elseif ($devis['client_nom'] && $devis['client_prenom']) {
                                                    $client_name = $devis['client_prenom'] . ' ' . $devis['client_nom'];
                                                } else {
                                                    $client_name = 'Client supprimé';
                                                }
                                                
                                                // Avatar initials
                                                $initials = '';
                                                if ($devis['client_entreprise']) {
                                                    $initials = strtoupper(substr($devis['client_entreprise'], 0, 2));
                                                } elseif ($devis['client_nom'] && $devis['client_prenom']) {
                                                    $initials = strtoupper(substr($devis['client_prenom'], 0, 1) . substr($devis['client_nom'], 0, 1));
                                                } else {
                                                    $initials = '??';
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($devis['numero']); ?></div>
                                                        <small class="text-muted">ID: <?php echo $devis['id']; ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                                <?php echo $initials; ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold small"><?php echo htmlspecialchars($client_name); ?></div>
                                                                <?php if ($devis['client_entreprise'] && ($devis['client_nom'] || $devis['client_prenom'])): ?>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($devis['client_prenom'] . ' ' . $devis['client_nom']); ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small"><?php echo date('d/m/Y', strtotime($devis['date_devis'])); ?></div>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($devis['cree_le'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <i class="fas fa-calendar text-warning me-1"></i>
                                                            <?php echo $devis['validite_jours']; ?> jours
                                                        </div>
                                                        <small class="<?php echo $is_expired ? 'text-danger' : 'text-success'; ?>">
                                                            Expire le <?php echo date('d/m/Y', strtotime($devis['date_expiration'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success"><?php echo number_format($devis['montant_total'], 2); ?> DH</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="devis_detail.php?id=<?php echo $devis['id']; ?>" class="btn btn-outline-primary" title="Voir détail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="_devis.php?id=<?php echo $devis['id']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="devis_detail.php?id=<?php echo $devis['id']; ?>&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteDevis(<?php echo $devis['id']; ?>)" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-file-invoice fa-2x mb-3"></i><br>
                                                    Aucun devis trouvé
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php if (!empty($devis_list)): ?>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de <?php echo count($devis_list); ?> sur <?php echo $stats_total['total']; ?> devis
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
                Êtes-vous sûr de vouloir supprimer ce devis ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="devis_id" id="deleteDevisId">
                    <button type="submit" name="delete_devis" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDevis(devisId) {
    document.getElementById('deleteDevisId').value = devisId;
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
                    (status === 'accepte' && rowStatus.includes('accepté')) ||
                    (status === 'refuse' && rowStatus.includes('refusé')) ||
                    (status === 'expire' && rowStatus.includes('expiré'));
                row.style.display = shouldShow ? '' : 'none';
            }
        }
    });
});
</script>

<?php include_once '../includes/scripts.php'; ?>