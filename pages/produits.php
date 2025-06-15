<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Liste des Produits</h1>
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
                                                <input type="text" class="form-control" placeholder="Rechercher un produit..." id="searchInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter
                                        </button>
                                        <a href="produit.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouveau Produit
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
                                        <i class="fas fa-box fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">324</h4>
                                    <p class="text-muted mb-0 small">Produits Total</p>
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
                                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">12</h4>
                                    <p class="text-muted mb-0 small">Stock Faible</p>
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
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">8</h4>
                                    <p class="text-muted mb-0 small">Nouveaux ce mois</p>
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
                                    <h4 class="mb-0 fw-bold">248,500</h4>
                                    <p class="text-muted mb-0 small">Valeur Stock (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-boxes text-primary me-2"></i>
                                    Liste des Produits
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th>Prix Unitaire</th>
                                            <th>Stock</th>
                                            <th>Statut</th>
                                            <th>Date Creation</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produitsTableBody">
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient rounded-3 d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-laptop"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Ordinateur Portable HP</div>
                                                        <small class="text-muted">Laptop HP 15-dw3000 Intel Core i5</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">8,500.00 DH</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success me-2">45</span>
                                                    <small class="text-muted">En stock</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Disponible</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">15/05/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewProduct(1)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_produit.php?id=1" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteProduct(1)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-gradient rounded-3 d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Smartphone Samsung</div>
                                                        <small class="text-muted">Galaxy A54 5G 128GB</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">3,200.00 DH</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning me-2">8</span>
                                                    <small class="text-warning">Stock faible</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Stock Faible</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">28/04/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewProduct(2)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_produit.php?id=2" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteProduct(2)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-gradient rounded-3 d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-tv"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Ecran LED 24"</div>
                                                        <small class="text-muted">Monitor Dell 24" Full HD</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">1,850.00 DH</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-danger me-2">0</span>
                                                    <small class="text-danger">Rupture</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">Rupture Stock</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">10/05/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewProduct(3)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_produit.php?id=3" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteProduct(3)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-gradient rounded-3 d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-keyboard"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Clavier Mecanique</div>
                                                        <small class="text-muted">Clavier Gaming RGB</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">450.00 DH</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success me-2">32</span>
                                                    <small class="text-muted">En stock</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Disponible</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">05/05/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewProduct(4)" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit_produit.php?id=4" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteProduct(4)" title="Supprimer">
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
                                    Affichage de 1 e 10 sur 324 produits
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