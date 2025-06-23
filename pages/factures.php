<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Handle search and filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

// Build WHERE conditions
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(f.numero LIKE ? OR c.nom LIKE ? OR c.prenom LIKE ? OR c.entreprise LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($status_filter)) {
    $where_conditions[] = "f.statut = ?";
    $params[] = $status_filter;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Load factures from database
try {
    // Get factures with client information
    $sql = "SELECT f.*, 
                   c.nom as client_nom, c.prenom as client_prenom, c.email as client_email, 
                   c.telephone as client_telephone, c.entreprise as client_entreprise,
                   bc.numero as bon_commande_numero
            FROM factures f 
            LEFT JOIN clients c ON f.client_id = c.id 
            LEFT JOIN bons_commande bc ON f.bon_commande_id = bc.id 
            $where_clause
            ORDER BY f.cree_le DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $factures = $stmt->fetchAll();
    
    // Get statistics
    $stats_sql = "SELECT 
                    COUNT(*) as total_factures,
                    COUNT(CASE WHEN statut = 'brouillon' THEN 1 END) as brouillon,
                    COUNT(CASE WHEN statut = 'envoyee' THEN 1 END) as envoyee,
                    COUNT(CASE WHEN statut = 'payee' THEN 1 END) as payee,
                    COUNT(CASE WHEN statut = 'en_retard' THEN 1 END) as en_retard,
                    COUNT(CASE WHEN statut = 'annulee' THEN 1 END) as annulee,
                    COALESCE(SUM(montant_total), 0) as ca_total,
                    COALESCE(SUM(CASE WHEN statut = 'payee' THEN montant_total ELSE 0 END), 0) as ca_encaisse,
                    COALESCE(SUM(CASE WHEN statut IN ('envoyee', 'en_retard') THEN montant_total ELSE 0 END), 0) as ca_en_attente
                  FROM factures";
    
    $stats = $db->query($stats_sql)->fetch();
    
} catch(PDOException $e) {
    $factures = [];
    $stats = [
        'total_factures' => 0,
        'brouillon' => 0,
        'envoyee' => 0,
        'payee' => 0,
        'en_retard' => 0,
        'annulee' => 0,
        'ca_total' => 0,
        'ca_encaisse' => 0,
        'ca_en_attente' => 0
    ];
}

// Handle messages
$success_message = $_GET['success'] ?? '';
$error_message = $_GET['error'] ?? '';

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch($status) {
        case 'brouillon': return 'bg-secondary';
        case 'envoyee': return 'bg-warning';
        case 'payee': return 'bg-success';
        case 'en_retard': return 'bg-danger';
        case 'annulee': return 'bg-dark';
        default: return 'bg-secondary';
    }
}

// Function to get status label
function getStatusLabel($status) {
    switch($status) {
        case 'brouillon': return 'Brouillon';
        case 'envoyee': return 'Envoyée';
        case 'payee': return 'Payée';
        case 'en_retard': return 'En retard';
        case 'annulee': return 'Annulée';
        default: return ucfirst($status);
    }
}

// Function to calculate days difference
function getDaysDifference($date) {
    $target = new DateTime($date);
    $today = new DateTime();
    $diff = $today->diff($target);
    
    if ($target < $today) {
        return -$diff->days; // Negative for overdue
    } else {
        return $diff->days; // Positive for future
    }
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
        <h1 class="page-title">Liste des Factures</h1>
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
            
            <?php if (!empty($error_message)): ?>
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
                            <form method="GET" action="" class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control" name="search" 
                                                       placeholder="Rechercher une facture..." 
                                                       value="<?php echo htmlspecialchars($search); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" name="status" onchange="this.form.submit()">
                                                <option value="">Tous les statuts</option>
                                                <option value="brouillon" <?php echo $status_filter == 'brouillon' ? 'selected' : ''; ?>>Brouillon</option>
                                                <option value="envoyee" <?php echo $status_filter == 'envoyee' ? 'selected' : ''; ?>>Envoyée</option>
                                                <option value="payee" <?php echo $status_filter == 'payee' ? 'selected' : ''; ?>>Payée</option>
                                                <option value="en_retard" <?php echo $status_filter == 'en_retard' ? 'selected' : ''; ?>>En retard</option>
                                                <option value="annulee" <?php echo $status_filter == 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="export.php?type=factures&format=csv" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter CSV
                                        </a>
                                        <a href="facture.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouvelle Facture
                                        </a>
                                    </div>
                                </div>
                            </form>
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
                                        <i class="fas fa-file-invoice fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo $stats['total_factures']; ?></h4>
                                    <p class="text-muted mb-0 small">Total Factures</p>
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
                                    <h4 class="mb-0 fw-bold"><?php echo $stats['envoyee']; ?></h4>
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
                                    <h4 class="mb-0 fw-bold"><?php echo $stats['payee']; ?></h4>
                                    <p class="text-muted mb-0 small">Payées</p>
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
                                    <div class="bg-danger bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo $stats['en_retard']; ?></h4>
                                    <p class="text-muted mb-0 small">En retard</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Stats -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-money-bill-wave fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats['ca_total'], 0, ',', ' '); ?></h4>
                                    <p class="text-muted mb-0 small">CA Total (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats['ca_encaisse'], 0, ',', ' '); ?></h4>
                                    <p class="text-muted mb-0 small">Encaissé (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-hourglass-half fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats['ca_en_attente'], 0, ',', ' '); ?></h4>
                                    <p class="text-muted mb-0 small">En attente (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Factures Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-file-invoice text-primary me-2"></i>
                                    Liste des Factures (<?php echo count($factures); ?>)
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($factures)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucune facture trouvée</h5>
                                    <p class="text-muted">Commencez par créer votre première facture.</p>
                                    <a href="facture.php" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Créer une facture
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- Table View -->
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>N° Facture</th>
                                                <th>Client</th>
                                                <th>Date Émission</th>
                                                <th>Échéance</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                                <th style="width: 140px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($factures as $facture): ?>
                                                <?php
                                                $client_initials = '';
                                                if ($facture['client_prenom'] && $facture['client_nom']) {
                                                    $client_initials = strtoupper(substr($facture['client_prenom'], 0, 1) . substr($facture['client_nom'], 0, 1));
                                                }
                                                
                                                $days_diff = getDaysDifference($facture['date_echeance']);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($facture['numero']); ?></div>
                                                        <small class="text-muted">ID: <?php echo $facture['id']; ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                                <?php echo $client_initials; ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold small">
                                                                    <?php echo htmlspecialchars($facture['client_prenom'] . ' ' . $facture['client_nom']); ?>
                                                                </div>
                                                                <?php if ($facture['client_entreprise']): ?>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($facture['client_entreprise']); ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small"><?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></div>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($facture['cree_le'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <i class="fas fa-calendar <?php echo $days_diff < 0 ? 'text-danger' : ($days_diff < 7 ? 'text-warning' : 'text-info'); ?> me-1"></i>
                                                            <?php echo date('d/m/Y', strtotime($facture['date_echeance'])); ?>
                                                        </div>
                                                        <small class="<?php echo $days_diff < 0 ? 'text-danger' : 'text-success'; ?>">
                                                            <?php 
                                                            if ($days_diff < 0) {
                                                                echo "Échue depuis " . abs($days_diff) . " jour" . (abs($days_diff) > 1 ? 's' : '');
                                                            } else {
                                                                echo "Dans " . $days_diff . " jour" . ($days_diff > 1 ? 's' : '');
                                                            }
                                                            ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success"><?php echo number_format($facture['montant_total'], 2, ',', ' '); ?> DH</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php echo getStatusBadgeClass($facture['statut']); ?>">
                                                            <?php echo getStatusLabel($facture['statut']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="facture_detail.php?id=<?php echo $facture['id']; ?>" class="btn btn-outline-primary" title="Voir détail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="facture.php?id=<?php echo $facture['id']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="facture_detail.php?id=<?php echo $facture['id']; ?>&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteFacture(<?php echo $facture['id']; ?>)" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteFacture(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
        // TODO: Implement AJAX call to delete facture
        console.log('Deleting facture with ID:', id);
        // window.location.href = 'delete_facture.php?id=' + id;
    }
}
</script>

<?php include_once '../includes/scripts.php'; ?>