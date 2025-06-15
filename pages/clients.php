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
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter
                                        </button>
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
                                    <h4 class="mb-0 fw-bold">856</h4>
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
                                    <h4 class="mb-0 fw-bold">42</h4>
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
                                    <tbody id="clientsTableBody">
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        AH
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Ahmed Hassan</div>
                                                        <small class="text-muted">Tech Solutions SARL</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">
                                                        <i class="fas fa-envelope text-muted me-1"></i>
                                                        ahmed.hassan@techsol.ma
                                                    </div>
                                                    <div class="small">
                                                        <i class="fas fa-phone text-muted me-1"></i>
                                                        +212 6 12 34 56 78
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    Casablanca
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">15/05/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewClient(1)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_client.php?id=1" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteClient(1)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        FE
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Fatima El Amrani</div>
                                                        <small class="text-muted">Boutique Moderne</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">
                                                        <i class="fas fa-envelope text-muted me-1"></i>
                                                        fatima@boutique-moderne.com
                                                    </div>
                                                    <div class="small">
                                                        <i class="fas fa-phone text-muted me-1"></i>
                                                        +212 6 87 65 43 21
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    Rabat
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Inactif</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">28/04/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewClient(2)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_client.php?id=2" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteClient(2)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        YB
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Youssef Benali</div>
                                                        <small class="text-muted">Import Export Co.</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">
                                                        <i class="fas fa-envelope text-muted me-1"></i>
                                                        y.benali@importexport.ma
                                                    </div>
                                                    <div class="small">
                                                        <i class="fas fa-phone text-muted me-1"></i>
                                                        +212 6 99 88 77 66
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    Marrakech
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">10/05/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewClient(3)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_client.php?id=3" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteClient(3)" title="Supprimer">
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
                                    Affichage de 1 à 10 sur 856 clients
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

<?php include_once '../includes/scripts.php'; ?>