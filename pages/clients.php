<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Handle delete request
if (isset($_POST['delete_client'])) {
    $client_id = $_POST['client_id'];
    try {
        $stmt = $db->prepare("UPDATE clients SET status = 0 WHERE id = ?");
        $stmt->execute([$client_id]);
        $success_message = "Client supprimé avec succès";
    } catch(PDOException $e) {
        $error_message = "Erreur lors de la suppression du client";
    }
}

// Fetch clients data
try {
    $clients_query = "SELECT * FROM clients WHERE status = 1 ORDER BY cree_le DESC";
    $clients = $db->query($clients_query)->fetchAll();
    
    $stats_clients_actifs = $db->query("SELECT COUNT(*) as total FROM clients WHERE status = 1")->fetch();
    $stats_nouveaux_mois = $db->query("SELECT COUNT(*) as total FROM clients WHERE status = 1 AND MONTH(cree_le) = MONTH(CURDATE()) AND YEAR(cree_le) = YEAR(CURDATE())")->fetch();
    
} catch(PDOException $e) {
    $clients = [];
    $stats_clients_actifs = ['total' => 0];
    $stats_nouveaux_mois = ['total' => 0];
    $error_message = "Erreur lors du chargement des clients";
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
        <h1 class="page-title">Liste des Clients</h1>
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
                                                <input type="text" class="form-control" placeholder="Rechercher un client..." id="searchInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="export.php?type=clients&format=csv" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter CSV
                                        </a>
                                        <a href="client.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouveau Client
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
                                    <div class="bg-success bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-user-check fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_clients_actifs['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Clients Actifs</p>
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
                                        <i class="fas fa-user-friends fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_nouveaux_mois['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Nouveaux ce mois</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    Liste des Clients
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
    
                                            <th>Client</th>
                                            <th>Contact</th>
                                            <th>Localisation</th>
                                            <th>Statut</th>
                                            <th>Dernière commande</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                                <?php 
                                                                if ($client['entreprise']) {
                                                                    echo strtoupper(substr($client['entreprise'], 0, 2));
                                                                } else {
                                                                    echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1));
                                                                }
                                                                ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                                                </div>
                                                                <?php if ($client['entreprise']): ?>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($client['entreprise']); ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <?php if ($client['email']): ?>
                                                                <div class="small">
                                                                    <i class="fas fa-envelope text-muted me-1"></i>
                                                                    <?php echo htmlspecialchars($client['email']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($client['telephone']): ?>
                                                                <div class="small">
                                                                    <i class="fas fa-phone text-muted me-1"></i>
                                                                    <?php echo htmlspecialchars($client['telephone']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($client['ville']): ?>
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                                <?php echo htmlspecialchars($client['ville']); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">Actif</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?php echo date('d/m/Y', strtotime($client['cree_le'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="client.php?id=<?php echo $client['id']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteClient(<?php echo $client['id']; ?>)" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="fas fa-users fa-2x mb-3"></i><br>
                                                    Aucun client trouvé
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php if (!empty($clients)): ?>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de <?php echo count($clients); ?> sur <?php echo $stats_clients_actifs['total']; ?> clients
                                </div>
                                <?php if ($stats_clients_actifs['total'] > 10): ?>
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
                Êtes-vous sûr de vouloir supprimer ce client ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="client_id" id="deleteClientId">
                    <button type="submit" name="delete_client" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteClient(clientId) {
    document.getElementById('deleteClientId').value = clientId;
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
</script>

<?php include_once '../includes/scripts.php'; ?>