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
                                                <input type="text" class="form-control" placeholder="Rechercher une facture..." id="searchInput">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" id="statusFilter">
                                                <option value="">Tous les statuts</option>
                                                <option value="brouillon">Brouillon</option>
                                                <option value="envoyee">Envoyée</option>
                                                <option value="payee">Payée</option>
                                                <option value="en_retard">En retard</option>
                                                <option value="annulee">Annulée</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter
                                        </button>
                                        <a href="facture.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouvelle Facture
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
                                        <i class="fas fa-file-invoice fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">127</h4>
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
                                    <h4 class="mb-0 fw-bold">34</h4>
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
                                    <h4 class="mb-0 fw-bold">78</h4>
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
                                    <h4 class="mb-0 fw-bold">15</h4>
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
                                    <h4 class="mb-0 fw-bold">542,380</h4>
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
                                    <h4 class="mb-0 fw-bold">398,250</h4>
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
                                    <h4 class="mb-0 fw-bold">144,130</h4>
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
                                    Liste des Factures
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
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
                                    <tbody id="facturesTableBody">
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#FAC-2024-001</div>
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
                                                <div class="small">18/05/2024</div>
                                                <small class="text-muted">14:30</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-info me-1"></i>
                                                    17/06/2024
                                                </div>
                                                <small class="text-success">Dans 25 jours</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">18,240.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Envoyée</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="facture_detail.php?id=1" class="btn btn-outline-primary" title="Voir détail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="facture.php?id=1" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="facture_detail.php?id=1&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFacture(1)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#FAC-2024-002</div>
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
                                                <div class="small">16/05/2024</div>
                                                <small class="text-muted">11:15</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-success me-1"></i>
                                                    15/06/2024
                                                </div>
                                                <small class="text-success">Dans 23 jours</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">10,500.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Payée</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="facture_detail.php?id=2" class="btn btn-outline-primary" title="Voir détail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="facture.php?id=2" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="facture_detail.php?id=2&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFacture(2)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#FAC-2024-003</div>
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
                                                <div class="small">12/05/2024</div>
                                                <small class="text-muted">16:45</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-danger me-1"></i>
                                                    11/05/2024
                                                </div>
                                                <small class="text-danger">Échue depuis 7 jours</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">26,880.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">En retard</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="facture_detail.php?id=3" class="btn btn-outline-primary" title="Voir détail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="facture.php?id=3" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="facture_detail.php?id=3&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFacture(3)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">#FAC-2024-004</div>
                                                <small class="text-muted">ID: 4</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 35px; height: 35px; font-size: 12px;">
                                                        KC
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold small">Karim Cherkaoui</div>
                                                        <small class="text-muted">Digital Agency</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">20/05/2024</div>
                                                <small class="text-muted">10:00</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-calendar text-primary me-1"></i>
                                                    19/06/2024
                                                </div>
                                                <small class="text-success">Dans 27 jours</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">15,600.00 DH</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">Brouillon</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="facture_detail.php?id=4" class="btn btn-outline-primary" title="Voir détail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="facture.php?id=4" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="facture_detail.php?id=4&print=1" class="btn btn-outline-info" title="Imprimer" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFacture(4)" title="Supprimer">
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
                                    Affichage de 1 à 10 sur 127 factures
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
function deleteFacture(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
        // AJAX call to delete facture
        console.log('Deleting facture with ID:', id);
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