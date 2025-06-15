<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Gestion des Utilisateurs</h1>
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
                                                <input type="text" class="form-control" placeholder="Rechercher un utilisateur..." id="searchInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter
                                        </button>
                                        <a href="utilisateur.php" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-1"></i>Nouvel Utilisateur
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
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">12</h4>
                                    <p class="text-muted mb-0 small">Total Utilisateurs</p>
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
                                        <i class="fas fa-user-check fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold">11</h4>
                                    <p class="text-muted mb-0 small">Actifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    Liste des Utilisateurs
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Date Création</th>
                                            <th>Statut</th>
                                            <th style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usersTableBody">
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        OB
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Omar Benjelloun</div>
                                                        <small class="text-muted">ID: 1</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">omar@gestion-vente.ma</div>
                                            </td>
                                            <td>
                                                <div class="small">+212 5 22 34 56 78</div>
                                            </td>
                                            <td>
                                                <div class="small">15/01/2024</div>
                                                <small class="text-muted">09:00</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="utilisateur.php?id=1" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleStatus(1)" title="Désactiver">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(1)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        SA
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Salma Alami</div>
                                                        <small class="text-muted">ID: 2</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">salma.alami@gestion-vente.ma</div>
                                            </td>
                                            <td>
                                                <div class="small">+212 6 98 76 54 32</div>
                                            </td>
                                            <td>
                                                <div class="small">20/01/2024</div>
                                                <small class="text-muted">14:30</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="utilisateur.php?id=2" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleStatus(2)" title="Désactiver">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(2)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        MH
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Mohammed Hilali</div>
                                                        <small class="text-muted">ID: 3</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">m.hilali@gestion-vente.ma</div>
                                            </td>
                                            <td>
                                                <div class="small">+212 6 11 22 33 44</div>
                                            </td>
                                            <td>
                                                <div class="small">25/01/2024</div>
                                                <small class="text-muted">10:15</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="utilisateur.php?id=3" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleStatus(3)" title="Désactiver">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(3)" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                        ZK
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Zineb Kaddouri</div>
                                                        <small class="text-muted">ID: 4</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">zineb.k@gestion-vente.ma</div>
                                            </td>
                                            <td>
                                                <div class="small">+212 6 77 88 99 00</div>
                                            </td>
                                            <td>
                                                <div class="small">01/02/2024</div>
                                                <small class="text-muted">16:00</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Inactif</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="utilisateur.php?id=4" class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success" onclick="toggleStatus(4)" title="Activer">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(4)" title="Supprimer">
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
                                    Affichage de 1 à 4 sur 12 utilisateurs
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
function deleteUser(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        // AJAX call to delete user
        console.log('Deleting user with ID:', id);
    }
}

function resetPassword(id) {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?')) {
        // AJAX call to reset password
        console.log('Resetting password for user ID:', id);
        alert('Un nouveau mot de passe a été envoyé par email à l\'utilisateur.');
    }
}

function toggleStatus(id) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?')) {
        // AJAX call to toggle status
        console.log('Toggling status for user ID:', id);
    }
}

// Filter functionality
document.getElementById('roleFilter').addEventListener('change', function() {
    const role = this.value;
    // Filter table rows based on role
    console.log('Filtering by role:', role);
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    // Filter table rows based on search term
    console.log('Searching for:', searchTerm);
});
</script>

<?php include_once '../includes/scripts.php'; ?>