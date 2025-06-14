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
                                                <option value="confirme">Confirme</option>
                                                <option value="expediee">Expediee</option>
                                                <option value="livree">Livree</option>
                                                <option value="annulee">Annulee</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter
                                        </button>
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
                                    <h4 class="mb-0 fw-bold">84</h4>
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
                                    <h4 class="mb-0 fw-bold">23</h4>
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
                                    <h4 class="mb-0 fw-bold">47</h4>
                                    <p class="text-muted mb-0 small">Livrees</p>
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
                                    <h4 class="mb-0 fw-bold">285,650</h4>
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
                                            <th>Ne Commande</th>
                                            <th>Client</th>
                                            <th>Date</th>
                                            <th>Date Livraison</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="commandesTableBody">
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#CMD-2024-001</div>
                                                <small class="text-muted">ID: 1</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                        AH
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold small">Ahmed Hassan</div>
                                                        <small class="text-muted">Tech Solutions SARL</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">16/05/2024</div>
                                                <small class="text-muted">09:15</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-info me-1"></i>
                                                    20/05/2024
                                                </div>
                                                <small class="text-muted">Prevue</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">15,200.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">En attente</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="bon_commande_detail.php?id=1" class="btn btn-outline-primary" title="Voir detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="bon_commande.php?id=1" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="bon_commande_detail.php?id=1&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteCommande(1)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#CMD-2024-002</div>
                                                <small class="text-muted">ID: 2</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                        FE
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold small">Fatima El Amrani</div>
                                                        <small class="text-muted">Boutique Moderne</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">14/05/2024</div>
                                                <small class="text-muted">16:30</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-success me-1"></i>
                                                    18/05/2024
                                                </div>
                                                <small class="text-success">Livree</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">8,750.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Livree</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="bon_commande_detail.php?id=2" class="btn btn-outline-primary" title="Voir detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="bon_commande.php?id=2" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="bon_commande_detail.php?id=2&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteCommande(2)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#CMD-2024-003</div>
                                                <small class="text-muted">ID: 3</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                        YB
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold small">Youssef Benali</div>
                                                        <small class="text-muted">Import Export Co.</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">10/05/2024</div>
                                                <small class="text-muted">11:45</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-warning me-1"></i>
                                                    15/05/2024
                                                </div>
                                                <small class="text-warning">En cours</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">22,400.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">Expediee</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="bon_commande_detail.php?id=3" class="btn btn-outline-primary" title="Voir detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="bon_commande.php?id=3" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="bon_commande_detail.php?id=3&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteCommande(3)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de 1 e 10 sur 84 bons de commande
                                </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCommande(id) {
    if (confirm('etes-vous ser de vouloir supprimer ce bon de commande ?')) {
        // AJAX call to delete commande
        console.log('Deleting bon de commande with ID:', id);
    }
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    // Filter table rows based on status
    console.log('Filtering by status:', status);
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    // Filter table rows based on search term
    console.log('Searching for:', searchTerm);
});
</script>

<?php include_once '../includes/scripts.php'; ?>